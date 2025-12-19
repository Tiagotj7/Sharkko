<?php
// ../models/Message.php
require_once __DIR__ . '/../database/connection.php';

class Message
{
    public static function create(int $conversationId, int $senderId, string $body)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('
            INSERT INTO messages (conversation_id, sender_id, body)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$conversationId, $senderId, $body]);
    }

    public static function forConversation(int $conversationId): array
    {
        $pdo = getPDO();
        $sql = '
            SELECT m.*, u.name AS sender_name, u.avatar AS sender_avatar
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at ASC
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$conversationId]);
        return $stmt->fetchAll();
    }
}