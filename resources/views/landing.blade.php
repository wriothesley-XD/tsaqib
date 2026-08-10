<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                        label: ['Manrope', 'sans-serif'],
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
            font-family:'Manrope',sans-serif;font-weight:700;
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
                Cerdas Iman, Unggul Prestasi
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($daftarKomunitas as $k)
                <a href="{{ route('komunitas', $k['slug']) }}"
                   class="group flex items-center gap-4 p-4 rounded-2xl bg-white/[.04] border border-white/10 hover:bg-white/[.08] hover:border-white/20 transition">
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

    {{-- ================= FOOTER: "Rumah Baru" untuk logo instansi =================
         Kiri (atau atas di mobile): teks hak cipta.
         Kanan (atau bawah di mobile): barisan logo instansi pendukung, rapi & horizontal.
         Semua logo sudah ada di public/assets/logo-instansi/ — kalau salah satu file belum
         ada, <img>-nya otomatis hilang (onerror) tanpa merusak layout yang lain. --}}
    <footer class="relative z-10 border-t border-white/10 mt-auto">
        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 py-6 flex flex-col lg:flex-row items-center justify-between gap-5">

            <p class="text-white text-[11px] font-label text-center lg:text-left order-2 lg:order-1">
                &copy; {{ date('Y') }} TSAQIB &middot; Forum Studi Islam SMAN 1 Bukittinggi
            </p>

            <div class="flex items-center gap-4 sm:gap-6 order-1 lg:order-2 bg-white/[.05] border border-white/10 rounded-2xl px-5 py-3 sm:px-6 sm:py-3.5">
                <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Kementerian Agama" title="Kementerian Agama" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Tut Wuri Handayani" title="Tut Wuri Handayani" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Pemerintah Provinsi Sumatera Barat" title="Pemerintah Provinsi Sumatera Barat" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="SMAN 1 Bukittinggi" title="SMAN 1 Bukittinggi" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/fsi.webp') }}" alt="Forum Studi Islam" title="Forum Studi Islam" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            </div>

        </div>
    </footer>
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
        let dragging = false, activeId = null, startX = 0, startPos = 0, moved = false;

        const onDown = (e) => {
            dragging = true; moved = false; activeId = e.pointerId;
            startX = e.clientX; startPos = pos;
            resumeAt = Infinity;                                  // pointerdown: pause auto-scroll now
            if (track.setPointerCapture) {                        // keep events flowing past the track edge
                try { track.setPointerCapture(e.pointerId); } catch (_) {}
            }
        };
        const onMove = (e) => {
            if (!dragging || e.pointerId !== activeId) return;    // ignore other pointers (multi-touch)
            const dx = e.clientX - startX;
            if (!moved && Math.abs(dx) > 4) moved = true;         // threshold -> drag vs click
            if (moved) { mode = 'drag'; pos = startPos - dx; }    // pointermove: follow cursor/finger 1:1
        };
        const onUp = (e) => {
            if (!dragging || e.pointerId !== activeId) return;
            dragging = false; activeId = null;
            if (!moved) { resumeAt = performance.now(); return; } // click/tap: just resume
            // swallow the click that follows a drag so card links don't navigate
            track.addEventListener('click', (ev) => ev.preventDefault(), { capture: true, once: true });
            target = Math.round(pos / pitch()) * pitch();         // pointerup: snap to nearest card
            mode = 'snap';
            resumeAt = performance.now() + RESUME_DELAY;          // resume auto-scroll shortly after
        };
        track.addEventListener('pointerdown', onDown);
        track.addEventListener('pointermove', onMove);
        track.addEventListener('pointerup', onUp);
        track.addEventListener('pointercancel', onUp);
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
</script>

</body>
</html>