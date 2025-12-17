<?php
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('register.php');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    remember_old($_POST);

    if ($name === '' || $email === '' || $password === '') {
        flash('error', 'Preencha todos os campos obrigatórios.');
        redirect('register.php');
    }

    if ($password !== $password_confirm) {
        flash('error', 'As senhas não conferem.');
        redirect('register.php');
    }

    $userId = User::create($name, $email, $password);

    if (!$userId) {
        flash('error', 'E-mail já cadastrado.');
        redirect('register.php');
    }

    login_user($userId);
    flash('success', 'Conta criada com sucesso!');
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

  <h1>Criar conta</h1>
  <form action="register.php" method="post" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      Nome
      <input type="text" name="name" value="<?= esc(old('name')) ?>" required>
    </label>

    <label>
      E-mail
      <input type="email" name="email" value="<?= esc(old('email')) ?>" required>
    </label>

    <label>
      Senha
      <input type="password" name="password" required>
    </label>

    <label>
      Confirmar senha
      <input type="password" name="password_confirm" required>
    </label>

    <button class="btn-primary" type="submit">Registrar</button>
    <p>Já tem conta? <a href="login.php">Entrar</a></p>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>