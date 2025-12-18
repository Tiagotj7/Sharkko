<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/app/partials/flash.php'; ?>

  <section class="hero">
    <div>
      <h1>Conecte devs, empresas e projetos.</h1>
      <p>Divulgue projetos, encontre talentos e oportunidades, em um só lugar.</p>
      <a class="btn-primary" href="register.php">Começar agora</a>
      <a class="btn-outline" href="login.php">Já tenho conta</a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/app/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
