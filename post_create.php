<?php
// ===============================
// DEBUG (remova depois)
// ===============================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===============================
// BASE_PATH + BOOTSTRAP
// ===============================
define('BASE_PATH', __DIR__ . '/app');

require_once BASE_PATH . '/config/bootstrap.php';
require_once BASE_PATH . '/controllers/PostController.php';

// ===============================
// USUÁRIO LOGADO
// ===============================
require_login();
$user = current_user();

// ===============================
// PROCESSA POST
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('post_create.php');
    }

    $title           = trim($_POST['title'] ?? '');
    $description     = trim($_POST['description'] ?? '');
    $tags            = trim($_POST['tags'] ?? '');
    $languages       = trim($_POST['languages'] ?? '');
    $contact_email   = trim($_POST['contact_email'] ?? '');
    $contact_link    = trim($_POST['contact_link'] ?? '');

    remember_old($_POST);

    if ($title === '' || $description === '') {
        flash('error', 'Título e descrição são obrigatórios.');
        redirect('post_create.php');
    }

    // ===============================
    // UPLOAD
    // ===============================
    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            flash('error', 'Formato de imagem inválido.');
            redirect('post_create.php');
        }

        $imageName = uniqid('post_') . '.' . $ext;
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            UPLOAD_POSTS . '/' . $imageName
        );
    }

    // ===============================
    // SALVAR POST
    // ===============================
    $postId = PostController::create([
        'user_id'       => (int) $user['id'],
        'title'         => $title,
        'description'   => $description,
        'image'         => $imageName,
        'tags'          => $tags,
        'languages'     => $languages,
        'contact_email' => $contact_email,
        'contact_link'  => $contact_link,
    ]);

    flash('success', 'Projeto criado com sucesso!');
    redirect('index.php?r=post_show&id=' . $postId);
}

// ===============================
// VIEW (GET)
// ===============================
require BASE_PATH . '/views/post/create.php';
