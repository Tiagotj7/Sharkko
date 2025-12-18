<?php
require_once __DIR__ . '/app/controllers/MessageController.php';

MessageController::show();
$user = current_user();

// Se vier ?user_id=XX, cria/abre conversa entre o usuário atual e o outro
if (isset($_GET['user_id']) && !isset($_GET['id'])) {
    $otherId = (int)$_GET['user_id'];
    if ($otherId === (int)$user['id']) {
        flash('error', 'Você não pode conversar consigo mesmo.');
        redirect('messages.php');
    }
    $other = User::findById($otherId);
    if (!$other) {
        flash('error', 'Usuário não encontrado.');
        redirect('messages.php');
    }
    $convId = Conversation::findOrCreateBetween((int)$user['id'], $otherId);
    redirect('conversation.php?id=' . $convId);
}

$conversationId = (int)($_GET['id'] ?? 0);
if ($conversationId <= 0) {
    flash('error', 'Conversa inválida.');
    redirect('messages.php');
}

$conversation = Conversation::findForUser($conversationId, (int)$user['id']);
if (!$conversation) {
    flash('error', 'Conversa não encontrada ou você não tem acesso.');
    redirect('messages.php');
}

$other = Conversation::otherParticipant($conversationId, (int)$user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('conversation.php?id=' . $conversationId);
    }
    $body = trim($_POST['body'] ?? '');
    if ($body !== '') {
        Message::create($conversationId, (int)$user['id'], $body);
    }
    redirect('conversation.php?id=' . $conversationId);
}

$messages = Message::forConversation($conversationId);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/app/views/partials/head.php'; ?>
  <style>
    .conversation-wrapper {
      display: flex;
      flex-direction: column;
      height: 70vh;
      max-height: 600px;
    }
    .conversation-messages {
      flex: 1;
      overflow-y: auto;
      padding-right: .5rem;
    }
    .message-item {
      display: flex;
      margin-bottom: .5rem;
    }
    .message-item.own {
      justify-content: flex-end;
    }
    .message-bubble {
      max-width: 70%;
      padding: .4rem .6rem;
      border-radius: .6rem;
      margin-left: .4rem;
      margin-right: .4rem;
      font-size: .9rem;
    }
    .message-item.own .message-bubble {
      background: var(--primary);
      color: white;
      border-bottom-right-radius: 0;
    }
    .message-item.other .message-bubble {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-bottom-left-radius: 0;
    }
    .conversation-form {
      margin-top: .5rem;
      display: flex;
      gap: .5rem;
    }
    .conversation-form textarea {
      resize: none;
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/views/partials/header.php'; ?>
<main class="container main">
  <?php include __DIR__ . '/views/partials/flash.php'; ?>

  <div class="card conversation-wrapper">
    <header class="post-header">
      <div class="post-author">
        <div class="avatar-sm">
          <?php if (!empty($other['avatar'])): ?>
            <img src="uploads/avatars/<?= esc($other['avatar']) ?>" alt="">
          <?php else: ?>
            <div class="avatar-placeholder"><?= strtoupper($other['name'][0]) ?></div>
          <?php endif; ?>
        </div>
        <div>
          <strong><?= esc($other['name']) ?></strong>
          <p style="margin:0;font-size:.85rem;color:var(--muted);">Conversa privada</p>
        </div>
      </div>
    </header>

    <div class="conversation-messages">
      <?php if (empty($messages)): ?>
        <p style="color:var(--muted);">Ainda não há mensagens. Comece a conversa!</p>
      <?php else: ?>
        <?php foreach ($messages as $m): ?>
          <?php $own = ((int)$m['sender_id'] === (int)$user['id']); ?>
          <div class="message-item <?= $own ? 'own' : 'other' ?>">
            <?php if (!$own): ?>
              <div class="avatar-sm">
                <?php if (!empty($m['sender_avatar'])): ?>
                  <img src="uploads/avatars/<?= esc($m['sender_avatar']) ?>" alt="">
                <?php else: ?>
                  <div class="avatar-placeholder"><?= strtoupper($m['sender_name'][0]) ?></div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <div class="message-bubble">
              <p style="margin:0"><?= nl2br(esc($m['body'])) ?></p>
              <span style="font-size:.7rem;color:var(--muted);"><?= esc($m['created_at']) ?></span>
            </div>
            <?php if ($own): ?>
              <div class="avatar-sm">
                <?php if (!empty($user['avatar'])): ?>
                  <img src="uploads/avatars/<?= esc($user['avatar']) ?>" alt="">
                <?php else: ?>
                  <div class="avatar-placeholder"><?= strtoupper($user['name'][0]) ?></div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <form action="conversation.php?id=<?= (int)$conversationId ?>" method="post" class="conversation-form">
      <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">
      <textarea name="body" rows="2" placeholder="Digite uma mensagem..."></textarea>
      <button class="btn-primary" type="submit">Enviar</button>
    </form>
  </div>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>