<?php
// app/controllers/ProfileController.php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';

class ProfileController
{
    public static function show(): void
    {
        require_login();

        $profileId = (int)($_GET['id'] ?? 0);

        if ($profileId <= 0) {
            flash('error', 'Usuário inválido.');
            redirect('index.php');
        }

        // 🔹 usuário logado
        $user = current_user();

        // 🔹 usuário do perfil
        $profileUser = User::findById($profileId);

        if (!$profileUser) {
            flash('error', 'Usuário não encontrado.');
            redirect('index.php');
        }

        // 🔹 posts do usuário
        $posts = Post::byUser($profileId);

        // 🔹 carrega a view COM as variáveis
        require __DIR__ . '/../views/profile/show.php';
    }
}
