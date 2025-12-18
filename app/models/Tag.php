<?php
// app/models/Tag.php
require_once __DIR__ . '/app/database/connection.php';

class Tag
{
    public static function all(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT tags FROM posts WHERE tags IS NOT NULL AND tags <> ""');

        $tags = [];
        while ($row = $stmt->fetch()) {
            foreach (explode(',', $row['tags']) as $t) {
                $t = strtolower(trim($t));
                if ($t !== '') {
                    $tags[$t] = $t;
                }
            }
        }
        $list = array_values($tags);
        sort($list);
        return $list;
    }
}