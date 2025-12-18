<?php
// app/controllers/MessageController.php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/validation.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/models/Conversation.php';
require_once __DIR__ . '/app/models/Message.php';

class MessageController
{
    public static function index()
    {
        require_login();

        $user = current_user();
        $conversations = Conversation::forUser($user['id']);

        require_once __DIR__ . '/app/views/messages/index.php';
    }

    public static function show()
    {
        require_login();

        $conversationId = (int)($_GET['id'] ?? 0);
        $user = current_user();

        $conversation = Conversation::findForUser($conversationId, $user['id']);
        if (!$conversation) {
            flash('error', 'Conversa não encontrada.');
            redirect('messages.php');
        }

        $other = Conversation::otherParticipant($conversationId, $user['id']);
        $messages = Message::forConversation($conversationId);

        require_once __DIR__ . '/app/views/messages/conversation.php';
    }

    public static function send()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('messages.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('messages.php');
        }

        $conversationId = (int)($_POST['conversation_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');
        $user = current_user();

        if (($error = validate_required('Mensagem', $body))) {
            flash('error', $error);
            redirect('conversation.php?id=' . $conversationId);
        }

        $conversation = Conversation::findForUser($conversationId, $user['id']);
        if (!$conversation) {
            flash('error', 'Conversa não encontrada.');
            redirect('messages.php');
        }

        Message::create($conversationId, $user['id'], $body);

        redirect('conversation.php?id=' . $conversationId);
    }

    public static function start()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('messages.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('messages.php');
        }

        $otherId = (int)($_POST['user_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');
        $user = current_user();

        if ($otherId == $user['id']) {
            flash('error', 'Não pode enviar mensagem para si mesmo.');
            redirect('messages.php');
        }

        $other = User::findById($otherId);
        if (!$other) {
            flash('error', 'Usuário não encontrado.');
            redirect('messages.php');
        }

        if (($error = validate_required('Mensagem', $body))) {
            flash('error', $error);
            redirect('profile.php?id=' . $otherId);
        }

        $conversationId = Conversation::findOrCreateBetween($user['id'], $otherId);
        Message::create($conversationId, $user['id'], $body);

        redirect('conversation.php?id=' . $conversationId);
    }
}
