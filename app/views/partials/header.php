<?php
// views/partials/header.php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';

$user = current_user();
?>
<header class="topbar">
  <div class="container topbar-inner">
    <a href="index.php" class="logo">DevNetwork</a>

    <form action="search.php" method="get" class="search-form">
      <input type="text" name="q" placeholder="Buscar projetos, devs..." value="<?= esc($_GET['q'] ?? '') ?>">
    </form>

    <nav class="nav-links">
      <?php if ($user): ?>
        <a href="feed.php">Feed</a>
        <a href="messages.php">Mensagens</a>
        <a href="favorites.php">Favoritos</a>
        <a href="settings.php">Configurações</a>
      <?php endif; ?>
    </nav>

    <div class="topbar-right">
      <button id="themeToggle" class="btn-secondary">Tema</button>

      <?php if ($user): ?>
        <a href="profile.php?id=<?= (int)$user['id'] ?>" class="user-name"><?= esc($user['name']) ?></a>
        <a href="logout.php" class="btn-outline">Sair</a>
      <?php else: ?>
        <a href="login.php" class="btn-outline">Login</a>
        <a href="register.php" class="btn-primary">Registrar</a>
      <?php endif; ?>
    </div>
  </div>
</header>