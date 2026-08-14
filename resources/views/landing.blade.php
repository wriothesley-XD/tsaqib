<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        label: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root{
            --cream:#F7F5EF;
            --ink:#10140F;
            --green:#01795F;
            --green-dark:#3F704D;
            --gold:#C9A66B;
        }

        /* ===== Background hero: 3 lapis, dari bawah ke atas =====
           1. .hero-bg      → warna ink polos, cuma fallback kalau foto gagal load
           2. .hero-photo   → FOTO ASLI kamu (opsional). Taruh file di:
                               public/assets/landing/hero-photo.jpg
                               Kalau file belum ada, <img> otomatis hilang (onerror="this.remove()")
                               dan yang kelihatan cuma gradient di bawah ini. Begitu file ada,
                               foto otomatis muncul TANPA ubah kode sama sekali.
           3. .hero-overlay → gradient hijau-emas transparan, fungsinya biar teks tetap kebaca
                               di atas foto apapun. Boleh diatur opacity-nya kalau fotonya gelap/terang.
           4. .hero-pattern → pola bintang 8 Islami tipis, paling atas, dekoratif doang. ===== */
        .hero-bg{
            /* Transparent: the global <body> background (gradient + girih pattern,
               from resources/css/app.css) shows through so Beranda stays consistent
               with every other page. The hero's own .hero-photo / .hero-overlay /
               .hero-pattern still layer on top of it, so the hero look is unchanged. */
            background-color:transparent;
        }
        .hero-photo{
            position:absolute;inset:0;z-index:0;
            width:100%;height:100%;object-fit:cover;object-position:center;
        }
        .hero-overlay{
            position:absolute;inset:0;z-index:1;
            background-image:
                radial-gradient(circle at 15% 20%, rgba(1,121,95,.55), transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(201,166,107,.3), transparent 50%),
                linear-gradient(160deg, rgba(13,51,39,.9) 0%, rgba(16,20,15,.92) 55%, rgba(16,20,15,.95) 100%);
        }
        .hero-pattern {
    position: absolute;
    inset: 0;
    z-index: 1;
    /* Ganti 'nama-file-kamu.jpg' dengan nama file foto yang kamu upload nanti */
    background-image: url('{{ asset('assets/landing/fsi.jpg') }}');
    background-size: cover;
    background-position: center;
    opacity: 0.25; /* Atur tingkat transparan/redup foto (0.1 sampai 1) agar teks tetap jelas dibaca */
}

        .brand-mark{
            width:42px;height:42px;border-radius:12px;
            background:linear-gradient(135deg, var(--green), var(--green-dark));
            display:flex;align-items:center;justify-content:center;
            font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;color:var(--cream);
            box-shadow:0 4px 14px rgba(1,121,95,.4);
        }

        .eyebrow-pill{
            display:inline-flex;align-items:center;gap:6px;
            padding:5px 14px;border-radius:999px;
            background:rgba(247,245,239,.1);
            border:1px solid rgba(247,245,239,.2);
            color:var(--cream);
            font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;
            font-size:11px;letter-spacing:.06em;text-transform:uppercase;
            backdrop-filter:blur(4px);
        }

        .cta-primary{
            background:var(--green);
            transition:background .2s ease, transform .2s ease, box-shadow .2s ease;
            box-shadow:0 10px 30px -8px rgba(1,121,95,.6);
        }
        .cta-primary:hover{ background:var(--green-dark); transform:translateY(-2px); }

        /* ===== Carousel: seamless loop + interactive (arrows / drag) =====
           Track = TWO identical sets. JS owns the transform so arrows, drag and
           auto-scroll can compose. `pos` is unbounded; only the RENDER wraps it
           mod one-set-width, so crossing the set boundary is an instant, invisible
           jump (the duplicate set is pixel-identical). Spacing rides on each card
           via margin-right so the wrap lands dead-on — no seam. */
        .carousel-viewport{ overflow:hidden; scrollbar-width:none; }
        .carousel-viewport::-webkit-scrollbar{ display:none; }
        .carousel-track{
            display:flex;
            width:max-content;
            will-change:transform;
            cursor:grab;
            touch-action:pan-y;        /* horizontal gesture = our drag, vertical = scroll page */
            user-select:none;
        }
        .carousel-track:active{ cursor:grabbing; }
        .carousel-set{ display:flex; }
        .carousel-set > *{ margin-right:1rem; }   /* gap rides with each card -> seamless seam */
        .carousel-card{ flex:0 0 auto; touch-action:pan-y; }   /* swipe starts on cards, not just track */
        @media (prefers-reduced-motion: reduce){
            .carousel-viewport{ overflow-x:auto; }
            .carousel-set[aria-hidden="true"]{ display:none; } /* no loop -> originals only */
        }
        .carousel-nav-btn{
            width:38px;height:38px;border-radius:999px;
            display:flex;align-items:center;justify-content:center;
            background:rgba(247,245,239,.1);
            border:1px solid rgba(247,245,239,.25);
            color:var(--cream);
            transition:background .2s ease, transform .15s ease;
        }
        .carousel-nav-btn:hover{ background:rgba(1,121,95,.85); }
        .carousel-nav-btn:active{ transform:scale(.94); }

        .card-face{
            width:198px;height:280px;border-radius:22px;position:relative;overflow:hidden;
            display:flex;flex-direction:column;justify-content:flex-end;
            padding:18px;
            transition:transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s cubic-bezier(.22,1,.36,1);
            border:1px solid rgba(247,245,239,.12);
        }
        @media (min-width:1024px){ .card-face{ width:220px; height:320px; } }
        .card-face:hover{ transform:translateY(-10px) scale(1.015); box-shadow:0 24px 48px -14px rgba(0,0,0,.55); }
        .card-face::after{
            content:'';position:absolute;inset:0;z-index:1;
            background:linear-gradient(180deg, transparent 35%, rgba(0,0,0,.75) 100%);
        }
        .card-face .card-icon,
        .card-face .card-label,
        .card-face .card-desc,
        .card-face .card-arrow{ position:relative; z-index:2; }

        /* Foto card (opsional). Taruh file di public/assets/landing/ dengan nama persis
           yang dirujuk di masing-masing <img class="card-photo">. Kalau file belum ada,
           <img> otomatis hilang (onerror="this.remove()") dan yang tampil cuma gradient
           warna di bawah ini — jadi aman upload kapan aja, bertahap satu-satu. */
        .card-face .card-photo{
            position:absolute;inset:0;z-index:0;
            width:100%;height:100%;object-fit:cover;
            transform:scale(1);
            transition:transform .6s cubic-bezier(.22,1,.36,1);
        }
        .card-face:hover .card-photo{ transform:scale(1.08); }

        /* Micro-interaction: ikon & panah ikut "hidup" pas di-hover, bukan cuma card-nya doang */
        .card-face .card-icon{ transition:transform .4s cubic-bezier(.22,1,.36,1); }
        .card-face:hover .card-icon{ transform:scale(1.15) rotate(-4deg); }
        .card-face .card-arrow i{ display:inline-block; transition:transform .3s cubic-bezier(.22,1,.36,1); }
        .card-face:hover .card-arrow i{ transform:translateX(5px); }

        .c-labor   { background:linear-gradient(155deg,#0f7a5c,#0a4a3a); }
        .c-perpus  { background:linear-gradient(155deg,#3f704d,#1f3a26); }
        .c-komunitas{ background:linear-gradient(155deg,#c9a66b,#8a6a3b); }
        .c-figma   { background:linear-gradient(155deg,#10140f,#01795f); }

        /* Entrance animation: card muncul fade+slide-up bergantian (stagger), biar nggak
           kaku muncul langsung semua bareng pas halaman di-load */
        @keyframes cardEnter{
            from{ opacity:0; transform:translateY(28px) scale(.96); }
            to{ opacity:1; transform:translateY(0) scale(1); }
        }
        .carousel-card{ animation:cardEnter .7s cubic-bezier(.22,1,.36,1) both; }
        .carousel-card:nth-child(1){ animation-delay:.05s; }
        .carousel-card:nth-child(2){ animation-delay:.15s; }
        .carousel-card:nth-child(3){ animation-delay:.25s; }
        .carousel-card:nth-child(4){ animation-delay:.35s; }

        @media (prefers-reduced-motion: reduce){
            *{ transition-duration:.01ms !important; animation-duration:.01ms !important; }
        }

        /* ===== Reveal saat scroll: section BERITA/Buletin muncul fade+slide-up =====
           Dipicu IntersectionObserver (lihat <script> bawah) yang menambah .is-revealed.
           Awalnya hidden (opacity:0 + translateY); saat .is-revealed, kembali ke posisi.
           Stagger: featured dulu, lalu tiap kartu daftar bergantian. */
        .kabar-reveal{ opacity:0; transform:translateY(34px); transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1); }
        .kabar-reveal.is-revealed{ opacity:1; transform:translateY(0); }
        .kabar-reveal-stagger > *{ opacity:0; transform:translateY(26px); transition:opacity .6s cubic-bezier(.22,1,.36,1) both, transform .6s cubic-bezier(.22,1,.36,1) both; }
        .kabar-reveal-stagger.is-revealed > *:nth-child(1){ transition-delay:.06s; }
        .kabar-reveal-stagger.is-revealed > *:nth-child(2){ transition-delay:.16s; }
        .kabar-reveal-stagger.is-revealed > *:nth-child(3){ transition-delay:.26s; }
        .kabar-reveal-stagger.is-revealed > *:nth-child(4){ transition-delay:.36s; }
        .kabar-reveal-stagger.is-revealed > *{ opacity:1; transform:translateY(0); }

        /* Featured: angkat halus saat hover (gambar di dalamnya ikut zoom via group-hover). */
        .kabar-featured{ transition:transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s ease; }
        .kabar-featured:hover{ transform:translateY(-6px); box-shadow:0 30px 60px -20px rgba(0,0,0,.6); }

        /* ===== Reveal section "Jelajahi Komunitas" (stagger fade + slide-up) =====
           Pola SAMA dengan .kabar-reveal di atas: grid diberi [data-reveal] (lihat
           markup), IntersectionObserver menambah .is-revealed; tiap kartu (anak
           grid) awalnya hidden lalu muncul bergantian via nth-child delay.
           translate 20px, ~.45s ease-out, jeda antar-kartu ~70ms. */
        .kom-reveal > *{ opacity:0; transform:translateY(20px);
            transition:opacity .45s cubic-bezier(.22,1,.36,1) both, transform .45s cubic-bezier(.22,1,.36,1) both,
                       border-color .25s ease, box-shadow .25s ease; }
        .kom-reveal.is-revealed > *{ opacity:1; transform:translateY(0); }
        .kom-reveal.is-revealed > *:nth-child(1){ transition-delay:.07s; }
        .kom-reveal.is-revealed > *:nth-child(2){ transition-delay:.14s; }
        .kom-reveal.is-revealed > *:nth-child(3){ transition-delay:.21s; }
        .kom-reveal.is-revealed > *:nth-child(4){ transition-delay:.28s; }
        .kom-reveal.is-revealed > *:nth-child(5){ transition-delay:.35s; }
        .kom-reveal.is-revealed > *:nth-child(6){ transition-delay:.42s; }
        .kom-reveal.is-revealed > *:nth-child(7){ transition-delay:.49s; }
        .kom-reveal.is-revealed > *:nth-child(8){ transition-delay:.56s; }
        .kom-reveal.is-revealed > *:nth-child(9){ transition-delay:.63s; }
        .kom-reveal.is-revealed > *:nth-child(10){ transition-delay:.70s; }
        .kom-reveal.is-revealed > *:nth-child(11){ transition-delay:.77s; }
        .kom-reveal.is-revealed > *:nth-child(12){ transition-delay:.84s; }
        .kom-reveal.is-revealed > *:nth-child(13){ transition-delay:.91s; }
        /* Hover: angkat halus + skala kecil + border emerald/gold (di samping
           efek group-hover warna yang sudah ada di markup kartu). */
        .kom-card{ will-change:transform; }
        .kom-card:hover{ transform:translateY(-4px) scale(1.015) !important;
            border-color:var(--gold) !important; box-shadow:0 18px 40px -18px rgba(201,166,107,.4); }

        @media (prefers-reduced-motion: reduce){
            .kabar-reveal, .kabar-reveal-stagger > *{ opacity:1 !important; transform:none !important; transition:none !important; }
            .kabar-featured:hover{ transform:none; }
            .kom-reveal > *{ opacity:1 !important; transform:none !important; transition:none !important; }
            .kom-card:hover{ transform:none !important; }
        }
    </style>
</head>
<body class="antialiased">

<div class="relative min-h-screen hero-bg overflow-hidden flex flex-col">
    {{-- Foto asli hero (opsional). Upload ke public/assets/landing/hero-photo.jpg dengan
         nama file PERSIS itu — begitu ada, foto langsung tampil otomatis, nggak perlu
         edit kode ini lagi. Kalau belum ada file-nya, <img> ini otomatis hilang (onerror)
         dan yang kelihatan cuma gradient di .hero-overlay. --}}
    <img src="{{ asset('assets/landing/hero-photo.jpg') }}" alt="" class="hero-photo" onerror="this.remove()">
    <div class="hero-overlay pointer-events-none"></div>
    <div class="hero-pattern pointer-events-none"></div>

    {{-- ================= NAVBAR (shared partial — sama di semua halaman) ================= --}}
    @include('partials.navbar')

    {{-- ================= HERO CONTENT ================= --}}
    <main class="relative z-10 flex-1 max-w-7xl w-full mx-auto px-5 sm:px-8 flex flex-col lg:flex-row lg:items-center gap-10 lg:gap-6 pt-14 pb-8 lg:pb-0">

        {{-- Kiri: branding + deskripsi + CTA utama --}}
        <div class="lg:w-[46%] pt-4 lg:pt-0">
            <span class="eyebrow-pill">
                <i class="fa-solid fa-mosque text-[10px]"></i>
                Forum Studi Islam &middot; SMAN 1 Bukittinggi
            </span>

            <h1 class="font-display font-extrabold text-[var(--cream)] leading-[1.05] mt-5 text-5xl sm:text-6xl lg:text-7xl tracking-tight">
                TSAQIB
            </h1>
            <p class="font-display font-bold text-[var(--gold)] text-lg sm:text-xl mt-1 tracking-tight">
                Cerdas, Unggul, dan Berakhlak Mulia
            </p>

            <p class="text-white text-sm sm:text-[15px] leading-relaxed mt-5 max-w-md">
                Wadah kaderisasi dan pengembangan diri siswa/i SMAN 1 Bukittinggi berbasis nilai-nilai
                keislaman &mdash; menghubungkan Laboratorium PAI, Perpustakaan Digital, dan komunitas
                minat &amp; bakat dalam satu ekosistem.
            </p>

            <div class="flex flex-wrap items-center gap-4 mt-8">
                <a href="{{ route('open.recruitment') }}" class="cta-primary inline-flex items-center gap-2.5 text-white font-label font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftar Jadi Anggota</span>
                </a>
            </div>
        </div>

        {{-- Kanan: carousel horizontal --}}
        <div id="program" class="lg:w-[54%] lg:pl-4">
            <div class="flex items-center justify-between mb-4 lg:mb-5">
                <h2 class="font-label text-white text-[11px] font-bold uppercase tracking-widest">
                    Jelajahi Program TSAQIB
                </h2>
                <div class="hidden sm:flex items-center gap-2">
                    <button type="button" id="carousel-prev" class="carousel-nav-btn" aria-label="Sebelumnya">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" id="carousel-next" class="carousel-nav-btn" aria-label="Berikutnya">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="relative carousel-viewport">
                <div id="carousel-track" class="carousel-track">

                    {{-- Set 1: originals --}}
                    <div class="carousel-set">
                        <a href="{{ route('laboratorium.pai') }}" class="carousel-card card-face c-labor">
                            <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-flask text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Laboratorium<br>PAI</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Materi, riset, dan simulasi ibadah</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <a href="{{ route('perpustakaan') }}" class="carousel-card card-face c-perpus">
                            <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-book-open text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Perpustakaan<br>Digital</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Koleksi buku &amp; referensi FSI</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <button type="button" onclick="handleKomunitasClick()" class="carousel-card card-face c-komunitas text-left">
                            <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-users text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Komunitas<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">7 komunitas minat &amp; bakat</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </button>

                        <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2"
                           target="_blank" rel="noopener noreferrer" class="carousel-card card-face c-figma">
                            <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-diagram-project text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Prototype<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Desain awal di Figma</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Lihat <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </span>
                        </a>
                    </div>

                    {{-- Set 2: identical duplicate (decorative) so translateX -50% loops with no seam --}}
                    <div class="carousel-set" aria-hidden="true">
                        <a href="{{ route('laboratorium.pai') }}" class="carousel-card card-face c-labor" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-flask text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Laboratorium<br>PAI</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Materi, riset, dan simulasi ibadah</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <a href="{{ route('perpustakaan') }}" class="carousel-card card-face c-perpus" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-book-open text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Perpustakaan<br>Digital</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Koleksi buku &amp; referensi FSI</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <button type="button" onclick="handleKomunitasClick()" class="carousel-card card-face c-komunitas text-left" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-users text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Komunitas<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">7 komunitas minat &amp; bakat</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </button>

                        <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2"
                           target="_blank" rel="noopener noreferrer" class="carousel-card card-face c-figma" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="" class="card-photo" onerror="this.remove()">
                            <i class="card-icon fa-solid fa-diagram-project text-2xl text-white/90 mb-3"></i>
                            <span class="card-label block font-display font-bold text-white text-lg leading-tight">Prototype<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Desain awal di Figma</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Lihat <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    {{-- ================= BERITA (feed gabungan Berita + Buletin) =================
         Layout: KIRI 1 kartu BESAR (featured = item terbaru) + KANAN daftar kartu
         kecil berjajar. Feed = News terpublikasi + Book kategori 'buletin', diurutkan
         terbaru (date desc). Mobile: featured di atas, daftar di bawah (full width). --}}
    @if($kabarTerbaru->isNotEmpty())
        @php
            $featured = $kabarTerbaru->first();
            $others = $kabarTerbaru->slice(1);
            $fallbackBg = 'background:linear-gradient(155deg,#0f7a5c 0%,#0a4a3a 100%);';
            // Ikon FA per tipe, dipakai badge & placeholder gambar.
            $iconOf = fn ($type) => $type === 'berita' ? 'fa-newspaper' : 'fa-book-open';
        @endphp
    <section class="kabar-reveal relative z-10 w-full pt-16 sm:pt-20" data-reveal>
        <div class="w-full" style="background:linear-gradient(180deg,#0a2e2218 0%,#0618125a 100%);">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-12 sm:py-16">

                {{-- Header: judul "◇✦ BERITA ✦◇" di tengah, "Lihat Semua" kanan-atas --}}
                <div class="relative text-center mb-8 sm:mb-10">
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl tracking-[0.08em] text-[var(--cream)]">
                        <span class="text-[var(--gold)]">◇✦</span>
                        <span class="mx-2">BERITA</span>
                        <span class="text-[var(--gold)]">✦◇</span>
                    </h2>
                    <a href="{{ route('info') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-emerald-400 hover:text-emerald-300 whitespace-nowrap transition absolute right-0 top-1">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                {{-- Split 60/40 (desktop). Mobile: 1 kolom, featured lalu daftar. --}}
                <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-5 sm:gap-6">

                    {{-- ===== KARTU BESAR (featured) ===== --}}
                    <a href="{{ $featured['url'] }}" @if($featured['target'] === '_blank') target="_blank" rel="noopener" @endif
                       class="kabar-featured kabar-reveal group relative block rounded-3xl overflow-hidden shadow-lg shadow-black/30"
                       data-reveal
                       style="min-height:24rem;">
                        @if($featured['image'])
                            <img src="{{ asset('storage/' . $featured['image']) }}" alt="{{ $featured['title'] }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div class="absolute inset-0 items-center justify-center gap-3 text-white/30" style="{{ $fallbackBg }}display:none;">
                                <i class="fa-solid {{ $iconOf($featured['type']) }} text-5xl"></i>
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center gap-3 text-white/30" style="{{ $fallbackBg }}">
                                <i class="fa-solid {{ $iconOf($featured['type']) }} text-5xl"></i>
                            </div>
                        @endif

                        {{-- Overlay gelap di bawah demi keterbacaan teks --}}
                        <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(6,24,18,.05) 0%, rgba(6,24,18,.35) 45%, rgba(6,24,18,.95) 100%);"></div>

                        @if($featured['type'] === 'berita')
                            <span class="absolute top-4 left-4 z-10 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-600/90 text-white font-bold uppercase tracking-wider shadow-md backdrop-blur-sm" style="font-size:11px;">
                                <i class="fa-solid fa-newspaper" style="font-size:9px;"></i> Berita
                            </span>
                        @else
                            <span class="absolute top-4 left-4 z-10 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9A66B] text-[#10140F] font-bold uppercase tracking-wider shadow-md backdrop-blur-sm" style="font-size:11px;">
                                <i class="fa-solid fa-book-open" style="font-size:9px;"></i> Buletin
                            </span>
                        @endif
                        <span class="absolute top-4 right-4 z-10 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9A66B] text-[#10140F] font-bold uppercase tracking-wider shadow-md" style="font-size:11px;">
                            <i class="fa-solid fa-star" style="font-size:9px;"></i> Unggulan
                        </span>

                        {{-- Teks overlay: judul + excerpt 2 baris --}}
                        <div class="absolute inset-x-0 bottom-0 z-10 p-6 sm:p-8">
                            <div class="flex items-center gap-2 text-xs mb-2">
                                <i class="fa-regular fa-calendar text-emerald-300/80" style="font-size:11px;"></i>
                                <span class="text-emerald-300/80">{{ $featured['date']?->format('d M Y') }}</span>
                                @if($featured['author'])
                                    <span class="text-white/40">•</span>
                                    <span class="text-white/55 truncate">{{ $featured['author'] }}</span>
                                @endif
                            </div>
                            <h3 class="font-display font-extrabold text-2xl sm:text-3xl text-white leading-tight line-clamp-3">{{ $featured['title'] }}</h3>
                            @if($featured['excerpt'])
                                <p class="text-white/70 text-sm sm:text-base mt-2 line-clamp-2 leading-relaxed">{{ $featured['excerpt'] }}</p>
                            @endif
                        </div>
                    </a>

                    {{-- ===== DAFTAR KARTU KECIL (kanan) =====
                         Kartu kompak horizontal: thumb kecil kiri + teks (badge, judul,
                         excerpt 1 baris) kanan. Berjajar vertikal, mengisi kolom kanan. --}}
                    <div class="kabar-reveal-stagger flex flex-col gap-3 sm:gap-4" data-reveal>
                        @foreach($others as $r)
                            <a href="{{ $r['url'] }}" @if($r['target'] === '_blank') target="_blank" rel="noopener" @endif
                               class="group tsaqib-card flex gap-3 sm:gap-4 p-3 sm:p-3.5 rounded-2xl overflow-hidden hover:border-[var(--gold)]/40 transition-colors duration-200">
                                {{-- Thumbnail kecil (rasio 4:3), placeholder ikon bila tak ada gambar --}}
                                <div class="relative w-24 h-20 sm:w-28 sm:h-24 shrink-0 rounded-xl overflow-hidden">
                                    @if($r['image'])
                                        <img src="{{ asset('storage/' . $r['image']) }}" alt="{{ $r['title'] }}"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <div class="absolute inset-0 items-center justify-center text-white/30" style="{{ $fallbackBg }}display:none;">
                                            <i class="fa-solid {{ $iconOf($r['type']) }} text-2xl"></i>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center text-white/30" style="{{ $fallbackBg }}">
                                            <i class="fa-solid {{ $iconOf($r['type']) }} text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Badges + judul + excerpt 1 baris --}}
                                <div class="min-w-0 flex-1 flex flex-col">
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        @if($r['type'] === 'berita')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-600/90 text-white font-bold uppercase tracking-wider shadow" style="font-size:9px;">
                                                <i class="fa-solid fa-newspaper" style="font-size:8px;"></i> Berita
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#C9A66B] text-[#10140F] font-bold uppercase tracking-wider shadow" style="font-size:9px;">
                                                <i class="fa-solid fa-book-open" style="font-size:8px;"></i> Buletin
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 text-emerald-300/80" style="font-size:10px;">
                                            <i class="fa-regular fa-calendar" style="font-size:9px;"></i>{{ $r['date']?->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h3 class="font-display font-bold text-sm sm:text-base text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">{{ $r['title'] }}</h3>
                                    @if($r['excerpt'])
                                        <p class="text-white/45 text-xs mt-1 line-clamp-1 leading-relaxed">{{ $r['excerpt'] }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile "Lihat Semua" --}}
                <div class="text-center mt-8 sm:hidden">
                    <a href="{{ route('info') }}" class="cta-primary inline-flex items-center gap-2 text-white font-bold text-xs px-6 py-3.5 rounded-full">
                        <i class="fa-solid fa-grip text-xs"></i> Lihat Semua Berita
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ================= KOMUNITAS PREVIEW (publik — tamu bisa lihat tanpa login) ================= --}}
    @if(!empty($daftarKomunitas))
    <section id="komunitas-preview" class="relative z-10 w-full max-w-7xl mx-auto px-5 sm:px-8 py-14 sm:py-20">
        <div class="text-center mb-10">
            <span class="eyebrow-pill">
                <i class="fa-solid fa-users text-[10px]"></i>
                {{ count($daftarKomunitas) }} Komunitas Minat &amp; Bakat
            </span>
            <h2 class="font-display font-extrabold text-[var(--cream)] text-3xl sm:text-4xl mt-4 tracking-tight">
                Jelajahi Komunitas TSAQIB
            </h2>
            <p class="text-white/60 text-sm mt-3 max-w-xl mx-auto">
                Tiap komunitas punya karakter sendiri. Intip dari dekat, lalu pilih yang paling cocok dengan minatmu.
            </p>
        </div>

        <div class="kom-reveal grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-reveal>
            @foreach($daftarKomunitas as $k)
                <a href="{{ route('komunitas', $k['slug']) }}"
                   class="kom-card group flex items-center gap-4 p-4 rounded-2xl bg-white/[.04] border border-white/10 hover:bg-white/[.08] transition">
                    <img src="{{ asset($k['image']) }}" alt="{{ $k['nama'] }}"
                         class="w-16 h-16 rounded-xl object-cover shrink-0 bg-white/5"
                         onerror="this.remove()">
                    <div class="min-w-0">
                        <h3 class="font-display font-bold text-[var(--cream)] truncate group-hover:text-[var(--gold)] transition-colors">
                            {{ $k['nama'] }}
                        </h3>
                        <p class="text-white/55 text-xs mt-1 line-clamp-2 leading-snug">
                            {{ $k['deskripsi_singkat'] }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-white/30 group-hover:text-[var(--gold)] transition-colors ml-auto shrink-0"></i>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('komunitas', 'semua') }}" class="cta-primary inline-flex items-center gap-2 text-white font-label font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full">
                <i class="fa-solid fa-grip text-xs"></i>
                <span>Lihat Semua Komunitas</span>
            </a>
        </div>
    </section>
    @endif

    {{-- Footer bersama (barisan logo instansi + badge Liivo -> /credits) — sama
         persis dengan halaman publik lain; jangan duplikasi markup di sini. --}}
    @include('partials.site-footer')
</div>

<script>
    // ===== Gerbang auth Komunitas (logic sama kayak emblem-hotspot di Floating Island lama) =====
    function handleKomunitasClick() {
        @auth
            @if(Auth::user()->selected_community)
                window.location.href = "{{ route('komunitas') }}";
            @else
                window.location.href = "{{ route('select-role') }}";
            @endif
        @else
            // Guest boleh membaca feed komunitas (read-only) — tanpa paksa login.
            window.location.href = "{{ route('komunitas') }}";
        @endauth
    }

    // ===== Program carousel: seamless loop + arrows + drag (auto-scroll resumes) =====
    (function () {
        const viewport = document.querySelector('.carousel-viewport');
        const track    = document.getElementById('carousel-track');
        if (!viewport || !track) return;

        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) return;               // CSS media query gives a static scrollable list

        const AUTO_SPEED   = 0.5;   // px / frame (auto-scroll)
        const RESUME_DELAY = 1500;  // ms idle before auto-scroll resumes after interaction
        const SNAP_EASE    = 0.25;  // 0..1, convergence per frame for arrow/release snap

        // pitch = one card + its 1rem margin. offsetWidth ignores transforms -> stable.
        const pitch = () => {
            const c = track.querySelector('.carousel-card');
            return c ? c.offsetWidth + 16 : 220;
        };
        // setWidth = one full set = the loop length (4 cards here).
        let setWidth = 0;
        const measure = () => {
            const set = track.querySelector('.carousel-set');
            setWidth = set ? set.children.length * pitch() : 0;
        };
        measure();

        let pos = 0;          // unbounded scroll position — only the render wraps it
        let mode = 'auto';    // 'auto' | 'drag' | 'snap'
        let target = 0;       // snap destination (unbounded)
        let resumeAt = 0;     // earliest timestamp auto-scroll may run

        // Seamless boundary reset: pos is unbounded, render pos MOD one set.
        // Crossing the set width teleports the transform to an identical copy -> invisible.
        const wrap = () => ((pos % setWidth) + setWidth) % setWidth;
        const apply = () => { track.style.transform = 'translate3d(' + (-wrap()) + 'px,0,0)'; };

        function tick() {
            const now = performance.now();
            if (mode === 'snap') {
                pos += (target - pos) * SNAP_EASE;
                if (Math.abs(target - pos) < 0.5) {
                    pos = target;
                    mode = 'auto';
                    resumeAt = now + RESUME_DELAY;   // resume auto-scroll shortly after
                }
            } else if (mode === 'auto' && now >= resumeAt) {
                pos += AUTO_SPEED;
            }
            apply();
            requestAnimationFrame(tick);
        }

        // Arrows: ease exactly one card in either direction.
        const snapBy = (dir) => {
            target = Math.round(pos / pitch()) * pitch() + dir * pitch();
            mode = 'snap';
            resumeAt = performance.now() + RESUME_DELAY;
        };
        const prev = document.getElementById('carousel-prev');
        const next = document.getElementById('carousel-next');
        if (prev) prev.addEventListener('click', () => snapBy(-1));
        if (next) next.addEventListener('click', () => snapBy(1));

        // Unified drag via Pointer Events (mouse / trackpad / touch / pen — one path).
        // Routed through the same handlers as everything else, so it shares the loop
        // state (pos/mode/resumeAt) and never conflicts with arrows or auto-scroll.
        //
        // move/up dipasang ke window, dan kita TIDAK pakai setPointerCapture. Alasan:
        // setPointerCapture pada ancestor (track) bikin banyak browser me-retarget
        // event `click` ke track, bukan ke <a> card -> kartu tak pernah navigasi ke
        // routenya meski cursor di atas <a>. Dengan listener window, drag tetap
        // terlacak saat pointer keluar track, DAN click bersih jatuh ke <a> card.
        let dragging = false, activeId = null, startX = 0, startPos = 0, moved = false;

        const onMove = (e) => {
            if (!dragging || e.pointerId !== activeId) return;    // ignore other pointers (multi-touch)
            const dx = e.clientX - startX;
            if (!moved && Math.abs(dx) > 8) moved = true;         // threshold -> drag vs click (8px, toleransi jitter trackpad/touch)
            if (moved) { mode = 'drag'; pos = startPos - dx; }    // pointermove: follow cursor/finger 1:1
        };
        const onUp = (e) => {
            if (!dragging || e.pointerId !== activeId) return;
            dragging = false; activeId = null;
            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup', onUp);
            window.removeEventListener('pointercancel', onUp);
            if (!moved) { resumeAt = performance.now(); return; } // klik/tap bersih -> biarkan <a> navigasi
            // Hanya setelah drag betul-betul: telan click susulan agar card tak navigasi
            track.addEventListener('click', (ev) => ev.preventDefault(), { capture: true, once: true });
            target = Math.round(pos / pitch()) * pitch();         // pointerup: snap to nearest card
            mode = 'snap';
            resumeAt = performance.now() + RESUME_DELAY;          // resume auto-scroll shortly after
        };
        const onDown = (e) => {
            dragging = true; moved = false; activeId = e.pointerId;
            startX = e.clientX; startPos = pos;
            resumeAt = Infinity;                                  // pointerdown: pause auto-scroll now
            window.addEventListener('pointermove', onMove);
            window.addEventListener('pointerup', onUp);
            window.addEventListener('pointercancel', onUp);
        };
        track.addEventListener('pointerdown', onDown);
        // pan-y (CSS on track + cards) reserves horizontal swipes for us while vertical
        // still scrolls the page; wrap() makes hitting the cloned set reset instantly
        // (no transition) to the matching real card -> stays infinite.

        // Pause auto-scroll while hovering (desktop nicety); resume on leave.
        viewport.addEventListener('mouseenter', () => { if (mode === 'auto') resumeAt = Infinity; });
        viewport.addEventListener('mouseleave', () => { if (mode === 'auto') resumeAt = 0; });

        // Responsive card width (198px <-> 220px): re-measure the loop length on resize.
        window.addEventListener('resize', measure, { passive: true });

        requestAnimationFrame(tick);
    })();

    // ===== Reveal section BERITA/Buletin saat masuk viewport =====
    // Elemen bertanda [data-reveal] awalnya hidden (lihat .kabar-reveal di <style>).
    // Saat terlihat, kelas .is-revealed ditambahkan → fade + slide-up masuk.
    // Stagger (.kabar-reveal-stagger) membuat anak-anaknya muncul bergantian.
    (function () {
        var revealEls = document.querySelectorAll('[data-reveal]');
        if (!revealEls.length) return;

        // Tanpa IntersectionObserver (browser lama) → tampilkan langsung.
        if (!('IntersectionObserver' in window)) {
            revealEls.forEach(function (el) { el.classList.add('is-revealed'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    io.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -12% 0px', threshold: 0.12 });

        revealEls.forEach(function (el) { io.observe(el); });
    })();
</script>

</body>
</html>