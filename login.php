<?php
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('login.php');
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    remember_old($_POST);

    $user = User::findByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Credenciais inválidas.');
        redirect('login.php');
    }

    login_user((int)$user['id']);
    flash('success', 'Bem-vindo de volta!');
    redirect('feed.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/views/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/views/partials/header.php'; ?>
<main class="container main auth-page">
  <?php include __DIR__ . '/views/partials/flash.php'; ?>

  <h1>Login</h1>
  <form action="login.php" method="post" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      E-mail
      <input type="email" name="email" value="<?= esc(old('email')) ?>" required>
    </label>

    <label>
      Senha
      <input type="password" name="password" required>
    </label>

    <button class="btn-primary" type="submit">Entrar</button>
    <p>Não tem conta? <a href="register.php">Criar agora</a></p>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>