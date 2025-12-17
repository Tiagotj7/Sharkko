<?php
require_once __DIR__ . '/app/controllers/FavoriteController.php';

FavoriteController::index();

$favorites = Favorite::forUser((int)$user['id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/views/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/views/partials/header.php'; ?>
<main class="container main">
  <?php include __DIR__ . '/views/partials/flash.php'; ?>

  <h1>Meus favoritos</h1>

  <?php if (empty($favorites)): ?>
    <p>Você ainda não favoritou nenhum projeto.</p>
  <?php else: ?>
    <?php foreach ($favorites as $post): ?>
      <?php
        $likesCount = Like::countForPost((int)$post['id']);
      ?>
      <article class="post-card">
        <header class="post-header">
          <div class="post-author">
            <div class="avatar-sm">
              <?php if (!empty($post['user_avatar'])): ?>
                <img src="uploads/avatars/<?= esc($post['user_avatar']) ?>" alt="">
              <?php else: ?>
                <div class="avatar-placeholder"><?= strtoupper($post['user_name'][0]) ?></div>
              <?php endif; ?>
            </div>
            <div>
              <strong><?= esc($post['user_name']) ?></strong><br>
              <span class="post-date"><?= esc($post['created_at']) ?></span>
            </div>
          </div>
        </header>

        <div class="post-body">
          <h3><a href="post_show.php?id=<?= (int)$post['id'] ?>"><?= esc($post['title']) ?></a></h3>
          <p><?= nl2br(esc(substr($post['description'], 0, 220))) ?>...</p>

          <?php if (!empty($post['image'])): ?>
            <img src="uploads/posts/<?= esc($post['image']) ?>" class="post-image" alt="">
          <?php endif; ?>

          <?php if (!empty($post['languages'])): ?>
            <div class="chips">
              <?php foreach (explode(',', $post['languages']) as $lang): ?>
                <span class="chip chip-lang"><?= esc(trim($lang)) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($post['tags'])): ?>
            <div class="chips">
              <?php foreach (explode(',', $post['tags']) as $tag): ?>
                <span class="chip chip-tag">#<?= esc(trim($tag)) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <footer class="post-footer">
          <span><?= $likesCount ?> curtidas</span>
          <a href="post_show.php?id=<?= (int)$post['id'] ?>" class="btn-link">Ver detalhes</a>
        </footer>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>