<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <article class="post-detail">
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
      <?php if ($post['user_id'] == $user['id']): ?>
        <div class="post-actions">
          <a href="post_edit.php?id=<?= (int)$post['id'] ?>" class="btn-outline">Editar</a>
          <form action="post_delete.php" method="post" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
            <button type="submit" class="btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
          </form>
        </div>
      <?php endif; ?>
    </header>

    <div class="post-body">
      <h1><?= esc($post['title']) ?></h1>
      <p><?= nl2br(esc($post['description'])) ?></p>

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

      <?php if (!empty($post['contact_email']) || !empty($post['contact_link'])): ?>
        <div class="contact-info">
          <h3>Contato</h3>
          <?php if (!empty($post['contact_email'])): ?>
            <p>E-mail: <a href="mailto:<?= esc($post['contact_email']) ?>"><?= esc($post['contact_email']) ?></a></p>
          <?php endif; ?>
          <?php if (!empty($post['contact_link'])): ?>
            <p>Link: <a href="<?= esc($post['contact_link']) ?>" target="_blank"><?= esc($post['contact_link']) ?></a></p>
          <?php endif; ?>
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
    </footer>
  </article>

  <section class="comments">
    <h2>Comentários</h2>

    <?php foreach ($comments as $comment): ?>
      <div class="comment">
        <div class="comment-author">
          <div class="avatar-xs">
            <?php if (!empty($comment['sender_avatar'])): ?>
              <img src="uploads/avatars/<?= esc($comment['sender_avatar']) ?>" alt="">
            <?php else: ?>
              <div class="avatar-placeholder"><?= strtoupper($comment['sender_name'][0]) ?></div>
            <?php endif; ?>
          </div>
          <strong><?= esc($comment['sender_name']) ?></strong>
          <span class="comment-date"><?= esc($comment['created_at']) ?></span>
        </div>
        <p><?= nl2br(esc($comment['body'])) ?></p>
      </div>
    <?php endforeach; ?>

    <form action="post_comment.php" method="post" class="comment-form">
      <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">
      <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
      <textarea name="body" rows="3" placeholder="Escreva um comentário..." required></textarea>
      <button type="submit" class="btn-primary">Comentar</button>
    </form>
  </section>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
