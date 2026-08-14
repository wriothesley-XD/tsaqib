{{-- resources/views/tsaqib/labor-pai.blade.php --}}
@php($pageTitle = 'Laboratorium PAI - FSI SMAN 1 Bukittinggi')

@push('styles')
    /* ===== Laboratorium PAI: aksen kartu + carousel horizontal (mobile) ===== */

    /* 1) Aksen border kiri selang-seling hijau/emas — mobile only */
    @media (max-width: 1023px){
        .lp-cards > .tsaqib-card:nth-child(odd)  { border-left: 3px solid var(--green); }
        .lp-cards > .tsaqib-card:nth-child(even) { border-left: 3px solid var(--gold); }
    }

    /* 2) Carousel infinite-loop (CSS keyframes) + drag Pointer Events — mobile only.
          Track = 2 set kartu (original + clone via JS). translateX 0 → -50% loop
          seamless karena set ke-2 identik. Desktop (≥1024px) → grid 3-kolom. */
    .lp-carousel{
        position:relative;
        overflow:hidden;
        padding:.5rem 0;
        -webkit-mask-image:linear-gradient(to right, transparent, #000 5%, #000 95%, transparent);
        mask-image:linear-gradient(to right, transparent, #000 5%, #000 95%, transparent);
    }
    .lp-track{
        position:relative;
        display:flex;
        width:max-content;
        --lp-duration:40s;
        animation:lp-loop var(--lp-duration) linear infinite;
        touch-action:pan-y;        /* horizontal → drag kita; vertikal → scroll halaman */
        user-select:none;
        cursor:grab;
    }
    .lp-track.is-dragging{ cursor:grabbing; animation-play-state:paused; }
    .lp-track > .lp-card{
        flex:0 0 auto;
        width:min(80vw, 300px);
        margin-right:1rem;         /* margin (bukan flex gap) supaya -50% = persis 1 set → seamless */
    }
    @keyframes lp-loop{
        from{ transform:translateX(0); }
        to  { transform:translateX(-50%); }
    }

    /* 4) Redesign kartu — badge solid pojok kiri, ikon deco faint pojok kanan,
          judul tebal, body pendek dgn keyword bold. */
    .lp-card{
        position:relative; overflow:hidden; padding:1.75rem;
        background:linear-gradient(160deg, rgba(247,245,239,.06), rgba(247,245,239,.02));
    }
    .lp-card-deco{
        position:absolute; top:-.5rem; right:-.4rem;
        font-size:5.5rem; line-height:1; color:var(--cream);
        opacity:.05; pointer-events:none;
    }
    .lp-badge{
        display:flex; align-items:center; justify-content:center;
        width:2.75rem; height:2.75rem; border-radius:.85rem;
        font-size:1.05rem; color:var(--cream); margin-bottom:1rem;
        box-shadow:0 6px 18px -6px rgba(0,0,0,.55);
    }
    .lp-badge-green{ background:linear-gradient(140deg, var(--green), var(--green-dark)); }
    .lp-badge-gold{ background:linear-gradient(140deg, var(--gold), #a9893f); color:var(--ink); }
    .lp-card-title{
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
        font-size:1.05rem; line-height:1.3; color:var(--cream); margin-bottom:.5rem;
    }
    .lp-card-body{ font-size:.8rem; line-height:1.6; color:rgba(247,245,239,.62); }
    .lp-card-body strong{ color:var(--cream); font-weight:700; }
    .lp-checklist{ margin-top:.85rem; display:flex; flex-direction:column; gap:.5rem; }
    .lp-checklist li{ display:flex; align-items:flex-start; gap:.5rem; font-size:.78rem; color:rgba(247,245,239,.62); }
    .lp-checklist i{ color:#3fd6b0; font-size:.85rem; margin-top:.1rem; }

    /* Desktop: grid 3-kolom; animasi & clone dimatikan */
    @media (min-width:1024px){
        .lp-carousel{ overflow:visible; -webkit-mask-image:none; mask-image:none; padding:0; }
        .lp-track{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:1.5rem;
            width:auto;
            animation:none;
            transform:none !important;   /* hapus inline transform sisa drag */
            touch-action:auto;
            cursor:default;
        }
        .lp-track > .lp-card{ width:auto; margin-right:0; }
        .lp-track > .lp-card.is-clone{ display:none; }
    }

    /* ===== Section Profil TSAQIB (flipbook embed) =====
       Header dgn ikon emas bulat (float + ring pulse, 2.4s ease-in-out infinite),
       diikuti iframe flipbook Heyzine. URL iframe di-set admin via tabel settings. */
    .lp-profil{ padding:2rem; }
    .lp-profil-head{
        display:flex; align-items:center; gap:1.5rem; margin-bottom:1.5rem;
    }
    .lp-profil-icon{
        position:relative; z-index:1;
        flex:0 0 auto;
        display:flex; align-items:center; justify-content:center;
        width:4.5rem; height:4.5rem; border-radius:999px;
        background:linear-gradient(140deg, var(--gold), #a9893f);
        color:var(--ink); font-size:1.6rem;
        box-shadow:0 8px 22px -8px rgba(201,166,107,.7);
        animation:lp-float 2.4s ease-in-out infinite;
    }
    /* ring pulse — mengembang & memudar, durasi sama dgn float agar ritmenya selaras */
    .lp-profil-icon::before{
        content:''; position:absolute; inset:-6px; border-radius:inherit;
        background:rgba(201,166,107,.45);
        animation:lp-ring 2.4s ease-in-out infinite;
        z-index:-1;
    }
    .lp-profil-head:hover .lp-profil-icon{ transform:scale(1.08); }
    .lp-profil-icon{ transition:transform .25s ease; }

    /* wrapper iframe — responsive + rounded; override tinggi tetap mobile → desktop */
    .lp-flipbook-wrap{
        position:relative; width:100%;
        border-radius:.85rem; overflow:hidden;
        background:#fff;
        box-shadow:0 12px 30px -12px rgba(0,0,0,.6);
    }
    .lp-flipbook-wrap iframe{
        display:block; width:100%;
        height:clamp(380px, 56vw, 520px);   /* menimpa inline height via !important saat responsif */
    }

    /* ===== Fake iframe: thumbnail statis klik-untuk-muat (web perf) =====
       Lazy-facade: alih-alih memuat iframe Heyzine saat halaman dibuka (berat),
       tampilkan thumbnail 400px + overlay gelap + ikon Book. Iframe asli baru
       di-inject (Alpine) saat thumbnail diklik. Tata letak tetap di bawah
       .lp-flipbook-wrap agar hierarki CSS tidak berubah. */
    .lp-flipbook-facade{
        position:relative; width:100%; height:400px;
        cursor:pointer; overflow:hidden;
        background:
            radial-gradient(circle at 30% 20%, rgba(1,121,95,.35), transparent 60%),
            linear-gradient(135deg, #0a4a3a 0%, #10140f 100%);
        display:flex; align-items:center; justify-content:center;
    }
    .lp-flipbook-facade::after{
        /* tekstur halaman tipis biar nggak terlihat sebagai flat hitam */
        content:''; position:absolute; inset:0;
        background-image:repeating-linear-gradient(90deg, rgba(247,245,239,.04) 0 2px, transparent 2px 44px);
        pointer-events:none;
    }
    .lp-flipbook-facade:hover .lp-facade-play{ transform:scale(1.08); background:var(--gold); color:var(--ink); }
    .lp-facade-play{
        position:relative; z-index:2;
        width:5.5rem; height:5.5rem; border-radius:999px;
        display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.15rem;
        background:rgba(247,245,239,.12); color:var(--cream);
        border:1px solid rgba(247,245,239,.25);
        backdrop-filter:blur(4px);
        transition:transform .2s ease, background .2s ease, color .2s ease;
    }
    .lp-facade-play i{ font-size:1.5rem; }
    .lp-facade-play span{ font-size:10px; font-weight:700; letter-spacing:.04em; }
    .lp-facade-hint{
        position:absolute; bottom:1rem; left:0; right:0; z-index:2;
        text-align:center; font-size:11px; color:rgba(247,245,239,.6); font-weight:600;
    }

    /* ===== Cover asli flipbook (menggantikan ikon buku generik) =====
       .lp-facade-cover = gambar cover object-cover penuh; overlay gelap di atasnya
       menjaga tombol Buka & hint tetap terbaca. Bingkai emas + bayangan "halaman
       tersusun" muncul saat ada cover (.has-cover) untuk kesan kartu buku. */
    .lp-facade-cover{
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover; object-position:center;
        z-index:0;
    }
    /* Overlay gelap di atas cover agar teks & tombol kontras.
       z-index di-ATAS cover (z:0) tapi di bawah tombol Buka (z:2). */
    .lp-flipbook-facade.has-cover::before{
        content:''; position:absolute; inset:0; z-index:1; pointer-events:none;
        background:
            linear-gradient(180deg, rgba(16,20,15,.15) 0%, rgba(16,20,15,.55) 70%, rgba(16,20,15,.78) 100%);
    }
    /* has-cover: sembunyikan tekstur halaman default (konflik dgn foto). */
    .lp-flipbook-facade.has-cover::after{ display:none; }
    /* Bingkai emas tipis di tepi facade (frame kartu buku). */
    .lp-flipbook-facade.has-cover{
        border:2px solid color-mix(in srgb, var(--gold) 65%, transparent);
        background:#10140f;
        /* Bayangan halaman tersusun: dua "stack" di belakang kartu utama. */
        box-shadow:
            0 2px 0 rgba(247,245,239,.06),
            0 4px 0 rgba(247,245,239,.04),
            0 12px 30px -12px rgba(0,0,0,.6);
    }
    @media (prefers-reduced-motion: reduce){ .lp-flipbook-facade:hover .lp-facade-play{ transform:none; } }

    @keyframes lp-float{
        0%,100%{ transform:translateY(0); }
        50%   { transform:translateY(-8px); }
    }
    @keyframes lp-ring{
        0%   { transform:scale(1);   opacity:.55; }
        100% { transform:scale(1.9); opacity:0; }
    }
    @media (prefers-reduced-motion: reduce){
        .lp-profil-icon, .lp-profil-icon::before{ animation:none; }
    }
@endpush
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Title Banner -->
        <x-page-header
            eyebrow="Fasilitas & Karakter Rabbani"
            eyebrow-icon="fa-solid fa-flask"
            title="Laboratorium PAI <span class='text-[var(--gold)]'>SMAN 1 Bukittinggi</span>"
            subtitle="Pusat riset, praktikum ibadah, dan pembinaan karakter Pendidikan Agama Islam SMAN 1 Bukittinggi." />

        <!-- 1. SEJARAH SINGKAT, VISI, & MISI -->
        <div class="lp-carousel">
            <div class="lp-track lp-cards">

            <!-- Sejarah Singkat -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-clock-rotate-left lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-green"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <h3 class="lp-card-title">Sejarah Singkat</h3>
                <p class="lp-card-body">Bukan sekadar ruang fisik, Labor PAI adalah pusat <strong>pembinaan karakter</strong>, penguatan akhlak mulia, serta pembiasaan nilai-nilai keislaman dalam kehidupan sehari-hari peserta didik.</p>
            </div>

            <!-- Visi dan Misi -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-eye lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-eye"></i></div>
                <h3 class="lp-card-title">Visi dan Misi</h3>
                <p class="lp-card-body">Menjadi pusat <strong>praktikum keilmuan Islam</strong> dan laboratorium karakter siswa yang unggul, beriman, dan berakhlak mulia.</p>
                <ul class="lp-checklist">
                    <li><i class="fa-solid fa-square-check"></i><span>Memfasilitasi modul praktikum ibadah siswa.</span></li>
                    <li><i class="fa-solid fa-square-check"></i><span>Mengembangkan media syiar &amp; keilmuan Islam.</span></li>
                    <li><i class="fa-solid fa-square-check"></i><span>Membangun ukhuwah &amp; kepemimpinan Rabbani.</span></li>
                </ul>
            </div>
            <!-- Legalitas & Struktur Organisasi -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-file-contract lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-green"><i class="fa-solid fa-file-contract"></i></div>
                <h3 class="lp-card-title">Legalitas &amp; Struktur Organisasi</h3>
                <p class="lp-card-body">Dasar <strong>Surat Keputusan (SK)</strong> yang mengatur pembagian tugas, tanggung jawab, dan wewenang setiap personel agar bekerja secara efektif, terukur, dan sesuai ketentuan.</p>
            </div>
            <!-- Perencanaan & Regulasi Operasional -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-clipboard-list lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-clipboard-list"></i></div>
                <h3 class="lp-card-title">Perencanaan &amp; Regulasi Operasional</h3>
                <p class="lp-card-body">Mencakup penyusunan <strong>kebijakan</strong>, <strong>SOP</strong>, tata kelola layanan, pemanfaatan teknologi, dan mekanisme evaluasi untuk operasional yang aman dan berkelanjutan.</p>
            </div>
            <!-- Pelaksanaan Pemanfaatan -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-laptop lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-green"><i class="fa-solid fa-laptop"></i></div>
                <h3 class="lp-card-title">Pelaksanaan Pemanfaatan</h3>
                <p class="lp-card-body">Pusat <strong>pembelajaran</strong> dan pengembangan kompetensi bagi siswa, guru PAI, KKG, serta MGMP PAI melalui sumber belajar digital dan kolaborasi berbasis teknologi.</p>
            </div>
            <!-- Sarana Prasarana & Inventaris -->
            <div class="tsaqib-card lp-card">
                <i class="fa-solid fa-boxes-stacked lp-card-deco" aria-hidden="true"></i>
                <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-boxes-stacked"></i></div>
                <h3 class="lp-card-title">Sarana Prasarana &amp; Inventaris</h3>
                <p class="lp-card-body">Inventaris <strong>aset</strong>, perangkat keras, dan sarana prasarana penunjang kegiatan Laboratorium PAI Digital.</p>
            </div>

            </div>
        </div>

        {{-- Profil TSAQIB — flipbook embed. Ikon emas (float + ring pulse) sebagai header,
            iframe Heyzine di bawahnya. URL iframe dari DB (default Heyzine). --}}
        <div class="tsaqib-card lp-profil">
            <div class="lp-profil-head">
                <div class="lp-profil-icon"><i class="ti ti-book-2"></i></div>
                <div class="flex-1">
                    <h3 class="lp-card-title">Profil TSAQIB</h3>
                    <p class="lp-card-body">Kenali lebih dekat <strong>Profil TSAQIB FSI</strong> — sejarah, program kerja, dan kepengurusan dalam satu dokumen interaktif.</p>
                </div>
                <a href="{{ $profilTsaqibUrl }}" target="_blank" rel="noopener"
                   class="flex items-center gap-1.5 text-[var(--gold)] hover:text-[var(--cream)] text-xs font-semibold transition-colors flex-shrink-0">
                    Buka penuh <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>

            <div class="lp-flipbook-wrap" id="lp-flipbook">
                {{-- Fake iframe (klik-untuk-muat): cover flipbook asli (object-cover) +
                     overlay gelap + tombol Buka. Iframe Heyzine asli baru di-inject saat
                     diklik, agar halaman tidak memuat embed berat saat pertama dibuka.
                     URL iframe di data-src (tidak dimuat sampai klik). Cover image dari
                     thumbnail Heyzine (diturunkan di controller); bila null → ikon buku. --}}
                <button type="button" class="lp-flipbook-facade {{ $coverUrl ? 'has-cover' : '' }}"
                        data-src="{{ $profilTsaqibUrl }}"
                        aria-label="Buka flipbook Profil TSAQIB">
                    @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="Cover Profil TSAQIB" class="lp-facade-cover" loading="lazy">
                    @endif
                    <span class="lp-facade-play">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Buka</span>
                    </span>
                    <span class="lp-facade-hint">Klik untuk memuat flipbook interaktif</span>
                </button>
            </div>
        </div>

        <!-- 2. INFOGRAFIS STRUKTUR ORGANISASI -->
        <div class="tsaqib-card p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-[var(--cream)]">Infografis Struktur Organisasi</h2>
                    <p class="text-white/50 text-xs">Struktur Pembina Guru & Pengurus Siswa Laboratorium PAI & TSAQIB</p>
                </div>
            </div>

            <!-- INFOGRAPHIC TREE NODES -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <img src="{{ asset('images/struktur.webp') }}" alt="Struktur FSI TSAQIB" class="w-full h-full rounded-xl border border-white/10 bg-white p-2 object-contain" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('images/kepengurusan.webp') }}" alt="Kepengurusan FSI TSAQIB" class="w-full h-full rounded-xl border border-white/10 bg-white p-2 object-contain" loading="lazy" onerror="this.remove()">
            </div>
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    {{-- Carousel infinite-loop + drag (Pointer Events: mouse + touch unified) --}}
    <script>
    (function () {
        var track = document.querySelector('.lp-track');
        if (!track) return;

        // 1) Duplikat kartu sekali → loop seamless (translateX 0 → -50%)
        var originals = Array.prototype.slice.call(track.children);
        originals.forEach(function (node) {
            var clone = node.cloneNode(true);
            clone.classList.add('is-clone');
            clone.setAttribute('aria-hidden', 'true');
            track.appendChild(clone);
        });

        var cards = Array.prototype.slice.call(track.children); // 12 setelah clone
        var step = 0, half = 0;
        function measure() {
            step = cards.length > 1 ? (cards[1].offsetLeft - cards[0].offsetLeft) : cards[0].offsetWidth;
            half = cards[6] ? cards[6].offsetLeft : step * 6;   // lebar 1 set = clone pertama
        }
        measure();
        window.addEventListener('resize', measure);

        var D = parseFloat(getComputedStyle(track).getPropertyValue('--lp-duration') || '40s') * 1000;

        function wrap(v) { v = v % half; if (v > 0) v -= half; return v; } // → (-half, 0]
        function matrixX(el) {
            var m = getComputedStyle(el).transform;
            if (!m || m === 'none') return 0;
            var a = m.match(/matrix[^(]*\(([^)]+)\)/);
            if (!a) return 0;
            var v = a[1].split(',');
            return parseFloat(v.length === 6 ? v[4] : v[12]); // matrix(...) vs matrix3d(...)
        }
        function pauseAnim() { track.style.animation = 'none'; void track.offsetWidth; }
        function resumeAnim(fromTx) {                                  // lanjut loop dari posisi `fromTx`
            var p = half ? (-wrap(fromTx) / half) : 0;
            if (p < 0) p = 0; if (p >= 1) p = 0;
            track.style.transition = 'none';
            track.style.transform = '';
            track.style.animation = 'lp-loop ' + (D / 1000) + 's linear infinite';
            track.style.animationDelay = (-p * D) + 'ms';
            track.style.animationPlayState = 'running';
        }

        var armed = false, dragging = false, startX = 0, startY = 0, baseTx = 0, cur = 0;

        function onDown(e) {
            if (window.matchMedia('(min-width:1024px)').matches) return; // desktop = grid
            armed = true; dragging = false;
            startX = e.clientX; startY = e.clientY;
            baseTx = matrixX(track); cur = baseTx;                        // kunci posisi animasi saat ini
            pauseAnim();
            track.style.transform = 'translateX(' + baseTx + 'px)';       // bridge tanpa lompat
            window.addEventListener('pointermove', onMove);
            window.addEventListener('pointerup', onUp);
            window.addEventListener('pointercancel', onUp);
        }
        function onMove(e) {
            if (!armed) return;
            var dx = e.clientX - startX, dy = e.clientY - startY;
            if (!dragging) {
                if (Math.abs(dx) < 5) return;                            // tunggu gerakan jelas
                if (Math.abs(dy) > Math.abs(dx)) { resumeAnim(baseTx); armed = false; cleanup(); return; } // vertikal → browser scroll
                dragging = true; track.classList.add('is-dragging');
            }
            cur = wrap(baseTx + dx);                                     // instant reset di batas duplikat
            track.style.transform = 'translateX(' + cur + 'px)';
            e.preventDefault();
        }
        function onUp() {
            if (!armed) return;
            if (dragging) {
                track.classList.remove('is-dragging');
                resumeAnim(wrap(Math.round(cur / step) * step));         // snap ke kartu terdekat + resume
            } else {
                resumeAnim(baseTx);                                      // tap biasa → lanjut loop
            }
            armed = false; dragging = false; cleanup();
        }
        function cleanup() {
            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup', onUp);
            window.removeEventListener('pointercancel', onUp);
        }

        track.addEventListener('pointerdown', onDown);
    })();

    /* ===== Fake iframe → muat iframe Heyzine sungguhan saat diklik =====
       Thumbnail (.lp-flipbook-facade) membawa URL di data-src. Klik mengganti
       seluruh isinya dengan <iframe> responsif (aturan .lp-flipbook-wrap iframe
       di <style> sudah mengatur tinggi). URL belum pernah dimuat sebelum klik. */
    (function () {
        var wrap = document.getElementById('lp-flipbook');
        var facade = wrap ? wrap.querySelector('.lp-flipbook-facade') : null;
        if (! wrap || ! facade) return;

        var src = facade.getAttribute('data-src');
        if (! src) return;

        function loadIframe() {
            var iframe = document.createElement('iframe');
            iframe.setAttribute('allowfullscreen', 'allowfullscreen');
            iframe.setAttribute('allow', 'autoplay; fullscreen; clipboard-write');
            iframe.setAttribute('scrolling', 'no');
            iframe.setAttribute('class', 'fp-iframe');
            iframe.setAttribute('src', src);
            iframe.setAttribute('title', 'Flipbook Profil TSAQIB');
            iframe.setAttribute('loading', 'lazy');
            iframe.style.cssText = 'border:1px solid lightgray; width:100%; height:400px;';
            wrap.innerHTML = '';
            wrap.appendChild(iframe);
        }

        facade.addEventListener('click', loadIframe);
        facade.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); loadIframe(); }
        });
    })();
    </script>

</body>
</html>
