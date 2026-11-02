<?php
require __DIR__ . '/../src/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([STATUS_DELIVERED, (int)($_POST['order_id'] ?? 0)]);
    header('Location: admin.php');
    exit;
}

$orders = fetch_orders($pdo);
$revenue = array_sum(array_column($orders, 'total'));
$pending = count(array_filter($orders, fn($o) => $o['status'] !== STATUS_DELIVERED));

header_html('Pedidos');
?>
<h1 class="page-title">Pedidos</h1>

<div class="stats">
  <div class="panel"><span class="muted">Pedidos</span><strong><?= count($orders) ?></strong></div>
  <div class="panel"><span class="muted">A entregar</span><strong><?= $pending ?></strong></div>
  <div class="panel"><span class="muted">Faturamento</span><strong><?= money($revenue) ?></strong></div>
</div>

<div class="panel table-wrap">
  <?php if (!$orders): ?>
    <p class="muted">Nenhum pedido ainda.</p>
  <?php else: ?>
    <table class="orders">
      <thead>
        <tr><th>#</th><th>Data</th><th>Cliente</th><th>Itens</th><th>Pagamento</th><th>Total</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><b><?= $o['id'] ?></b></td>
            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
            <td><?= e($o['name']) ?><br><small class="muted"><?= e($o['address']) ?></small></td>
            <td><?= e($o['items']) ?></td>
            <td><?= PAYMENTS[$o['payment']] ?></td>
            <td><b><?= money($o['total']) ?></b></td>
            <td><?= status_badge($o['status']) ?></td>
            <td>
              <?php if ($o['status'] !== STATUS_DELIVERED): ?>
                <form method="post">
                  <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                  <button class="btn check" title="Marcar como entregue" aria-label="Marcar como entregue">✓</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php footer_html();
