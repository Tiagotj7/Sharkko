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
  let minScale = 1;

  function updateTransform() {
    previewImg.style.transform = `translate(${imgX}px, ${imgY}px) scale(${scale})`;
    cropScale.value = Number(scale.toFixed(2));
    cropX.value = Number(imgX.toFixed(0));
    cropY.value = Number(imgY.toFixed(0));
  }

  zoom.addEventListener('input', function () {
    const newScale = Math.max(minScale, Math.min(3, parseFloat(this.value)));
    scale = newScale;
    this.value = newScale;
    updateTransform();
  });

  input.addEventListener('change', function (e) {
    const file = this.files && this.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) {
      alert('Por favor, selecione uma imagem válida.');
      return;
    }

    const url = URL.createObjectURL(file);
    previewImg.src = url;

    // wait for image to load to fit it
    previewImg.onload = function () {
      const rect = preview.getBoundingClientRect();
      const imgW = previewImg.naturalWidth;
      const imgH = previewImg.naturalHeight;
      
      // Calculate base scale (fit image to circle)
      const scaleX = rect.width / imgW;
      const scaleY = rect.height / imgH;
      minScale = Math.max(scaleX, scaleY);
      
      // Set zoom limits and initial value
      zoom.min = minScale.toFixed(2);
      zoom.max = '3';
      zoom.step = '0.1';
      
      scale = minScale;
      zoom.value = minScale.toFixed(2);
      
      // Center image initially
      imgX = (rect.width - imgW * scale) / 2;
      imgY = (rect.height - imgH * scale) / 2;
      updateTransform();
    };

    previewWrap.style.display = 'block';
  });

  // dragging - only when zoomed in
  preview.addEventListener('pointerdown', function (e) {
    if (scale <= minScale + 0.01) return; // no drag at base scale
    dragging = true;
    startX = e.clientX;
    startY = e.clientY;
    preview.style.cursor = 'grabbing';
    try { preview.setPointerCapture(e.pointerId); } catch (err) {}
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
    preview.style.cursor = 'grab';
    try { preview.releasePointerCapture(e.pointerId); } catch (err) {}
  });

  preview.addEventListener('pointercancel', function () {
    dragging = false;
    preview.style.cursor = 'grab';
  });

});
