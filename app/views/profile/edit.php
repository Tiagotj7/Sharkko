<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <h1>Editar perfil</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="profile_edit.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

    <label>
      Nome
      <input type="text" name="name" value="<?= esc(old('name', $data['name'])) ?>" required>
    </label>

    <label>
      Bio
      <textarea name="bio" rows="3"><?= esc(old('bio', $data['bio'])) ?></textarea>
    </label>

    <label>
      Localização
      <input type="text" name="location" value="<?= esc(old('location', $data['location'])) ?>">
    </label>

    <div class="avatar-upload-section">
      <label class="avatar-label">Foto de perfil</label>
      
      <div class="avatar-container">
        <!-- Current avatar display -->
        <?php if (!empty($data['avatar'])): ?>
          <div class="avatar-current">
            <img src="uploads/avatars/<?= esc($data['avatar']) ?>" alt="Avatar atual" class="avatar-current-img">
            <div class="avatar-overlay">Alterar</div>
          </div>
        <?php else: ?>
          <div class="avatar-placeholder-box">📷</div>
        <?php endif; ?>

        <!-- Hidden file input -->
        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="avatar-file-input">
      </div>

      <!-- Preview and crop interface -->
      <div id="avatarPreviewWrap" class="avatar-preview-wrap" style="display:none;">
        <div class="avatar-preview-header">
          <h3>Ajuste sua foto</h3>
          <p class="avatar-preview-subtext">Arraste para reposicionar • Use o zoom para ajustar</p>
        </div>
        
        <div class="avatar-preview-container">
          <div class="avatar-preview" id="avatarPreview">
            <img id="avatarPreviewImg" src="" alt="Preview">
          </div>
        </div>

        <!-- Zoom control -->
        <div class="zoom-control">
          <span class="zoom-label">🔍</span>
          <input id="avatarZoom" type="range" min="1" max="3" step="0.1" value="1" class="zoom-slider">
          <span class="zoom-value" id="zoomValue">100%</span>
        </div>

        <!-- Validation message -->
        <div id="avatarValidationMsg" class="avatar-validation-msg" style="display:none;"></div>

        <!-- Hidden inputs -->
        <input type="hidden" name="avatar_crop_scale" id="avatarCropScale" value="1">
        <input type="hidden" name="avatar_crop_x" id="avatarCropX" value="0">
        <input type="hidden" name="avatar_crop_y" id="avatarCropY" value="0">

        <div class="avatar-preview-actions">
          <button type="button" id="avatarCancelBtn" class="btn-secondary">Cancelar</button>
          <button type="button" id="avatarConfirmBtn" class="btn-primary">Confirmar</button>
        </div>
      </div>

      <!-- Info text -->
      <p class="avatar-info">JPG, PNG ou GIF • Mín. 400x400px • Máx. 5MB</p>
    </div>

    <label>
      GitHub URL
      <input type="url" name="github_url" value="<?= esc(old('github_url', $data['github_url'])) ?>">
    </label>

    <label>
      LinkedIn URL
      <input type="url" name="linkedin_url" value="<?= esc(old('linkedin_url', $data['linkedin_url'])) ?>">
    </label>

    <label>
      Website URL
      <input type="url" name="website_url" value="<?= esc(old('website_url', $data['website_url'])) ?>">
    </label>

    <button type="submit" class="btn-primary">Salvar</button>
  </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
<script src="assets/js/avatar-preview.js"></script>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
