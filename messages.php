<?php
require_once __DIR__ . '/app/controllers/MessageController.php';

MessageController::index();
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

  <h1>Mensagens</h1>

  <?php if (empty($conversations)): ?>
    <p>Você ainda não tem conversas. Visite o perfil de alguém e clique em "Enviar mensagem".</p>
  <?php else: ?>
    <div class="card">
      <ul class="conversation-list">
        <?php foreach ($conversations as $conv): ?>
          <li>
            <a href="conversation.php?id=<?= (int)$conv['id'] ?>">
              <div class="avatar-sm">
                <?php if (!empty($conv['other_avatar'])): ?>
                  <img src="uploads/avatars/<?= esc($conv['other_avatar']) ?>" alt="">
                <?php else: ?>
                  <div class="avatar-placeholder"><?= strtoupper($conv['other_name'][0]) ?></div>
                <?php endif; ?>
              </div>
              <span><?= esc($conv['other_name']) ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>