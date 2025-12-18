<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/partials/header.php'; ?>
<main class="container main auth-page">
  <?php include __DIR__ . '/app/partials/flash.php'; ?>

  <h1>Login</h1>
  <form action="login.php" method="post" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

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
<?php include __DIR__ . '/app/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
