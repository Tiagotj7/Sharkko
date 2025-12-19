<?php
// ../helpers/upload.php
require_once __DIR__ . '/../config/config.php';

function upload_image(array $file, string $subdir): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = constant('UPLOAD_' . strtoupper($subdir));
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $path = $uploadDir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        return $filename;
    }

    return null;
}

function delete_image(string $filename, string $subdir)
{
    $uploadDir = constant('UPLOAD_' . strtoupper($subdir));
    $path = $uploadDir . '/' . $filename;
    if (file_exists($path)) {
        unlink($path);
    }
}
