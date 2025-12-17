<?php
// app/helpers/auth.php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function login_user(int $userId) {
    $_SESSION['user_id'] = $userId;
}

function logout_user() {
    unset($_SESSION['user_id']);
}

function current_user() {
    if (!empty($_SESSION['user_id'])) {
        return User::findById((int)$_SESSION['user_id']);
    }
    return null;
}

function require_login() {
    if (!current_user()) {
        flash('error', 'Você precisa estar logado.');
        redirect('login.php');
    }
}