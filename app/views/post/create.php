<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include BASE_PATH . '/views/partials/head.php'; ?>
</head>
<body>

<?php include BASE_PATH . '/views/partials/header.php'; ?>

<main class="container main">
    <?php include BASE_PATH . '/views/partials/flash.php'; ?>

    <h1>Novo projeto</h1>

    <form method="post" enctype="multipart/form-data" class="card">
        <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

        <label>Título
            <input type="text" name="title" required>
        </label>

        <label>Descrição
            <textarea name="description" rows="6" required></textarea>
        </label>

        <button class="btn-primary">Publicar</button>
    </form>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>

</body>
</html>
