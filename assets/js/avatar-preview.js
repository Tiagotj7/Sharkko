// assets/js/avatar-preview.js

document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('avatarInput');
  const previewWrap = document.getElementById('avatarPreviewWrap');
  const previewImg = document.getElementById('avatarPreviewImg');
  const preview = document.getElementById('avatarPreview');
  const zoom = document.getElementById('avatarZoom');
  const cropScale = document.getElementById('avatarCropScale');
  const cropX = document.getElementById('avatarCropX');
  const cropY = document.getElementById('avatarCropY');

  let dragging = false;
  let startX = 0;
  let startY = 0;
  let imgX = 0;
  let imgY = 0;
  let scale = 1;

  function updateTransform() {
    previewImg.style.transform = `translate(${imgX}px, ${imgY}px) scale(${scale})`;
    cropScale.value = scale;
    // store normalized offsets relative to preview size
    const rect = preview.getBoundingClientRect();
    cropX.value = Math.round((imgX / rect.width) * 100);
    cropY.value = Math.round((imgY / rect.height) * 100);
  }

  zoom.addEventListener('input', function () {
    scale = parseFloat(this.value);
    // adjust position to keep image centered-ish
    updateTransform();
  });

  input.addEventListener('change', function (e) {
    const file = this.files && this.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) return;

    const url = URL.createObjectURL(file);
    previewImg.src = url;

    // reset state
    scale = 1;
    imgX = 0;
    imgY = 0;
    zoom.value = '1';
    updateTransform();

    // wait for image to load to fit it
    previewImg.onload = function () {
      // fit image so it covers the preview area
      const rect = preview.getBoundingClientRect();
      const imgW = previewImg.naturalWidth;
      const imgH = previewImg.naturalHeight;
      const scaleX = rect.width / imgW;
      const scaleY = rect.height / imgH;
      // choose larger to cover area
      const baseScale = Math.max(scaleX, scaleY);
      scale = baseScale;
      zoom.min = baseScale;
      zoom.value = baseScale;
      cropScale.value = scale;

      // center image
      imgX = (rect.width - imgW * scale) / 2;
      imgY = (rect.height - imgH * scale) / 2;
      updateTransform();
    };

    previewWrap.style.display = 'block';
  });

  // dragging
  preview.addEventListener('pointerdown', function (e) {
    e.preventDefault();
    dragging = true;
    startX = e.clientX;
    startY = e.clientY;
    preview.setPointerCapture(e.pointerId);
  });

  preview.addEventListener('pointermove', function (e) {
    if (!dragging) return;
    const dx = e.clientX - startX;
    const dy = e.clientY - startY;
    startX = e.clientX;
    startY = e.clientY;
    imgX += dx;
    imgY += dy;
    updateTransform();
  });

  preview.addEventListener('pointerup', function (e) {
    dragging = false;
    try { preview.releasePointerCapture(e.pointerId); } catch (err) {}
  });

  preview.addEventListener('pointercancel', function () {
    dragging = false;
  });

});
