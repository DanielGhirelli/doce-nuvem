<?php
require __DIR__ . '/../src/bootstrap.php';
require_login();

$st = $pdo->prepare('SELECT username, name, address FROM users WHERE id = ?');
$st->execute([$_SESSION['user']['id']]);
$user = $st->fetch();
$error = '';
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    if (mb_strlen($address) < 5 || mb_strlen($address) > 255) {
        $error = 'Informe um endereço válido.';
    } else {
        $pdo->prepare('UPDATE users SET address = ? WHERE id = ?')->execute([$address, $_SESSION['user']['id']]);
        $_SESSION['user']['address'] = $address;
        $saved = true;
    }
    $user['address'] = $address;
}

$orders = fetch_orders($pdo, $_SESSION['user']['id']);

header_html('Meu perfil');
?>
<div class="profile">
  <div class="panel">
    <div class="hero-emoji">👤</div>
    <h1><?= e($user['name']) ?></h1>
    <p class="muted">@<?= e($user['username']) ?></p>
    <?php if ($saved): ?><div class="alert ok">Endereço atualizado!</div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <label>Endereço de entrega
        <input name="address" value="<?= e($user['address']) ?>" required minlength="5" maxlength="255" placeholder="Rua, número, bairro, cidade">
      </label>
      <button class="btn">Salvar endereço</button>
    </form>
  </div>

  <div class="panel">
    <h2>Meus pedidos</h2>
    <?php if (!$orders): ?>
      <p class="muted">Você ainda não fez pedidos. <a href="index.php">Escolher cupcakes</a></p>
    <?php endif; ?>
    <?php foreach ($orders as $o): ?>
      <div class="my-order">
        <div>
          <b>Pedido #<?= $o['id'] ?></b> <small class="muted">· <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small>
          <p class="muted"><?= e($o['items']) ?></p>
          <?= status_badge($o['status']) ?>
        </div>
        <div class="my-order-total">
          <b><?= money($o['total']) ?></b>
          <small class="muted"><?= PAYMENTS[$o['payment']] ?></small>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php footer_html();
