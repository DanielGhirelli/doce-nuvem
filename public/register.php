<?php
require __DIR__ . '/../src/bootstrap.php';

$errors = [];
$f = ['username' => '', 'name' => '', 'address' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($f as $k => $_) $f[$k] = trim($_POST[$k] ?? '');
    $password = $_POST['password'] ?? '';

    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $f['username'])) $errors[] = 'Usuário deve ter 3 a 30 letras, números ou _.';
    if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
    if ($password !== ($_POST['password2'] ?? '')) $errors[] = 'As senhas não conferem.';
    if (mb_strlen($f['name']) < 2) $errors[] = 'Informe seu nome.';
    if (mb_strlen($f['address']) < 5) $errors[] = 'Informe um endereço válido.';

    if (!$errors) {
        $st = $pdo->prepare('SELECT 1 FROM users WHERE username = ?');
        $st->execute([$f['username']]);
        if ($st->fetch()) $errors[] = 'Esse usuário já existe.';
    }

    if (!$errors) {
        $pdo->prepare('INSERT INTO users (username, password_hash, name, address) VALUES (?, ?, ?, ?)')
            ->execute([$f['username'], password_hash($password, PASSWORD_DEFAULT), $f['name'], $f['address']]);
        $_SESSION['flash'] = 'Conta criada! Faça login.';
        header('Location: login.php');
        exit;
    }
}

header_html('Criar conta');
?>
<div class="auth-card">
  <h1>Criar conta</h1>
  <p class="muted">Cadastre-se para pedir seus cupcakes</p>
  <?php foreach ($errors as $err): ?><div class="alert error"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" class="validate">
    <label>Usuário <input name="username" value="<?= e($f['username']) ?>" required minlength="3" maxlength="30" pattern="[a-zA-Z0-9_]+" data-msg="Use apenas letras, números ou _."></label>
    <label>Nome completo <input name="name" value="<?= e($f['name']) ?>" required minlength="2" maxlength="100"></label>
    <label>Endereço de entrega <input name="address" value="<?= e($f['address']) ?>" required minlength="5" maxlength="255" placeholder="Rua, número, bairro, cidade"></label>
    <div class="row">
      <label>Senha <input type="password" name="password" required minlength="6"></label>
      <label>Confirmar senha <input type="password" name="password2" required minlength="6"></label>
    </div>
    <button class="btn">Cadastrar</button>
  </form>
  <p class="switch">Já tem conta? <a href="login.php">Entrar</a></p>
</div>
<?php footer_html();
