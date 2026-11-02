<?php
require __DIR__ . '/../src/bootstrap.php';

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([$username]);
    $user = $st->fetch();

    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'address' => $user['address'], 'is_admin' => (bool)$user['is_admin']];
        header('Location: ' . ($user['is_admin'] ? 'admin.php' : 'index.php'));
        exit;
    }
    $error = 'Usuário ou senha inválidos.';
}

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

header_html('Entrar');
?>
<div class="auth-card">
  <div class="hero-emoji">🧁</div>
  <h1>Bem-vindo de volta</h1>
  <p class="muted">Entre para escolher seus cupcakes</p>
  <?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="validate">
    <label>Usuário <input name="username" value="<?= e($username) ?>" required autofocus></label>
    <label>Senha <input type="password" name="password" required></label>
    <button class="btn">Entrar</button>
  </form>
  <p class="switch">Novo por aqui? <a href="register.php">Criar conta</a></p>
</div>
<?php footer_html();
