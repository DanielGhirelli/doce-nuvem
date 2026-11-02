<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> · Doce Nuvem</title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<header class="topbar">
  <div class="brand">
    <a href="index.php" class="logo">🧁 Doce Nuvem</a>
    <?php if (!empty($_SESSION['user'])): ?><a href="index.php" class="order-link">Fazer pedido</a><?php endif; ?>
  </div>
  <?php if (!empty($_SESSION['user'])): ?>
    <nav>
      <span>Olá, <?= e($_SESSION['user']['name']) ?></span>
      <?php if (!empty($_SESSION['user']['is_admin'])): ?><a href="admin.php">Pedidos</a><?php endif; ?>
      <a href="profile.php">Meu perfil</a>
      <a href="checkout.php" class="cart-link">Carrinho <b id="cart-count">0</b></a>
      <a href="logout.php">Sair</a>
    </nav>
  <?php endif; ?>
</header>
<main>
