<?php
// app/models/Favorite.php
require_once __DIR__ . '/../database/connection.php';

class Favorite
{
    public static function toggle(int $postId, int $userId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id FROM favorites WHERE post_id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$postId, $userId]);
        $fav = $stmt->fetch();

        if ($fav) {
            $stmt = $pdo->prepare('DELETE FROM favorites WHERE id = ?');
            $stmt->execute([$fav['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO favorites (post_id, user_id) VALUES (?, ?)');
            $stmt->execute([$postId, $userId]);
        }
    }

    public static function userFavorited(int $postId, int $userId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id FROM favorites WHERE post_id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$postId, $userId]);
        return (bool)$stmt->fetch();
    }

    public static function forUser(int $userId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('
            SELECT f.*, p.*, u.name AS user_name, u.avatar AS user_avatar
            FROM favorites f
            JOIN posts p ON p.id = f.post_id
            JOIN users u ON u.id = p.user_id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}