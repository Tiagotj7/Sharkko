<?php
// search.php
require_once __DIR__ . '/app/config/bootstrap.php';


$q = trim($_GET['q'] ?? '');

$posts = [];
$users = [];

if ($q !== '') {
    $pdo = getPDO();
    $like = '%' . $q . '%';

    // Posts
    $sqlPosts = '
        SELECT p.*, u.name AS user_name, u.avatar AS user_avatar
        FROM posts p
        JOIN users u ON u.id = p.user_id
        WHERE p.title LIKE ? OR p.description LIKE ? OR p.tags LIKE ? OR p.languages LIKE ?
        ORDER BY p.created_at DESC
        LIMIT 50
    ';
    $stmt = $pdo->prepare($sqlPosts);
    $stmt->execute([$like, $like, $like, $like]);
    $posts = $stmt->fetchAll();

    // Usuários
    $sqlUsers = '
        SELECT * FROM users
        WHERE name LIKE ? OR bio LIKE ?
        ORDER BY created_at DESC
        LIMIT 50
    ';
    $stmt = $pdo->prepare($sqlUsers);
    $stmt->execute([$like, $like]);
    $users = $stmt->fetchAll();
}

$user = current_user();
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

  <h1>Buscar</h1>

  <form action="search.php" method="get" class="card">
    <label>
      Termo
      <input type="text" name="q" value="<?= esc($q) ?>" placeholder="Projetos, devs, empresas...">
    </label>
    <button class="btn-primary" type="submit">Buscar</button>
  </form>

  <?php if ($q === ''): ?>
    <p>Digite algo para buscar.</p>
  <?php else: ?>

    <section class="card">
      <h2>Usuários</h2>
      <?php if (empty($users)): ?>
        <p>Nenhum usuário encontrado.</p>
      <?php else: ?>
        <?php foreach ($users as $u): ?>
          <div class="comment-item">
            <div class="avatar-sm">
              <?php if (!empty($u['avatar'])): ?>
                <img src="uploads/avatars/<?= esc($u['avatar']) ?>" alt="">
              <?php else: ?>
                <div class="avatar-placeholder"><?= strtoupper($u['name'][0]) ?></div>
              <?php endif; ?>
            </div>
            <div>
              <strong><a href="profile.php?id=<?= (int)$u['id'] ?>"><?= esc($u['name']) ?></a></strong>
              <?php if (!empty($u['bio'])): ?>
                <p><?= nl2br(esc(substr($u['bio'], 0, 120))) ?>...</p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <section class="feed">
      <h2>Projetos</h2>
      <?php if (empty($posts)): ?>
        <p>Nenhum projeto encontrado.</p>
      <?php else: ?>
        <?php foreach ($posts as $post): ?>
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
            </div>
            <footer class="post-footer">
              <span><?= $likesCount ?> curtidas</span>
              <a href="post_show.php?id=<?= (int)$post['id'] ?>" class="btn-link">Ver detalhes</a>
            </footer>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

  <?php endif; ?>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>