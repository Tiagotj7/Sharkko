<?php
// app/controllers/AuthController.php

define('BASE_PATH', __DIR__ . '/app');

require_once BASE_PATH . '/config/config.php';

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../models/User.php';
class AuthController
{
    public static function login()
    {
        $user = current_user();
        if ($user) {
            redirect('feed.php');
        }

        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (($error = validate_required('E-mail', $email))) $errors[] = $error;
                if (($error = validate_email($email))) $errors[] = $error;
                if (($error = validate_required('Senha', $password))) $errors[] = $error;

                if (empty($errors)) {
                    $user = User::findByEmail($email);
                    if ($user && password_verify($password, $user['password_hash'])) {
                        login_user($user['id']);
                        redirect('feed.php');
                    } else {
                        $errors[] = 'E-mail ou senha incorretos.';
                    }
                }
            }
        }

        // Exibe o formulário
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public static function register()
    {
        $user = current_user();
        if ($user) {
            redirect('feed.php');
        }

        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'password' => $_POST['password'] ?? '',
                    'password_confirm' => $_POST['password_confirm'] ?? '',
                ];

                if (($error = validate_required('Nome', $data['name']))) $errors[] = $error;
                if (($error = validate_min_length('Nome', $data['name'], 2))) $errors[] = $error;
                if (($error = validate_max_length('Nome', $data['name'], 100))) $errors[] = $error;
                if (($error = validate_required('E-mail', $data['email']))) $errors[] = $error;
                if (($error = validate_email($data['email']))) $errors[] = $error;
                if (($error = validate_required('Senha', $data['password']))) $errors[] = $error;
                if (($error = validate_min_length('Senha', $data['password'], 6))) $errors[] = $error;
                if ($data['password'] !== $data['password_confirm']) $errors[] = 'As senhas não coincidem.';

                if (empty($errors)) {
                    $userId = User::create($data['name'], $data['email'], $data['password']);
                    if ($userId) {
                        login_user($userId);
                        flash('success', 'Conta criada com sucesso!');
                        redirect('feed.php');
                    } else {
                        $errors[] = 'E-mail já cadastrado.';
                    }
                }
            }
        }

        remember_old($data);

        // Exibe o formulário
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public static function logout()
    {
        logout_user();
        flash('success', 'Logout realizado.');
        redirect('index.php');
    }
}
