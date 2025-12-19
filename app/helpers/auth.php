<?php
// app/helpers/auth.php

function login_user(int $userId): void
{
    $_SESSION['user_id'] = $userId;
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
}

function current_user()
{
    if (!empty($_SESSION['user_id'])) {
        return User::findById((int) $_SESSION['user_id']);
    }
    return null;
}

function require_login(): void
{
    if (!current_user()) {
        flash('error', 'Você precisa estar logado.');
        redirect('index.php?r=login');
        exit;
    }
}
