<?php
require_once __DIR__ . '/app/controllers/PostController.php';

PostController::show();

if (!$post) {
    flash('error', 'Post não encontrado.');
    redirect('index.php');
}

$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('post_show.php?id=' . $postId);
    }

    $body = trim($_POST['body'] ?? '');
    if ($body !== '') {
        Comment::create($postId, (int)$user['id'], $body);
        flash('success', 'Comentário publicado.');
    }
    redirect('post_show.php?id=' . $postId);
}

$comments = Comment::forPost($postId);
$likesCount = Like::countForPost($postId);
$userLiked = $user ? Like::userLiked($postId, (int)$user['id']) : false;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/views/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/views/partials/header.php'; ?>
<main class="container main">
  <?php include __DIR__ . '/views/partials/flash.php'; ?>

  <article class="post-detail card">
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
      <h1><?= esc($post['title']) ?></h1>
      <p><?= nl2br(esc($post['description'])) ?></p>

      <?php if (!empty($post['image'])): ?>
        <img class="post-image" src="uploads/posts/<?= esc($post['image']) ?>" alt="">
      <?php endif; ?>

      <?php if (!empty($post['languages'])): ?>
        <h4>Linguagens/Tecnologias</h4>
        <div class="chips">
          <?php foreach (explode(',', $post['languages']) as $lang): ?>
            <span class="chip chip-lang"><?= esc(trim($lang)) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($post['tags'])): ?>
        <h4>Hashtags</h4>
        <div class="chips">
          <?php foreach (explode(',', $post['tags']) as $tag): ?>
            <span class="chip chip-tag">#<?= esc(trim($tag)) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($post['contact_email']) || !empty($post['contact_link'])): ?>
        <h4>Contato</h4>
        <ul>
          <?php if (!empty($post['contact_email'])): ?>
            <li>E-mail: <a href="mailto:<?= esc($post['contact_email']) ?>"><?= esc($post['contact_email']) ?></a></li>
          <?php endif; ?>
          <?php if (!empty($post['contact_link'])): ?>
            <li>Link: <a href="<?= esc($post['contact_link']) ?>" target="_blank"><?= esc($post['contact_link']) ?></a></li>
          <?php endif; ?>
        </ul>
      <?php endif; ?>
    </div>

    <footer class="post-footer">
      <form action="post_like.php" method="post" class="inline-form">
        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
        <button class="btn-link" <?= $user ? '' : 'disabled' ?>>
          <?= $userLiked ? 'Descurtir' : 'Curtir' ?> (<?= $likesCount ?>)
        </button>
      </form>
    </footer>
  </article>

  <section class="comments card">
    <h2>Comentários (<?= count($comments) ?>)</h2>

    <?php if ($user): ?>
      <form action="post_show.php?id=<?= (int)$post['id'] ?>" method="post" class="comment-form">
        <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">
        <textarea name="body" rows="3" placeholder="Escreva um comentário..."></textarea>
        <button class="btn-primary" type="submit">Comentar</button>
      </form>
    <?php else: ?>
      <p><a href="login.php">Entre</a> para comentar.</p>
    <?php endif; ?>

    <div class="comment-list">
      <?php foreach ($comments as $c): ?>
        <div class="comment-item">
          <div class="avatar-sm">
            <?php if (!empty($c['user_avatar'])): ?>
              <img src="uploads/avatars/<?= esc($c['user_avatar']) ?>" alt="">
            <?php else: ?>
              <div class="avatar-placeholder"><?= strtoupper($c['user_name'][0]) ?></div>
            <?php endif; ?>
          </div>
          <div>
            <strong><?= esc($c['user_name']) ?></strong>
            <span class="comment-date"><?= esc($c['created_at']) ?></span>
            <p><?= nl2br(esc($c['body'])) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>