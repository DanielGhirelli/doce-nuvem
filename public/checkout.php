<?php
require __DIR__ . '/../src/bootstrap.php';
require_login();

$payments = PAYMENTS;
$cupcakes = array_column($pdo->query('SELECT * FROM cupcakes')->fetchAll(), null, 'id');
$order = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode($_POST['cart'] ?? '{}', true) ?: [];
    $payment = $_POST['payment'] ?? '';

    // Prices always come from the DB, never from the client.
    $items = [];
    $total = 0;
    foreach ($cart as $id => $qty) {
        $qty = (int)$qty;
        if (isset($cupcakes[$id]) && $qty > 0 && $qty <= 99) {
            $items[$id] = $qty;
            $total += $cupcakes[$id]['price'] * $qty;
        }
    }

    if (!$items) $error = 'Seu carrinho está vazio.';
    elseif (!isset($payments[$payment])) $error = 'Escolha uma forma de pagamento.';
    else {
        $pdo->beginTransaction();
        $pdo->prepare('INSERT INTO orders (user_id, payment, total) VALUES (?, ?, ?)')
            ->execute([$_SESSION['user']['id'], $payment, $total]);
        $orderId = $pdo->lastInsertId();
        $st = $pdo->prepare('INSERT INTO order_items (order_id, cupcake_id, qty, price) VALUES (?, ?, ?, ?)');
        foreach ($items as $id => $qty) $st->execute([$orderId, $id, $qty, $cupcakes[$id]['price']]);
        $pdo->commit();
        $order = ['id' => $orderId, 'total' => $total, 'payment' => $payments[$payment]];
    }
}

header_html('Carrinho');

if ($order): ?>
  <div class="auth-card success">
    <div class="hero-emoji bounce">🧁</div>
    <h1>Pedido #<?= e($order['id']) ?> confirmado!</h1>
    <p class="status"><span class="dot"></span> Seu cupcake está sendo preparado para entrega</p>
    <p class="muted">Total <?= money($order['total']) ?> · Pagamento: <?= e($order['payment']) ?></p>
    <p class="muted">Entrega em: <?= e($_SESSION['user']['address']) ?></p>
    <a href="index.php" class="btn">Voltar ao cardápio</a>
  </div>
  <script>localStorage.removeItem('cart');</script>
<?php else: ?>
  <h1 class="page-title">Seu carrinho</h1>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <div class="checkout">
    <div class="panel" id="cart-items" data-cupcakes='<?= e(json_encode(array_map(fn($c) => [
        'name' => $c['name'], 'price' => (float)$c['price'], 'image' => $c['image'], 'color' => $c['color'],
    ], $cupcakes))) ?>'></div>

    <form method="post" class="panel" id="checkout-form">
      <h3>Pagamento</h3>
      <div class="pay-options">
        <?php foreach ($payments as $k => $label): ?>
          <label class="pay"><input type="radio" name="payment" value="<?= $k ?>" required><span><?= $label ?></span></label>
        <?php endforeach; ?>
      </div>
      <h3>Entrega</h3>
      <p class="muted"><?= e($_SESSION['user']['address']) ?></p>
      <div class="total">Total <strong id="cart-total">R$ 0,00</strong></div>
      <input type="hidden" name="cart" id="cart-input">
      <button class="btn">Finalizar pedido</button>
    </form>
  </div>
<?php endif;
footer_html(['checkout']);
