<?php

require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/validation.php';
require_once BASE_PATH . '/helpers/csrf.php';
require_once BASE_PATH . '/helpers/upload.php';
require_once BASE_PATH . '/helpers/utils.php';

require_once BASE_PATH . '/models/Post.php';
require_once BASE_PATH . '/models/Like.php';
require_once BASE_PATH . '/models/Comment.php';
require_once BASE_PATH . '/models/Favorite.php';

class PostController
{
    /* ==============================
     * FEED
     * ============================== */
    public static function index()
    {
        require_login();

        $user  = current_user();
        $posts = Post::allWithUser();

        require BASE_PATH . '/views/feed/index.php';
    }

    /* ==============================
     * CREATE
     * ============================== */
    public static function create()
    {
        require_login();

        $errors = [];
        $data   = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {

                $data = [
                    'title'         => trim($_POST['title'] ?? ''),
                    'description'   => trim($_POST['description'] ?? ''),
                    'tags'          => trim($_POST['tags'] ?? ''),
                    'languages'     => trim($_POST['languages'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_link'  => trim($_POST['contact_link'] ?? ''),
                ];

                if ($e = validate_required('Título', $data['title'])) $errors[] = $e;
                if ($e = validate_max_length('Título', $data['title'], 150)) $errors[] = $e;
                if ($e = validate_required('Descrição', $data['description'])) $errors[] = $e;
                if ($e = validate_url($data['contact_link'])) $errors[] = $e;

                $image = null;
                if (!empty($_FILES['image']['name'])) {
                    if ($e = validate_image_upload($_FILES['image'])) {
                        $errors[] = $e;
                    } else {
                        $image = upload_image($_FILES['image'], 'posts');
                    }
                }

                if (empty($errors)) {
                    $user   = current_user();
                    $postId = Post::create(
                        $user['id'],
                        array_merge($data, ['image' => $image])
                    );

                    flash('success', 'Post criado com sucesso!');
                    redirect('index.php?r=post_show&id=' . $postId);
                }
            }
        }

        remember_old($data);
        require BASE_PATH . '/views/post/create.php';
    }

    /* ==============================
     * SHOW
     * ============================== */
    public static function show()
    {
        require_login();

        $id   = (int)($_GET['id'] ?? 0);
        $post = Post::findWithUser($id);

        if (!$post) {
            flash('error', 'Post não encontrado.');
            redirect('index.php?r=feed');
        }

        $user           = current_user();
        $comments       = Comment::forPost($id);
        $likesCount     = Like::countForPost($id);
        $userLiked      = Like::userLiked($id, $user['id']);
        $userFavorited  = Favorite::userFavorited($id, $user['id']);

        require BASE_PATH . '/views/post/show.php';
    }

    /* ==============================
     * EDIT / UPDATE
     * ============================== */
    public static function edit()
    {
        require_login();

        $id   = (int)($_GET['id'] ?? 0);
        $user = current_user();
        $post = Post::findById($id);

        if (!$post || $post['user_id'] != $user['id']) {
            flash('error', 'Post não encontrado ou sem permissão.');
            redirect('index.php?r=feed');
        }

        $errors = [];
        $data   = $post;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Token CSRF inválido.';
            } else {

                $data = array_merge($data, [
                    'title'         => trim($_POST['title'] ?? ''),
                    'description'   => trim($_POST['description'] ?? ''),
                    'tags'          => trim($_POST['tags'] ?? ''),
                    'languages'     => trim($_POST['languages'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_link'  => trim($_POST['contact_link'] ?? ''),
                ]);

                if ($e = validate_required('Título', $data['title'])) $errors[] = $e;
                if ($e = validate_max_length('Título', $data['title'], 150)) $errors[] = $e;
                if ($e = validate_required('Descrição', $data['description'])) $errors[] = $e;
                if ($e = validate_url($data['contact_link'])) $errors[] = $e;

                $image = $post['image'];

                if (!empty($_FILES['image']['name'])) {
                    if ($e = validate_image_upload($_FILES['image'])) {
                        $errors[] = $e;
                    } else {
                        if ($image) delete_image($image, 'posts');
                        $image = upload_image($_FILES['image'], 'posts');
                    }
                }

                if (empty($errors)) {
                    $data['image'] = $image;

                    Post::update($id, $user['id'], $data);
                    flash('success', 'Post atualizado com sucesso!');
                    redirect('index.php?r=post_show&id=' . $id);
                }
            }
        }

        remember_old($data);
        require BASE_PATH . '/views/post/edit.php';
    }

    /* ==============================
     * DELETE
     * ============================== */
    public static function delete()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?r=feed');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('index.php?r=feed');
        }

        $id   = (int)($_POST['id'] ?? 0);
        $user = current_user();
        $post = Post::findById($id);

        if (!$post || $post['user_id'] != $user['id']) {
            flash('error', 'Sem permissão.');
            redirect('index.php?r=feed');
        }

        Post::delete($id, $user['id']);

        if ($post['image']) {
            delete_image($post['image'], 'posts');
        }

        flash('success', 'Post removido.');
        redirect('index.php?r=feed');
    }

    /* ==============================
     * LIKE
     * ============================== */
    public static function like()
    {
        require_login();

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('index.php?r=feed');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $user   = current_user();

        Like::toggle($postId, $user['id']);

        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?r=feed');
    }

    /* ==============================
     * COMMENT
     * ============================== */
    public static function comment()
    {
        require_login();

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('index.php?r=feed');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $body   = trim($_POST['body'] ?? '');

        if ($e = validate_required('Comentário', $body)) {
            flash('error', $e);
            redirect('index.php?r=post_show&id=' . $postId);
        }

        $user = current_user();
        Comment::create($postId, $user['id'], $body);

        redirect('index.php?r=post_show&id=' . $postId);
    }
}
