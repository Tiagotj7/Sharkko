<?php
// ../models/Post.php
require_once __DIR__ . '/../database/connection.php';

class Post
{
    public static function create(int $userId, array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'INSERT INTO posts (user_id, title, description, image, tags, languages, contact_email, contact_link)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId,
            $data['title'],
            $data['description'],
            $data['image'],
            $data['tags'],
            $data['languages'],
            $data['contact_email'],
            $data['contact_link'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function allWithUser(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query(
            'SELECT p.*, u.name AS user_name, u.avatar AS user_avatar
             FROM posts p
             JOIN users u ON u.id = p.user_id
             ORDER BY p.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public static function findWithUser(int $id)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'SELECT p.*, u.name AS user_name, u.avatar AS user_avatar
             FROM posts p
             JOIN users u ON u.id = p.user_id
             WHERE p.id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findById(int $id)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function byUser(int $userId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function update(int $id, int $userId, array $data): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'UPDATE posts
             SET title = ?, description = ?, image = ?, tags = ?, languages = ?, contact_email = ?, contact_link = ?
             WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['image'],
            $data['tags'],
            $data['languages'],
            $data['contact_email'],
            $data['contact_link'],
            $id,
            $userId
        ]);
    }

    public static function delete(int $id, int $userId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $userId]);
    }
}