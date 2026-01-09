<?php
// app/helpers/utils.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url) {
    header('Location: ' . $url);
    exit;
}

function flash(string $key, ?string $message = null) {
    if ($message === null) {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    } else {
        $_SESSION['flash'][$key] = $message;
    }
}

function old(string $key, ?string $default = null): string {
    if (!empty($_SESSION['old'][$key])) {
        $value = $_SESSION['old'][$key];
        unset($_SESSION['old'][$key]);
        return (string)$value;
    }
    return $default ?? '';
}

function remember_old(array $data) {
    $_SESSION['old'] = $data;
}