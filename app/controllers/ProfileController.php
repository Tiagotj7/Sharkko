<?php
// app/controllers/ProfileController.php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../helpers/upload.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';

class ProfileController
{
    // 🔹 VER PERFIL
    public static function show(): void
    {
        require_login();

        $profileId = (int)($_GET['id'] ?? 0);

        if ($profileId <= 0) {
            flash('error', 'Usuário inválido.');
            redirect('index.php');
        }

        $user = current_user();
        $profileUser = User::findById($profileId);

        if (!$profileUser) {
            flash('error', 'Usuário não encontrado.');
            redirect('index.php');
        }

        $posts = Post::byUser($profileId);

        require __DIR__ . '/../views/profile/show.php';
    }

    // 🔹 EDITAR PERFIL
    public static function edit(): void
    {
        require_login();

        $user = current_user();
        $errors = [];
        $data = $user;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $data['name'] = trim($_POST['name'] ?? '');
                $data['bio'] = trim($_POST['bio'] ?? '');
                $data['location'] = trim($_POST['location'] ?? '');
                $data['github_url'] = trim($_POST['github_url'] ?? '');
                $data['linkedin_url'] = trim($_POST['linkedin_url'] ?? '');
                $data['website_url'] = trim($_POST['website_url'] ?? '');

                // avatar - if new file uploaded, update it
                if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['size'] > 0) {
                    if ($user['avatar']) {
                        delete_image($user['avatar'], 'avatars');
                    }
                    $uploadedFile = upload_image($_FILES['avatar'], 'avatars');
                    if ($uploadedFile) {
                        $data['avatar'] = $uploadedFile;
                    } else {
                        $errors[] = 'Erro ao fazer upload do avatar.';
                    }
                } else {
                    // Keep the old avatar if no new file was uploaded
                    $data['avatar'] = $user['avatar'];
                }

                if (empty($data['name'])) {
                    $errors[] = 'Nome é obrigatório.';
                }

                if (empty($errors)) {
                    User::update($user['id'], $data);
                    flash('success', 'Perfil atualizado com sucesso!');
                    redirect('profile.php?id=' . $user['id']);
                }
            }
        }

        require __DIR__ . '/../views/profile/edit.php';
    }
}
