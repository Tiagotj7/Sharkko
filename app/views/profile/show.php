<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <div class="profile-header">
    <div class="avatar-lg">
      <?php if (!empty($profileUser['avatar'])): ?>
        <img src="uploads/avatars/<?= esc($profileUser['avatar']) ?>" alt="">
      <?php else: ?>
        <div class="avatar-placeholder-lg">
          <?= strtoupper($profileUser['name'][0] ?? '?') ?>
        </div>
      <?php endif; ?>
    </div>

    <div>
      <h1><?= esc($profileUser['name'] ?? '') ?></h1>

      <?php if (!empty($profileUser['bio'])): ?>
        <p><?= nl2br(esc($profileUser['bio'])) ?></p>
      <?php endif; ?>

      <?php if (!empty($profileUser['location'])): ?>
        <p>📍 <?= esc($profileUser['location']) ?></p>
      <?php endif; ?>

      <div class="profile-links">
        <?php if (!empty($profileUser['github_url'])): ?>
          <a href="<?= esc($profileUser['github_url']) ?>" target="_blank">GitHub</a>
        <?php endif; ?>

        <?php if (!empty($profileUser['linkedin_url'])): ?>
          <a href="<?= esc($profileUser['linkedin_url']) ?>" target="_blank">LinkedIn</a>
        <?php endif; ?>

        <?php if (!empty($profileUser['website_url'])): ?>
          <a href="<?= esc($profileUser['website_url']) ?>" target="_blank">Website</a>
        <?php endif; ?>
      </div>

      <?php if (!empty($user) && (int)$profileUser['id'] === (int)$user['id']): ?>
        <a href="profile_edit.php" class="btn-primary">Editar perfil</a>
      <?php endif; ?>
    </div>
  </div>

  <section class="user-posts">
    <h2>Projetos de <?= esc($profileUser['name'] ?? '') ?></h2>

    <?php if (empty($posts)): ?>
      <p>Nenhum projeto ainda.</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <article class="post-card">
          <div class="post-body">
            <h3>
              <a href="post_show.php?id=<?= (int)$post['id'] ?>">
                <?= esc($post['title'] ?? '') ?>
              </a>
            </h3>

            <p>
              <?= nl2br(esc(substr($post['description'] ?? '', 0, 200))) ?>...
            </p>

            <?php if (!empty($post['languages'])): ?>
              <div class="chips">
                <?php foreach (explode(',', $post['languages']) as $lang): ?>
                  <span class="chip chip-lang"><?= esc(trim($lang)) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
