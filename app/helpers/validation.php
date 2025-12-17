<?php
// app/helpers/validation.php

function validate_required(string $field, $value): ?string
{
    if (empty($value)) {
        return "O campo $field é obrigatório.";
    }
    return null;
}

function validate_email(string $email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail inválido.";
    }
    return null;
}

function validate_min_length(string $field, string $value, int $min): ?string
{
    if (strlen($value) < $min) {
        return "O campo $field deve ter pelo menos $min caracteres.";
    }
    return null;
}

function validate_max_length(string $field, string $value, int $max): ?string
{
    if (strlen($value) > $max) {
        return "O campo $field deve ter no máximo $max caracteres.";
    }
    return null;
}

function validate_url(string $url): ?string
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "URL inválida.";
    }
    return null;
}

function validate_image_upload(array $file): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Erro no upload da imagem.";
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return "Tipo de imagem não permitido. Use JPEG, PNG ou GIF.";
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return "Imagem muito grande. Máximo 5MB.";
    }

    return null;
}
