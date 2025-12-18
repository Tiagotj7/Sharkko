<?php
// app/database/connection.php
require_once __DIR__ . '/app/config/config.php';

function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $envPath = ROOT_PATH . '/.env';
        $envExists = file_exists($envPath) ? 'sim' : 'não';
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Erro de conexão com o banco: ' . $e->getMessage() . ' | Host: ' . DB_HOST . ' | DB: ' . DB_NAME . ' | User: ' . DB_USER . ' | .env existe: ' . $envExists . ' | Caminho: ' . $envPath);
        }
    }

    return $pdo;
}