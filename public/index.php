<?php
require __DIR__ . '/../src/bootstrap.php';
require_login();

$cupcakes = $pdo->query('SELECT * FROM cupcakes ORDER BY id')->fetchAll();

header_html('Cardápio');
?>
<section class="hero">
  <h1>Cupcakes feitos hoje, <span>entregues fresquinhos</span></h1>
  <p class="muted">Escolha seus sabores favoritos e finalize em poucos cliques.</p>
</section>

<div class="grid">
  <?php foreach ($cupcakes as $c): ?>
    <article class="card" style="--bg: <?= e($c['color']) ?>">
      <img class="card-img" src="img/cupcakes/<?= e($c['image']) ?>" alt="Cupcake de <?= e($c['name']) ?>" loading="lazy">
      <div class="card-body">
        <h3><?= e($c['name']) ?></h3>
        <p class="muted"><?= e($c['description']) ?></p>
        <div class="card-foot">
          <strong><?= money($c['price']) ?></strong>
          <button class="btn small add-cart" data-id="<?= $c['id'] ?>">Adicionar</button>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
</div>
<div id="toast"></div>
<?php footer_html(['catalog']);
