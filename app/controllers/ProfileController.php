<?php

require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Post.php';

class ProfileController
{
    public static function show()
    {
        require_login();

        $profileId = (int)($_GET['id'] ?? 0);

        $profile = User::findById($profileId);

        if (!$profile) {
            flash('error', 'Usuário não encontrado.');
            redirect('index.php');
        }

        $posts   = Post::byUser($profileId);
        $current = current_user();

        require BASE_PATH . '/views/profile/show.php';
    }

    public static function edit()
    {
        require_login();

        $user = current_user();

        require BASE_PATH . '/views/profile/edit.php';
    }
}
