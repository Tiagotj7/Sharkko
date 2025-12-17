<?php
// views/partials/header.php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/helpers/utils.php';

$user = current_user();
?>
<header class="topbar">
  <div class="container topbar-inner">
    <a href="index.php" class="logo">DevNetwork</a>

    <nav class="nav-links">
      <?php if ($user): ?>
        <a href="feed.php">Feed</a>
        <a href="post_create.php">Novo Projeto</a>
        <a href="profile.php?id=<?= (int)$user['id'] ?>">Meu Perfil</a>
      <?php endif; ?>
    </nav>

    <div class="topbar-right">
      <button id="themeToggle" class="btn-secondary">Tema</button>

      <?php if ($user): ?>
        <span class="user-name"><?= esc($user['name']) ?></span>
        <a href="logout.php" class="btn-outline">Sair</a>
      <?php else: ?>
        <a href="login.php" class="btn-outline">Login</a>
        <a href="register.php" class="btn-primary">Registrar</a>
      <?php endif; ?>
    </div>
  </div>
</header>