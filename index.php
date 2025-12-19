<?php

define('BASE_PATH', __DIR__ . '/app');

require_once __DIR__ . '/app/config/bootstrap.php';

require_once __DIR__ . '/app/controllers/PostController.php';

PostController::index();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/app/models/Post.php';
require_once __DIR__ . '/app/models/Like.php';

$user = current_user();
$posts = Post::allWithUser();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/views/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/views/partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/app/views/partials/flash.php'; ?>

  <?php if (!$user): ?>
    <section class="hero">
      <div>
        <h1>Conecte devs, empresas e projetos.</h1>
        <p>Divulgue projetos, encontre talentos e oportunidades, em um só lugar.</p>
        <a class="btn-primary" href="register.php">Começar agora</a>
        <a class="btn-outline" href="login.php">Já tenho conta</a>
      </div>
    </section>
  <?php endif; ?>

  <section class="feed">
    <h2>Projetos recentes</h2>

    <?php foreach ($posts as $post): ?>
      <?php
        $likesCount = Like::countForPost((int)$post['id']);
        $userLiked = $user ? Like::userLiked((int)$post['id'], (int)$user['id']) : false;
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
          <p><?= nl2br(esc(substr($post['description'], 0, 200))) ?>...</p>

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
          <form action="post_like.php" method="post" class="inline-form">
            <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
            <button class="btn-link" <?= $user ? '' : 'disabled' ?>>
              <?= $userLiked ? 'Descurtir' : 'Curtir' ?> (<?= $likesCount ?>)
            </button>
          </form>
          <a href="post_show.php?id=<?= (int)$post['id'] ?>" class="btn-link">Comentários</a>
        </footer>
      </article>
    <?php endforeach; ?>
  </section>
</main>

<?php include __DIR__ . '/app/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>

