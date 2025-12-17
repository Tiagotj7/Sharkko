<?php
// app/controllers/SettingsController.php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../models/User.php';

class SettingsController
{
    public static function index()
    {
        require_login();

        $user = current_user();
        $errors = [];
        $data = $user;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $theme = $_POST['theme'] ?? 'dark';
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (!in_array($theme, ['light', 'dark'])) {
                    $theme = 'dark';
                }

                $newPasswordHash = null;
                if (!empty($newPassword)) {
                    if (!password_verify($currentPassword, $user['password_hash'])) {
                        $errors[] = 'Senha atual incorreta.';
                    } elseif (($error = validate_min_length('Nova senha', $newPassword, 6))) {
                        $errors[] = $error;
                    } elseif ($newPassword !== $confirmPassword) {
                        $errors[] = 'As senhas não coincidem.';
                    } else {
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    }
                }

                if (empty($errors)) {
                    if (User::updateSettings($user['id'], $theme, $newPasswordHash)) {
                        flash('success', 'Configurações atualizadas com sucesso!');
                        redirect('settings.php');
                    } else {
                        $errors[] = 'Erro ao atualizar configurações.';
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/settings/index.php';
    }
}
