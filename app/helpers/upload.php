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
        // Validate that file is a valid image
        if (!validate_image($path)) {
            @unlink($path);
            return null;
        }
        return $filename;
    }

    return null;
}

function validate_image(string $path): bool
{
    // Check if file can be read as image
    if (!function_exists('getimagesize')) {
        return true; // Can't validate without getimagesize
    }
    
    $imageInfo = @getimagesize($path);
    if ($imageInfo === false) {
        return false; // Not a valid image
    }
    
    // Ensure dimensions are reasonable
    if ($imageInfo[0] < 50 || $imageInfo[1] < 50) {
        return false; // Too small
    }
    
    return true;
}

function delete_image(string $filename, string $subdir)
{
    $uploadDir = constant('UPLOAD_' . strtoupper($subdir));
    $path = $uploadDir . '/' . $filename;
    if (file_exists($path)) {
        unlink($path);
    }
}
