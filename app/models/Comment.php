<?php
// app/models/Comment.php
require_once __DIR__ . '/../database/connection.php';

class Comment
{
    public static function create(int $postId, int $userId, string $body)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'INSERT INTO post_comments (post_id, user_id, body) VALUES (?, ?, ?)'
        );
        $stmt->execute([$postId, $userId, $body]);
    }

    public static function forPost(int $postId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'SELECT c.*, u.name AS user_name, u.avatar AS user_avatar
             FROM post_comments c
             JOIN users u ON u.id = c.user_id
             WHERE c.post_id = ?
             ORDER BY c.created_at ASC'
        );
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
}