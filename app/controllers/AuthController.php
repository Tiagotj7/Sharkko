<?php
// app/controllers/AuthController.php

require_once BASE_PATH . '/models/User.php';

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

                if ($error = validate_required('E-mail', $email)) $errors[] = $error;
                if ($error = validate_email($email)) $errors[] = $error;
                if ($error = validate_required('Senha', $password)) $errors[] = $error;

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

        require BASE_PATH . '/views/auth/login.php';
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

                if ($e = validate_required('Nome', $data['name'])) $errors[] = $e;
                if ($e = validate_min_length('Nome', $data['name'], 2)) $errors[] = $e;
                if ($e = validate_max_length('Nome', $data['name'], 100)) $errors[] = $e;
                if ($e = validate_required('E-mail', $data['email'])) $errors[] = $e;
                if ($e = validate_email($data['email'])) $errors[] = $e;
                if ($e = validate_required('Senha', $data['password'])) $errors[] = $e;
                if ($e = validate_min_length('Senha', $data['password'], 6)) $errors[] = $e;

                if ($data['password'] !== $data['password_confirm']) {
                    $errors[] = 'As senhas não coincidem.';
                }

                if (empty($errors)) {
                    $userId = User::create(
                        $data['name'],
                        $data['email'],
                        $data['password']
                    );

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

        require BASE_PATH . '/views/auth/register.php';
    }

    public static function logout()
    {
        logout_user();
        flash('success', 'Logout realizado.');
        redirect('index.php');
    }
}
