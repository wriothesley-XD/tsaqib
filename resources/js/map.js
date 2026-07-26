/**
 * map.js — Map utama pannable (drag-to-scroll) + interaktivitas hotspot.
 *
 * Import di resources/js/app.js:
 *   import './map.js';
 *
 * Data komunitas dikirim dari Blade lewat window.KOMUNITAS_DATA
 * (contoh script-nya ada di bagian bawah file ini).
 */

document.addEventListener('DOMContentLoaded', () => {
  const viewport = document.getElementById('map-viewport');
  const inner = document.getElementById('map-inner');

  const screenPopup = document.getElementById('screen-popup');
  const popupIcon = document.getElementById('popup-icon');
  const popupNama = document.getElementById('popup-nama');
  const popupDeskripsi = document.getElementById('popup-deskripsi');
  const popupDaftar = document.getElementById('popup-daftar');
  const popupClose = document.getElementById('popup-close');

  const komunitasData = window.KOMUNITAS_DATA || {};
  let komunitasAktifSaatIni = null;

  const MAP_SIZE = 2406; // ukuran asli map, px

  // ===== Drag-to-pan =====
  let isDragging = false;
  let didDrag = false; // buat bedain "klik" vs "drag" saat mouseup di atas hotspot
  let startX = 0;
  let startY = 0;
  let currentX = 0;
  let currentY = 0;

  function batasiPosisi(x, y) {
    const viewportWidth = viewport.clientWidth;
    const viewportHeight = viewport.clientHeight;

    const minX = viewportWidth - MAP_SIZE; // paling kiri map bisa digeser
    const minY = viewportHeight - MAP_SIZE;

    return {
      x: Math.min(0, Math.max(minX, x)),
      y: Math.min(0, Math.max(minY, y)),
    };
  }

  function terapkanPosisi() {
    inner.style.transform = `translate(${currentX}px, ${currentY}px)`;
  }

  function posisiTengahAwal() {
    // mulai dari tengah map biar user langsung lihat area utama pulau
    const viewportWidth = viewport.clientWidth;
    const viewportHeight = viewport.clientHeight;

    currentX = (viewportWidth - MAP_SIZE) / 2;
    currentY = (viewportHeight - MAP_SIZE) / 2;

    const dibatasi = batasiPosisi(currentX, currentY);
    currentX = dibatasi.x;
    currentY = dibatasi.y;

    terapkanPosisi();
  }

  function mulaiDrag(clientX, clientY) {
    isDragging = true;
    didDrag = false;
    startX = clientX - currentX;
    startY = clientY - currentY;
    viewport.classList.add('is-dragging');
  }

  function prosesDrag(clientX, clientY) {
    if (!isDragging) return;

    const dx = clientX - startX - currentX;
    const dy = clientY - startY - currentY;
    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) didDrag = true;

    const target = batasiPosisi(clientX - startX, clientY - startY);
    currentX = target.x;
    currentY = target.y;
    terapkanPosisi();
  }

  function akhiriDrag() {
    isDragging = false;
    viewport.classList.remove('is-dragging');
  }

  // Mouse events
  viewport.addEventListener('mousedown', (e) => {
    mulaiDrag(e.clientX, e.clientY);
  });
  window.addEventListener('mousemove', (e) => {
    prosesDrag(e.clientX, e.clientY);
  });
  window.addEventListener('mouseup', akhiriDrag);

  // Touch events (mobile)
  viewport.addEventListener('touchstart', (e) => {
    const t = e.touches[0];
    mulaiDrag(t.clientX, t.clientY);
  }, { passive: true });

  viewport.addEventListener('touchmove', (e) => {
    const t = e.touches[0];
    prosesDrag(t.clientX, t.clientY);
  }, { passive: true });

  viewport.addEventListener('touchend', akhiriDrag);

  window.addEventListener('resize', posisiTengahAwal);
  posisiTengahAwal();

  // ===== Popup komunitas =====
  function bukaPopup(slug) {
    const data = komunitasData[slug];
    if (!data || !data.aktif) return;

    komunitasAktifSaatIni = slug;
    popupIcon.src = data.iconUrl;
    popupIcon.alt = data.nama;
    popupNama.textContent = data.nama;
    popupDeskripsi.textContent = data.deskripsi || '';

    screenPopup.classList.remove('hidden');
  }

  function tutupPopup() {
    komunitasAktifSaatIni = null;
    screenPopup.classList.add('hidden');
  }

  document.querySelectorAll('.hotspot').forEach((hotspot) => {
    hotspot.addEventListener('click', () => {
      // kalau ini akhir dari drag (bukan klik murni), jangan buka popup
      if (didDrag) return;

      const slug = hotspot.dataset.komunitas;
      const aktif = hotspot.dataset.aktif === '1';
      if (!aktif) return;

      bukaPopup(slug);
    });
  });

  popupClose.addEventListener('click', tutupPopup);
  screenPopup.addEventListener('click', (e) => {
    if (e.target === screenPopup) tutupPopup();
  });

  popupDaftar.addEventListener('click', () => {
    console.log('lanjut daftar untuk komunitas:', komunitasAktifSaatIni);
    // TODO: tampilkan screen pilih role di sini
  });
});

/*
  Blok <script> yang perlu ditaruh di view utama (sebelum @vite):

  <script>
    window.KOMUNITAS_DATA = @json(
      collect(config('komunitas'))->mapWithKeys(fn ($k, $slug) => [
        $slug => [
          'nama'      => $k['nama'],
          'deskripsi' => $k['deskripsi'],
          'iconUrl'   => asset('images/' . $k['icon']),
          'aktif'     => $k['aktif'],
        ],
      ])
    );
  </script>
*/
