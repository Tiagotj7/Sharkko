<?php
// app/controllers/ProfileController.php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/validation.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/helpers/upload.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Post.php';
class ProfileController
{
    public static function show()
    {
        require_login();

        $id = (int)($_GET['id'] ?? current_user()['id']);
        $profileUser = User::findById($id);
        if (!$profileUser) {
            flash('error', 'Usuário não encontrado.');
            redirect('feed.php');
        }

        $posts = Post::byUser($id);

        require_once __DIR__ . '/app/views/profile/show.php';
    }

    public static function edit()
    {
        require_login();

        $user = current_user();
        $errors = [];
        $data = $user;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $data = array_merge($data, [
                    'name' => trim($_POST['name'] ?? ''),
                    'bio' => trim($_POST['bio'] ?? ''),
                    'location' => trim($_POST['location'] ?? ''),
                    'github_url' => trim($_POST['github_url'] ?? ''),
                    'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
                    'website_url' => trim($_POST['website_url'] ?? ''),
                ]);

                if (($error = validate_required('Nome', $data['name']))) $errors[] = $error;
                if (($error = validate_min_length('Nome', $data['name'], 2))) $errors[] = $error;
                if (($error = validate_max_length('Nome', $data['name'], 100))) $errors[] = $error;
                if (($error = validate_url($data['github_url']))) $errors[] = $error;
                if (($error = validate_url($data['linkedin_url']))) $errors[] = $error;
                if (($error = validate_url($data['website_url']))) $errors[] = $error;

                $avatar = $user['avatar'];
                if (!empty($_FILES['avatar']['name'])) {
                    if (($error = validate_image_upload($_FILES['avatar']))) {
                        $errors[] = $error;
                    } else {
                        if ($avatar) delete_image($avatar, 'avatars');
                        $avatar = upload_image($_FILES['avatar'], 'avatars');
                    }
                }

                if (empty($errors)) {
                    $data['avatar'] = $avatar;
                    if (User::updateProfile($user['id'], $data)) {
                        flash('success', 'Perfil atualizado com sucesso!');
                        redirect('profile.php?id=' . $user['id']);
                    } else {
                        $errors[] = 'Erro ao atualizar perfil.';
                    }
                }
            }
        }

        remember_old($data);

        require_once __DIR__ . '/app/views/profile/edit.php';
    }
}
