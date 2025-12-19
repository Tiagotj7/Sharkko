<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include BASE_PATH . '/views/partials/head.php'; ?>
</head>
<body>

<?php include BASE_PATH . '/views/partials/header.php'; ?>

<main class="feed-container">

<?php include BASE_PATH . '/views/partials/flash.php'; ?>

<!-- CRIAR POST -->
<section class="tweet-box">
    <form action="index.php?r=post_create" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

        <textarea name="description"
                  placeholder="O que você está construindo hoje?"
                  maxlength="500"
                  required></textarea>

        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn-primary">Publicar</button>
    </form>
</section>

<!-- FEED -->
<section class="feed">

<?php foreach ($posts as $post): ?>
<article class="tweet">

    <div class="tweet-avatar">
        <?php if ($post['user_avatar']): ?>
            <img src="uploads/avatars/<?= esc($post['user_avatar']) ?>">
        <?php else: ?>
            <div class="avatar-placeholder">
                <?= strtoupper($post['user_name'][0]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="tweet-content">
        <header>
            <strong><?= esc($post['user_name']) ?></strong>
            <span class="tweet-date"><?= esc($post['created_at']) ?></span>
        </header>

        <p><?= nl2br(esc($post['description'])) ?></p>

        <?php if ($post['image']): ?>
            <img src="uploads/posts/<?= esc($post['image']) ?>" class="tweet-image">
        <?php endif; ?>

        <footer class="tweet-actions">
            <a href="index.php?r=post_show&id=<?= (int)$post['id'] ?>">💬</a>
            <form action="index.php?r=post_like" method="post" class="inline">
                <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">
                <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                <button>❤️ <?= Like::countForPost($post['id']) ?></button>
            </form>
        </footer>
    </div>

</article>
<?php endforeach; ?>

</section>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
</body>
</html>
