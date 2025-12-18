<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/app/partials/flash.php'; ?>

<section class="new-post-card">
  <h2>O que você está construindo hoje?</h2>
  <a class="btn-primary" href="post_create.php">Criar novo projeto</a>
</section>

<section class="feed">
  <?php foreach ($posts as $post): ?>
    <?php
      $likesCount = Like::countForPost((int)$post['id']);
      $userLiked = Like::userLiked((int)$post['id'], (int)$user['id']);
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
        <form action="post_like.php" method="post" class="inline-form">
          <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">
          <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
          <button class="btn-link">
            <?= $userLiked ? 'Descurtir' : 'Curtir' ?> (<?= $likesCount ?>)
          </button>
        </form>
        <a href="post_show.php?id=<?= (int)$post['id'] ?>" class="btn-link">Ver detalhes</a>
      </footer>
    </article>
  <?php endforeach; ?>
</section>

</main>
<?php include __DIR__ . '/app/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
