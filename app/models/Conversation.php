<?php
// app/models/Conversation.php
require_once __DIR__ . '/../database/connection.php';

class Conversation
{
    public static function findOrCreateBetween(int $userId1, int $userId2): int
    {
        $pdo = getPDO();

        // Sempre ordena os IDs para evitar duplicidade (1,2) / (2,1)
        if ($userId2 < $userId1) {
            [$userId1, $userId2] = [$userId2, $userId1];
        }

        // Tenta achar conversa já existente entre os dois
        $sql = '
            SELECT c.id
            FROM conversations c
            JOIN conversation_user cu1 ON cu1.conversation_id = c.id AND cu1.user_id = ?
            JOIN conversation_user cu2 ON cu2.conversation_id = c.id AND cu2.user_id = ?
            LIMIT 1
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId1, $userId2]);
        $conv = $stmt->fetch();

        if ($conv) {
            return (int)$conv['id'];
        }

        // Cria nova conversa
        $pdo->beginTransaction();
        try {
            $pdo->exec('INSERT INTO conversations () VALUES ()');
            $convId = (int)$pdo->lastInsertId();

            $ins = $pdo->prepare('INSERT INTO conversation_user (conversation_id, user_id) VALUES (?, ?)');
            $ins->execute([$convId, $userId1]);
            $ins->execute([$convId, $userId2]);

            $pdo->commit();
            return $convId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function forUser(int $userId): array
    {
        $pdo = getPDO();
        $sql = '
            SELECT DISTINCT c.id,
                   u.id   AS other_id,
                   u.name AS other_name,
                   u.avatar AS other_avatar
            FROM conversations c
            JOIN conversation_user cu_self ON cu_self.conversation_id = c.id AND cu_self.user_id = ?
            JOIN conversation_user cu_other ON cu_other.conversation_id = c.id AND cu_other.user_id != ?
            JOIN users u ON u.id = cu_other.user_id
            ORDER BY c.created_at DESC
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }

    public static function findForUser(int $conversationId, int $userId)
    {
        $pdo = getPDO();
        $sql = '
            SELECT c.*
            FROM conversations c
            JOIN conversation_user cu ON cu.conversation_id = c.id
            WHERE c.id = ? AND cu.user_id = ?
            LIMIT 1
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$conversationId, $userId]);
        return $stmt->fetch();
    }

    public static function otherParticipant(int $conversationId, int $userId)
    {
        $pdo = getPDO();
        $sql = '
            SELECT u.*
            FROM conversation_user cu
            JOIN users u ON u.id = cu.user_id
            WHERE cu.conversation_id = ? AND cu.user_id != ?
            LIMIT 1
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$conversationId, $userId]);
        return $stmt->fetch();
    }
}