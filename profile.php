<?php
require_once __DIR__ . '/app/controllers/ProfileController.php';

ProfileController::show();
    flash('error', 'Usuário não encontrado.');
    redirect('index.php');


$posts = Post::byUser($profileId);
$current = current_user();
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

  <section class="profile-header card">
    <div class="avatar-lg">
      <?php if (!empty($profile['avatar'])): ?>
        <img src="uploads/avatars/<?= esc($profile['avatar']) ?>" alt="">
      <?php else: ?>
        <div class="avatar-placeholder-lg"><?= strtoupper($profile['name'][0]) ?></div>
      <?php endif; ?>
    </div>
    <div>
      <h1><?= esc($profile['name']) ?></h1>
      <?php if (!empty($profile['location'])): ?>
        <p><?= esc($profile['location']) ?></p>
      <?php endif; ?>

      <?php if (!empty($profile['bio'])): ?>
        <p><?= nl2br(esc($profile['bio'])) ?></p>
      <?php endif; ?>

      <div class="profile-links">
        <?php if (!empty($profile['github_url'])): ?>
          <a href="<?= esc($profile['github_url']) ?>" target="_blank">GitHub</a>
        <?php endif; ?>
        <?php if (!empty($profile['linkedin_url'])): ?>
          <a href="<?= esc($profile['linkedin_url']) ?>" target="_blank">LinkedIn</a>
        <?php endif; ?>
        <?php if (!empty($profile['website_url'])): ?>
          <a href="<?= esc($profile['website_url']) ?>" target="_blank">Site</a>
        <?php endif; ?>
      </div>

      <?php if ($current && (int)$current['id'] === (int)$profile['id']): ?>
        <a href="profile_edit.php" class="btn-outline">Editar perfil</a>
      <?php endif; ?>
    </div>
  </section>

  <section class="profile-posts">
    <h2>Projetos de <?= esc($profile['name']) ?></h2>

    <?php if (empty($posts)): ?>
      <p>Nenhum projeto publicado ainda.</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <article class="post-card">
          <h3><a href="post_show.php?id=<?= (int)$post['id'] ?>"><?= esc($post['title']) ?></a></h3>
          <p><?= nl2br(esc(substr($post['description'], 0, 200))) ?>...</p>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>