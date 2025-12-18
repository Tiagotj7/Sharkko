<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/app/partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/app/partials/flash.php'; ?>

  <div class="chat-header">
    <a href="messages.php" class="btn-outline">← Voltar</a>
    <h1>Conversa com <?= esc($other['name']) ?></h1>
  </div>

  <section class="chat-messages">
    <?php foreach ($messages as $msg): ?>
      <div class="message <?= $msg['sender_id'] == $user['id'] ? 'message-own' : 'message-other' ?>">
        <div class="message-author">
          <?php if ($msg['sender_id'] != $user['id']): ?>
            <div class="avatar-xs">
              <?php if (!empty($msg['sender_avatar'])): ?>
                <img src="uploads/avatars/<?= esc($msg['sender_avatar']) ?>" alt="">
              <?php else: ?>
                <div class="avatar-placeholder"><?= strtoupper($msg['sender_name'][0]) ?></div>
              <?php endif; ?>
            </div>
            <strong><?= esc($msg['sender_name']) ?></strong>
          <?php endif; ?>
        </div>
        <p><?= nl2br(esc($msg['body'])) ?></p>
        <span class="message-date"><?= esc($msg['created_at']) ?></span>
      </div>
    <?php endforeach; ?>
  </section>

  <form action="send_message.php" method="post" class="message-form">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">
    <input type="hidden" name="conversation_id" value="<?= (int)$conversationId ?>">
    <textarea name="body" rows="2" placeholder="Digite sua mensagem..." required></textarea>
    <button type="submit" class="btn-primary">Enviar</button>
  </form>
</main>

<?php include __DIR__ . '/app/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
