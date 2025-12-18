<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/app/partials/flash.php'; ?>

  <h1>Mensagens</h1>

  <section class="conversations">
    <?php if (empty($conversations)): ?>
      <p>Você não tem conversas ainda.</p>
    <?php else: ?>
      <?php foreach ($conversations as $conv): ?>
        <a href="conversation.php?id=<?= (int)$conv['id'] ?>" class="conversation-item">
          <div class="avatar-sm">
            <?php if (!empty($conv['other_avatar'])): ?>
              <img src="uploads/avatars/<?= esc($conv['other_avatar']) ?>" alt="">
            <?php else: ?>
              <div class="avatar-placeholder"><?= strtoupper($conv['other_name'][0]) ?></div>
            <?php endif; ?>
          </div>
          <div>
            <strong><?= esc($conv['other_name']) ?></strong>
            <p>Última atividade: <?= esc($conv['created_at']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<?php include __DIR__ . '/app/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
