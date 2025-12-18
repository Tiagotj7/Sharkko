<?php
// app/models/Like.php
require_once __DIR__ . '/app/database/connection.php';

class Like
{
    public static function toggle(int $postId, int $userId)
    {
        $pdo = getPDO();
        // verifica se já curtiu
        $stmt = $pdo->prepare(
            'SELECT id FROM post_likes WHERE post_id = ? AND user_id = ? LIMIT 1'
        );
        $stmt->execute([$postId, $userId]);
        $like = $stmt->fetch();

        if ($like) {
            $stmt = $pdo->prepare('DELETE FROM post_likes WHERE id = ?');
            $stmt->execute([$like['id']]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)'
            );
            $stmt->execute([$postId, $userId]);
        }
    }

    public static function countForPost(int $postId): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS total FROM post_likes WHERE post_id = ?'
        );
        $stmt->execute([$postId]);
        $row = $stmt->fetch();
        return (int)$row['total'];
    }

    public static function userLiked(int $postId, int $userId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'SELECT id FROM post_likes WHERE post_id = ? AND user_id = ? LIMIT 1'
        );
        $stmt->execute([$postId, $userId]);
        return (bool)$stmt->fetch();
    }
}