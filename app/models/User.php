<?php
// app/models/User.php
require_once __DIR__ . '/../database/connection.php';

class User
{
    public static function create(string $name, string $email, string $password): ?int
    {
        $pdo = getPDO();

        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return null; // já existe
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)'
        );
        $stmt->execute([$name, $email, $hash]);

        return (int)$pdo->lastInsertId();
    }

    public static function findByEmail(string $email)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findById(int $id)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateProfile(int $id, array $data)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'UPDATE users SET name = ?, bio = ?, location = ?, github_url = ?, linkedin_url = ?, website_url = ?, avatar = ?, theme = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['name'],
            $data['bio'],
            $data['location'],
            $data['github_url'],
            $data['linkedin_url'],
            $data['website_url'],
            $data['avatar'],
            $data['theme'],
            $id
        ]);
    }
}