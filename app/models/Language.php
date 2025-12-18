<?php
// app/models/Language.php
require_once __DIR__ . '/app/database/connection.php';

class Language
{
    public static function all(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT languages FROM posts WHERE languages IS NOT NULL AND languages <> ""');

        $langs = [];
        while ($row = $stmt->fetch()) {
            foreach (explode(',', $row['languages']) as $l) {
                $l = strtolower(trim($l));
                if ($l !== '') {
                    $langs[$l] = $l;
                }
            }
        }
        $list = array_values($langs);
        sort($list);
        return $list;
    }
}