<?php
// app/controllers/PostController.php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/upload.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Favorite.php';

class PostController
{
    public static function index()
    {
        require_login();

        $user = current_user();
        $posts = Post::allWithUser();

require_once $_SERVER['DOCUMENT_ROOT'] . '/views/feed/index.php';
    }

    public static function create()
    {
        require_login();

        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $data = [
                    'title' => trim($_POST['title'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'tags' => trim($_POST['tags'] ?? ''),
                    'languages' => trim($_POST['languages'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_link' => trim($_POST['contact_link'] ?? ''),
                ];

                if (($error = validate_required('Título', $data['title']))) $errors[] = $error;
                if (($error = validate_max_length('Título', $data['title'], 150))) $errors[] = $error;
                if (($error = validate_required('Descrição', $data['description']))) $errors[] = $error;
                if (($error = validate_url($data['contact_link']))) $errors[] = $error;

                $image = null;
                if (!empty($_FILES['image']['name'])) {
                    if (($error = validate_image_upload($_FILES['image']))) {
                        $errors[] = $error;
                    } else {
                        $image = upload_image($_FILES['image'], 'posts');
                    }
                }

                if (empty($errors)) {
                    $user = current_user();
                    $postId = Post::create($user['id'], array_merge($data, ['image' => $image]));
                    flash('success', 'Post criado com sucesso!');
                    redirect('post_show.php?id=' . $postId);
                }
            }
        }

        remember_old($data);

        require_once __DIR__ . '/../views/post/create.php';
    }

    public static function show()
    {
        require_login();

        $id = (int)($_GET['id'] ?? 0);
        $post = Post::findWithUser($id);
        if (!$post) {
            flash('error', 'Post não encontrado.');
            redirect('feed.php');
        }

        $comments = Comment::forPost($id);
        $user = current_user();
        $likesCount = Like::countForPost($id);
        $userLiked = Like::userLiked($id, $user['id']);
        $userFavorited = Favorite::userFavorited($id, $user['id']);

        require_once __DIR__ . '/../views/post/show.php';
    }

    public static function edit()
    {
        require_login();

        $id = (int)($_GET['id'] ?? 0);
        $user = current_user();
        $post = Post::findById($id);
        if (!$post || $post['user_id'] != $user['id']) {
            flash('error', 'Post não encontrado ou sem permissão.');
            redirect('feed.php');
        }

        $errors = [];
        $data = $post;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {
                $data = array_merge($data, [
                    'title' => trim($_POST['title'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'tags' => trim($_POST['tags'] ?? ''),
                    'languages' => trim($_POST['languages'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_link' => trim($_POST['contact_link'] ?? ''),
                ]);

                if (($error = validate_required('Título', $data['title']))) $errors[] = $error;
                if (($error = validate_max_length('Título', $data['title'], 150))) $errors[] = $error;
                if (($error = validate_required('Descrição', $data['description']))) $errors[] = $error;
                if (($error = validate_url($data['contact_link']))) $errors[] = $error;

                $image = $post['image'];
                if (!empty($_FILES['image']['name'])) {
                    if (($error = validate_image_upload($_FILES['image']))) {
                        $errors[] = $error;
                    } else {
                        if ($image) delete_image($image, 'posts');
                        $image = upload_image($_FILES['image'], 'posts');
                    }
                }

                if (empty($errors)) {
                    $data['image'] = $image;
                    if (Post::update($id, $user['id'], $data)) {
                        flash('success', 'Post atualizado com sucesso!');
                        redirect('post_show.php?id=' . $id);
                    } else {
                        $errors[] = 'Erro ao atualizar post.';
                    }
                }
            }
        }

        remember_old($data);

        require_once __DIR__ . '/../views/post/edit.php';
    }

    public static function update()
    {
        // Alias for edit POST
        self::edit();
    }

    public static function delete()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('feed.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('feed.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $user = current_user();
        $post = Post::findById($id);
        if (!$post || $post['user_id'] != $user['id']) {
            flash('error', 'Post não encontrado ou sem permissão.');
            redirect('feed.php');
        }

        if (Post::delete($id, $user['id'])) {
            if ($post['image']) delete_image($post['image'], 'posts');
            flash('success', 'Post deletado com sucesso!');
        } else {
            flash('error', 'Erro ao deletar post.');
        }

        redirect('feed.php');
    }

    public static function like()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('feed.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('feed.php');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $user = current_user();

        Like::toggle($postId, $user['id']);

        redirect($_SERVER['HTTP_REFERER'] ?? 'feed.php');
    }

    public static function comment()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('feed.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('feed.php');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');

        if (($error = validate_required('Comentário', $body))) {
            flash('error', $error);
            redirect('post_show.php?id=' . $postId);
        }

        $user = current_user();
        Comment::create($postId, $user['id'], $body);

        redirect('post_show.php?id=' . $postId);
    }
}
