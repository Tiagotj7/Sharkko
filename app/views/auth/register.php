<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="container main auth-page">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <h1>Criar conta</h1>
  <form action="register.php" method="post" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

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
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
