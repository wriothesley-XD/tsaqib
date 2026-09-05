<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --cream:#F7F5EF;
            --ink:#10140F;
            --green:#01795F;
            --green-dark:#3F704D;
            --gold:#C9A66B;
            /* Tangga hijau satu keluarga — variasi antar section HANYA level gelap */
            --green-s0:#0D2818;   /* paling gelap: Hero overlay, Lab, Komunitas, Kabar, Footer */
            --green-s1:#143520;   /* 1 step terang: Perpustakaan */
            --green-s2:#1C442B;   /* 2 step: frame gambar / panel */
        }

        /* ===== Background hero: foto fsi.jpg cover/fixed + overlay obsidian.
           Stop terakhir gradient = #0D2818 (--green-s0) agar fade menyatu
           mulus dgn seksi Laboratorium di bawahnya. ===== */
        .hero-bg{
            background-color:#0D2818;
            background-image:url('{{ asset('assets/landing/fsi.jpg') }}');
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            background-attachment:fixed;
        }
        .hero-overlay{
            position:absolute;inset:0;z-index:0;pointer-events:none;
            background:linear-gradient(to bottom, rgba(13,40,24,.92) 0%, rgba(13,40,24,.82) 50%, #0D2818 100%);
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
            background:#01795F;
            transition:filter .2s ease, transform .2s ease; /* hanya properti compositor */
            box-shadow:0 10px 30px -8px rgba(1,121,95,.55); /* statis — tak dianimasikan */
        }
        .cta-primary:hover{ filter:brightness(1.1); transform:translateY(-2px); }

        /* ===== Gaya editorial gelap — hijau tua + emas + teks krem =====
           Satu keluarga warna dari Hero sampai Footer; variasi antar section
           hanya dari level gelap-terang hijau (--green-s0/s1/s2). */
        .ed-eyebrow{
            font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:11px;
            letter-spacing:.28em;text-transform:uppercase;color:var(--gold);
        }
        /* Tombol solid emas di atas hijau tua — teks hijau paling gelap */
        .btn-gold{
            display:inline-flex;align-items:center;gap:.5rem;
            background:var(--gold);color:var(--green-s0);border-radius:9999px;
            font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;
            font-size:12px;padding:.875rem 1.5rem;white-space:nowrap;
            transition:filter .15s ease,transform .15s ease;
        }
        .btn-gold:hover{ filter:brightness(1.08);transform:translateY(-2px); } /* -2px = sama dgn cta-primary */

        /* Poin dgn garis vertikal emas di kiri (bukan ikon kotak) */
        .vpoint{ border-left:2px solid rgba(201,166,107,.55); padding-left:1rem; }
        .vpoint h4{ font-size:11px;letter-spacing:.14em;color:var(--cream); }
        .vpoint p{ color:rgba(247,245,239,.7); }

        /* Frame gambar/placeholder: border emas + overlay hijau semi-transparan
           supaya foto apa pun (termasuk placeholder) tetap menyatu dlm keluarga warna */
        .ph{
            position:relative;overflow:hidden;border-radius:1rem;
            border:1px solid rgba(201,166,107,.4);
            background:linear-gradient(155deg,var(--green-s2),var(--green-s0));
        }
        .ph::after{
            content:'';position:absolute;inset:0;pointer-events:none;
            background:linear-gradient(180deg, rgba(13,40,24,.12) 0%, rgba(13,40,24,.42) 100%);
        }

        /* Pola girih islami — SVG sama dgn pattern global situs (app.css),
           dipakai sebagai overlay samar di seksi Laboratorium. */
        .pat-islami{
            position:absolute;inset:0;pointer-events:none;
            background-image:url("data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20width%3D%2748%27%20height%3D%2748%27%3E%3Cg%20fill%3D%27none%27%20stroke%3D%27%235DCAA5%27%20stroke-width%3D%271%27%20opacity%3D%270.14%27%3E%3Cpath%20d%3D%27M24%2C2%20L34%2C12%20L24%2C22%20L14%2C12%20Z%27%2F%3E%3Cpath%20d%3D%27M24%2C26%20L34%2C36%20L24%2C46%20L14%2C36%20Z%27%2F%3E%3Cpath%20d%3D%27M0%2C12%20L10%2C2%20L10%2C22%20Z%27%20opacity%3D%270.6%27%2F%3E%3Cpath%20d%3D%27M48%2C12%20L38%2C2%20L38%2C22%20Z%27%20opacity%3D%270.6%27%2F%3E%3Cpath%20d%3D%27M0%2C36%20L10%2C26%20L10%2C46%20Z%27%20opacity%3D%270.6%27%2F%3E%3Cpath%20d%3D%27M48%2C36%20L38%2C26%20L38%2C46%20Z%27%20opacity%3D%270.6%27%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");
            background-size:48px 48px;
        }

        /* Carousel foto Laboratorium PAI: cross-fade opacity murni ~1s, tanpa geser layout
           (semua slide absolute+stacked, tinggi frame ditentukan aspect-[9/10] di .ph). */
        .lab-slide{
            position:absolute;inset:0;width:100%;height:100%;
            object-fit:cover;opacity:0;pointer-events:none;
            transition:opacity 1s ease-in-out;
        }
        .lab-slide.is-active{ opacity:1; }

        /* Dot indicator — aktif = emas + sedikit lebih besar (scale, tanpa reflow) */
        .lab-dots{
            position:absolute;z-index:2;left:0;right:0;bottom:14px;
            display:flex;align-items:center;justify-content:center;gap:8px;
        }
        .lab-dot{
            width:8px;height:8px;border-radius:999px;
            background:rgba(247,245,239,.35);
            border:1px solid rgba(247,245,239,.3);
            padding:0;cursor:pointer;
            transition:background-color .3s ease,border-color .3s ease,transform .3s ease;
        }
        .lab-dot:hover{ background:rgba(247,245,239,.6); }
        .lab-dot.is-active{ background:var(--gold);border-color:var(--gold);transform:scale(1.35); }

        /* ===== Interaksi "list berganti" (swap): list kiri + preview kanan =====
           Animasi HANYA opacity + translateY kecil. Cross-fade 250ms. */
        .swap-slide{
            position:absolute;inset:0;
            opacity:0;transform:translateY(8px);pointer-events:none;
            transition:opacity .25s ease-out,transform .25s ease-out; /* 250ms — dalam rentang 200-300ms */
            will-change:opacity,transform; /* layer cross-fade interaktif */
        }
        .swap-slide.is-active{ opacity:1;transform:none;pointer-events:auto; }
        /* Baris list: bar emas kiri dgn scaleY (tanpa reflow saat aktif) */
        .swap-item{ position:relative;transition:background .2s ease; }
        .swap-item .swap-bar{
            position:absolute;left:0;top:14%;bottom:14%;width:3px;
            background:#C9A66B;opacity:.3;transform:scaleY(.6);
            transition:opacity .2s ease,transform .2s ease;
        }
        .swap-item.is-active{ background:rgba(201,166,107,.10); }
        .swap-item.is-active .swap-bar{ opacity:1;transform:scaleY(1); }

        /* Callout "Info Pengumpulan Tugas" — panel emas di atas hijau tua */
        .callout-tugas{
            background:linear-gradient(135deg, rgba(201,166,107,.16), rgba(201,166,107,.06));
            border:1px solid rgba(201,166,107,.45);
        }

        /* ===== Carousel hero: seamless loop + interactive ===== */
        .carousel-viewport{ overflow:hidden; scrollbar-width:none; }
        .carousel-viewport::-webkit-scrollbar{ display:none; }
        .carousel-track{
            display:flex;
            width:max-content;
            will-change:transform;
            cursor:grab;
            touch-action:pan-y;
            user-select:none;
        }
        .carousel-track:active{ cursor:grabbing; }
        .carousel-set{ display:flex; }
        .carousel-set > *{ margin-right:1.5rem; }
        .carousel-card{ flex:0 0 auto; touch-action:pan-y; }
        @media (prefers-reduced-motion: reduce){
            .carousel-viewport{ overflow-x:auto; }
            .carousel-set[aria-hidden="true"]{ display:none; }
        }
        .carousel-nav-btn{
            width:38px;height:38px;border-radius:999px;
            display:flex;align-items:center;justify-content:center;
            background:rgba(247,245,239,.1);
            border:1px solid rgba(247,245,239,.25);
            color:var(--cream);
            transition:background .2s ease, transform .15s ease;
        }
        .carousel-nav-btn:hover{ background:rgba(1,121,95,.9); }
        .carousel-nav-btn:active{ transform:scale(.94); }

        /* Kartu carousel: glass + emas, lebar tetap (pitch loop mengandalkannya) */
        .card-face{
            width:198px;
            border-radius:22px;position:relative;overflow:hidden;
            display:flex;flex-direction:column;justify-content:flex-end;
            aspect-ratio:4/5;
            padding:24px;
            color:var(--cream); /* judul card mewarisi krem — tanpa ini default-nya hitam */
            transition:transform .45s cubic-bezier(.22,1,.36,1); /* shadow hover instan — tanpa repaint kontinu */
            background:rgba(13,40,24,.8);
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
            border:1px solid rgba(201,166,107,.2);
        }
        @media (min-width:1024px){ .card-face{ width:220px; } }
        .card-face:hover{ transform:translateY(-10px) scale(1.015); box-shadow:0 24px 48px -14px rgba(0,0,0,.55); }

        /* TSAQIB signature: gold gradient border yang "menyala" saat hover */
        .card-face::before{
            content:'';
            position:absolute;
            inset:0;
            border-radius:22px;
            padding:2px;
            background:linear-gradient(135deg, transparent 40%, rgba(201,166,107,.6) 50%, transparent 60%);
            -webkit-mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite:xor;
            mask-composite:exclude;
            opacity:0;
            transition:opacity .4s ease;
            z-index:3;
        }
        .card-face:hover::before{
            opacity:1;
            animation:borderGlow 2s ease-in-out infinite;
        }
        @keyframes borderGlow{
            0%, 100%{ background-position:0% 50%; }
            50%{ background-position:100% 50%; }
        }
        .card-face::after{
            content:'';position:absolute;inset:0;z-index:1;
            /* Overlay bawah hijau tua pekat — teks card terbaca di atas foto apa pun */
            background:linear-gradient(to top, rgba(13,40,24,.92) 0%, rgba(13,40,24,.45) 50%, transparent 100%);
        }
        .card-face .card-icon,
        .card-face .card-label,
        .card-face .card-desc,
        .card-face .card-arrow{ position:relative; z-index:2; }

        .card-face .card-photo{
            position:absolute;inset:0;z-index:0;
            width:100%;height:100%;object-fit:cover;object-position:center;
            transform:scale(1);
            transition:transform .6s cubic-bezier(.22,1,.36,1);
        }
        .card-face:hover .card-photo{ transform:scale(1.08); }

        .card-face .card-icon{ transition:transform .4s cubic-bezier(.22,1,.36,1); }
        .card-face:hover .card-icon{ transform:scale(1.15) rotate(-4deg); }
        .card-face .card-arrow i{ display:inline-block; transition:transform .3s cubic-bezier(.22,1,.36,1); }
        .card-face:hover .card-arrow i{ transform:translateX(5px); }

        @keyframes cardEnter{
            from{ opacity:0; transform:translateY(28px) scale(.96); }
            to{ opacity:1; transform:translateY(0) scale(1); }
        }
        .card-face{ animation:cardEnter .7s cubic-bezier(.22,1,.36,1) both; }
        .card-face:nth-child(1){ animation-delay:.05s; }
        .card-face:nth-child(2){ animation-delay:.15s; }
        .card-face:nth-child(3){ animation-delay:.25s; }
        .card-face:nth-child(4){ animation-delay:.35s; }

        /* ===== Reveal on scroll: fade-up sekali per elemen =====
           Hidden-state HANYA saat <html> ber-class .js-reveal (dipasang JS
           berkemampuan IntersectionObserver) → tanpa JS semua tetap terlihat.
           Hidden-state memakai :not(.is-visible), BUKAN menimpa transform
           di .is-visible — dulu .is-visible{transform:none} (spesifisitas
           0,3,0) mengalahkan hover-lift social-card/cta-card selamanya.
           Setelah transisi selesai JS melepas class reveal (lihat IO di bawah)
           → transisi hover komponen (250ms) kembali normal. */
        .js-reveal .reveal:not(.is-visible){
            opacity:0;
            transform:translateY(24px);
        }
        .js-reveal .reveal{
            will-change:opacity,transform; /* dilepas JS setelah animasi selesai */
            transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
            transition-delay:calc(var(--reveal-i,0) * 90ms);
        }

        /* Anchor in-page tidak tertelan sticky navbar (h-16/20) */
        section[id]{ scroll-margin-top:5.5rem; }

        /* Chip TODO pada foto placeholder — terlihat jelas sebagai NON-FINAL */
        .ph-todo{
            position:absolute;z-index:2;top:.75rem;right:.75rem;
            font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;
            font-size:9px;letter-spacing:.12em;text-transform:uppercase;
            color:var(--gold);background:rgba(13,40,24,.78);
            border:1px dashed rgba(201,166,107,.75);
            padding:4px 10px;border-radius:999px;
        }

        /* Tombol kembali ke atas — muncul setelah hero keluar viewport.
           Animasi hanya opacity+transform. */
        #to-top{
            opacity:0;transform:translateY(10px);pointer-events:none;
            transition:opacity .25s ease,transform .25s ease;
        }
        #to-top.is-show{ opacity:1;transform:none;pointer-events:auto; }

        /* Dot indicator rotator Berita — aktif = emas + scale (tanpa reflow) */
        .berita-dot{
            width:10px;height:10px;border-radius:999px;
            background:rgba(247,245,239,.28);
            border:1px solid rgba(247,245,239,.25);
            transition:background-color .2s ease,transform .2s ease;
        }
        .berita-dot:hover{ background:rgba(247,245,239,.55); }
        .berita-dot.is-active{ background:var(--gold);border-color:var(--gold);transform:scale(1.3); }

        @media (prefers-reduced-motion: reduce){
            /* Matikan SEMUA gerak: durasi & delay dibuang, smooth-scroll dipatkan */
            *{ transition-duration:.01ms !important; animation-duration:.01ms !important;
               transition-delay:0ms !important; animation-delay:0ms !important; }
            html{ scroll-behavior:auto !important; } /* override class scroll-smooth */
        }

        /* ===== Mobile <768px: kurangi beban animasi (resource terbatas) ===== */
        @media (max-width:767px){
            /* background-attachment:fixed = repaint tiap scroll di low-end → pakai scroll biasa */
            .hero-bg{ background-attachment:scroll; }
            /* 8 layer blur backdrop = biaya komposit terbesar di HP; bg card sudah rgba(.8) tetap terbaca */
            .card-face{ backdrop-filter:none; -webkit-backdrop-filter:none; }
            /* Stagger dipendekkan & dicap 4 langkah — antrean reveal tidak menumpuk */
            .js-reveal .reveal{ transition-delay:calc(min(var(--reveal-i,0), 3) * 60ms); }
        }

        /* ===== Counter hero: reserve lebar angka final SEBELUM count-up (anti-CLS)
           — lebar persisnya di-set JS dari data-target; tabular-nums cegah goyang digit ===== */
        .counter{
            display:inline-block;min-width:2ch;text-align:center;
            font-variant-numeric:tabular-nums;
        }

        /* Hero entrance */
        .hero-entrance{
            opacity:0;
            transform:translateY(20px);
            animation:heroFadeUp .8s cubic-bezier(.22,1,.36,1) forwards;
        }
        @keyframes heroFadeUp{
            to{ opacity:1; transform:translateY(0); }
        }
        .hero-entrance:nth-child(1){ animation-delay:.1s; }
        .hero-entrance:nth-child(2){ animation-delay:.2s; }
        .hero-entrance:nth-child(3){ animation-delay:.3s; }
        .hero-entrance:nth-child(4){ animation-delay:.4s; } /* paragraf deskripsi — sebelumnya tanpa delay */

        /* Social media style community cards — gelap, satu keluarga hijau */
        .social-card{
            background:rgba(247,245,239,.04);
            border:1px solid rgba(247,245,239,.10);
            border-radius:16px;
            overflow:hidden;
            transition:transform .25s cubic-bezier(.22,1,.36,1), border-color .25s ease; /* shadow instan */
        }
        .social-card:hover{
            transform:translateY(-6px);
            border-color:rgba(201,166,107,.4);
            box-shadow:0 16px 32px -12px rgba(0,0,0,.5);
        }
        .social-card-header{
            display:flex;
            align-items:center;
            gap:.75rem;
            padding:.75rem 1rem;
            border-bottom:1px solid rgba(247,245,239,.08);
        }
        .social-card-avatar{
            width:2.5rem;height:2.5rem;
            border-radius:50%;
            object-fit:cover;
            background:rgba(247,245,239,.08);
        }
        .social-card-image{
            width:100%;
            height:200px;
            object-fit:cover;
        }
        .social-card-caption{
            padding:1rem;
        }
        .social-card-footer{
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:.5rem 1rem 1rem;
            font-size:.75rem;
            color:rgba(247,245,239,.6);
        }
        .badge-default{
            background:rgba(201,166,107,.14);
            color:var(--gold);
            border-color:rgba(201,166,107,.35);
        }

        .scrollbar-hide{
            -ms-overflow-style:none;
            scrollbar-width:none;
        }
        .scrollbar-hide::-webkit-scrollbar{
            display:none;
        }
    </style>
</head>
<body class="antialiased">

<div class="relative min-h-screen hero-bg overflow-hidden flex flex-col">
    <div class="hero-overlay"></div>

    {{-- ================= NAVBAR (shared partial) ================= --}}
    @include('partials.navbar')

    {{-- ================= HERO (dipertahankan) ================= --}}
    <main class="relative z-10 flex-1 max-w-7xl w-full mx-auto px-5 sm:px-8 flex flex-col lg:flex-row lg:items-center gap-10 lg:gap-16 py-16 lg:py-24">

        <div class="lg:w-[46%] pt-4 lg:pt-0">
            <span class="eyebrow-pill hero-entrance">
                <i class="fa-solid fa-mosque text-[10px]"></i>
                Forum Studi Islam &middot; SMAN 1 Bukittinggi
            </span>

            <h1 class="font-display font-extrabold text-[var(--cream)] leading-[1.05] mt-5 text-5xl sm:text-6xl lg:text-7xl tracking-tight hero-entrance">
                TSAQIB
            </h1>
            <p class="font-display font-bold text-[var(--gold)] text-lg sm:text-xl mt-1 tracking-tight hero-entrance">
                Cerdas, Unggul, dan Berakhlak Mulia
            </p>

            <p class="text-white text-sm sm:text-[15px] leading-relaxed mt-5 max-w-md hero-entrance">
                Wadah kaderisasi dan pengembangan diri siswa/i SMAN 1 Bukittinggi berbasis nilai-nilai
                keislaman &mdash; menghubungkan Laboratorium PAI, Perpustakaan Digital, dan komunitas
                minat &amp; bakat dalam satu ekosistem.
            </p>

            <div class="flex flex-wrap items-center gap-6 mt-6 text-white/70 text-xs sm:text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-book text-[var(--gold)] text-sm"></i>
                    <span class="counter" data-target="{{ $totalModul ?? 0 }}">0</span>
                    <span>Modul</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-[var(--gold)] text-sm"></i>
                    <span class="counter" data-target="{{ $totalAnggota ?? 0 }}">0</span>
                    <span>Anggota</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-people-group text-[var(--gold)] text-sm"></i>
                    <span class="counter" data-target="{{ $totalKomunitas ?? 0 }}">0</span>
                    <span>Circle Tersedia</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 mt-8">
                <a href="{{ route('register') }}" class="cta-primary inline-flex items-center gap-2.5 text-white font-label font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Yuk, Gabung TSAQIB!</span>
                </a>
                <a href="{{ route('open.recruitment') }}" class="inline-flex items-center gap-2.5 text-white font-label font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full border border-white/20 hover:bg-white/5 hover:border-white/30 transition">
                    <i class="fa-solid fa-users text-xs"></i>
                    <span>Daftar Jadi Anggota FSI</span>
                </a>
            </div>
        </div>

        {{-- Kanan: bento grid 2x2 statis — sengaja digeser turun dari rata atas kiri --}}
        <div class="lg:w-[54%] lg:translate-y-6">
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

            <div class="relative carousel-viewport py-4 px-2">
                <div id="carousel-track" class="carousel-track">

                    {{-- Set 1: originals --}}
                    <div class="carousel-set">

                        <a href="{{ route('laboratorium.pai') }}" class="carousel-card card-face">
                            <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.5 9 12 5.16-1.5 9-6.45 9-12V7l-10-5z"/>
                                <path d="M8 10h8"/>
                                <path d="M8 14h8"/>
                                <path d="M12 18v-3"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Laboratorium<br>PAI</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Materi, riset, dan simulasi ibadah</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <a href="{{ route('perpustakaan') }}" class="carousel-card card-face">
                            <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.5 9 12 5.16-1.5 9-6.45 9-12V7l-10-5z"/>
                                <path d="M12 2v10m0 0l-3-3m3 3l3-3"/>
                                <path d="M9 12l3 3 3-3"/>
                                <circle cx="12" cy="8" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Perpustakaan<br>Digital</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Koleksi buku &amp; referensi FSI</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <button type="button" onclick="handleKomunitasClick()" class="carousel-card card-face text-left">
                            <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 2v4m0 12v4m8-12h-4m-8 0H4"/>
                                <circle cx="7" cy="7" r="1.5"/>
                                <circle cx="17" cy="7" r="1.5"/>
                                <circle cx="7" cy="17" r="1.5"/>
                                <circle cx="17" cy="17" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Komunitas<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">7 komunitas minat &amp; bakat</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </button>

                        <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2"
                           target="_blank" rel="noopener noreferrer" class="carousel-card card-face">
                            <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2l3 6-5 4 5 4-3 6-3-6 5-4-5-4 3-6z"/>
                                <path d="M12 12l4 2m-4-2l-4 2"/>
                                <circle cx="12" cy="12" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Prototype<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Desain awal di Figma</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Lihat <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </span>
                        </a>
                    </div>

                    {{-- Set 2: duplikat identik (dekoratif) utk loop mulus --}}
                    <div class="carousel-set" aria-hidden="true">

                        <a href="{{ route('laboratorium.pai') }}" class="carousel-card card-face" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.5 9 12 5.16-1.5 9-6.45 9-12V7l-10-5z"/>
                                <path d="M8 10h8"/>
                                <path d="M8 14h8"/>
                                <path d="M12 18v-3"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Laboratorium<br>PAI</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Materi, riset, dan simulasi ibadah</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <a href="{{ route('perpustakaan') }}" class="carousel-card card-face" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.5 9 12 5.16-1.5 9-6.45 9-12V7l-10-5z"/>
                                <path d="M12 2v10m0 0l-3-3m3 3l3-3"/>
                                <path d="M9 12l3 3 3-3"/>
                                <circle cx="12" cy="8" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Perpustakaan<br>Digital</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Koleksi buku &amp; referensi FSI</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </a>

                        <button type="button" onclick="handleKomunitasClick()" class="carousel-card card-face text-left" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 2v4m0 12v4m8-12h-4m-8 0H4"/>
                                <circle cx="7" cy="7" r="1.5"/>
                                <circle cx="17" cy="7" r="1.5"/>
                                <circle cx="7" cy="17" r="1.5"/>
                                <circle cx="17" cy="17" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Komunitas<br>TSAQIB</span>
                            <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">7 komunitas minat &amp; bakat</span>
                            <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                                Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </button>

                        <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2"
                           target="_blank" rel="noopener noreferrer" class="carousel-card card-face" tabindex="-1">
                            <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                            <svg class="card-icon w-8 h-8 text-white/90 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2l3 6-5 4 5 4-3 6-3-6 5-4-5-4 3-6z"/>
                                <path d="M12 12l4 2m-4-2l-4 2"/>
                                <circle cx="12" cy="12" r="1.5"/>
                            </svg>
                            <span class="card-label block font-bold text-lg leading-tight">Prototype<br>TSAQIB</span>
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

    {{-- ================= SECTION 1: KABAR TERBARU — 2 blok asimetris (di bawah hero) =================
         Blok A (±2/3): BERITA — rotator cross-fade 250ms, auto 4.5s, pause saat
         hover/focus, dot indicator clickable. Blok B (±1/3): BULETIN list ringkas.
         Badge satu keluarga: Berita = emas, Buletin = hijau sage. --}}
    @if($beritaTerbaru->isNotEmpty() || $buletinTerbaru->isNotEmpty())
    <section id="kabar" class="relative z-10 w-full" style="background:var(--green-s0);border-top:1px solid rgba(247,245,239,.06);">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 sm:py-24">

            <div class="flex items-end justify-between gap-6 mb-8 reveal" style="--reveal-i:0;">
                <div>
                    <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Kabar &amp; Kegiatan FSI</p>
                    <h2 class="font-display font-extrabold text-[var(--cream)] text-3xl sm:text-4xl leading-[1.08] tracking-tight mt-3">
                        KABAR TERBARU
                    </h2>
                </div>
                <a href="{{ route('info') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-[var(--gold)] hover:text-[#DCC9A0] whitespace-nowrap transition shrink-0 pb-1">
                    Semua Kabar <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-5 sm:gap-6 items-stretch">

                {{-- ===== BLOK A — BERITA (dominan, ±2/3): rotator cross-fade ===== --}}
                @if($beritaTerbaru->isNotEmpty())
                <div class="lg:col-span-2 reveal" style="--reveal-i:1;" data-berita-rotator>
                    <div class="relative aspect-[16/12] sm:aspect-[16/9] rounded-2xl overflow-hidden border border-white/10" style="background:linear-gradient(155deg,#1C442B,#0D2818);">
                        @foreach($beritaTerbaru as $item)
                            <a href="{{ $item['url'] }}" @if($item['target'] === '_blank') target="_blank" rel="noopener" @endif
                               class="swap-slide {{ $loop->first ? 'is-active' : '' }} group block"
                               data-berita-slide @unless($loop->first) inert @endunless>
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                                         loading="lazy" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <i class="fa-solid fa-newspaper text-5xl text-white/10"></i>
                                    </div>
                                @endif
                                {{-- Overlay gelap keemasan di bawah — judul tetap terbaca di atas foto apa pun --}}
                                <span class="absolute inset-0 pointer-events-none" style="background:linear-gradient(180deg, rgba(13,40,24,0) 30%, rgba(13,40,24,.55) 68%, rgba(13,40,24,.94) 100%);"></span>

                                <span class="absolute left-4 top-4 sm:left-5 sm:top-5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-md" style="background:#C9A66B;color:#0D2818;">
                                    Berita
                                </span>

                                <span class="absolute inset-x-0 bottom-0 p-5 sm:p-7 block">
                                    <span class="block font-display font-extrabold text-[var(--cream)] text-lg sm:text-2xl leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                        {{ $item['title'] }}
                                    </span>
                                    <span class="flex items-center gap-2 text-[11px] sm:text-xs text-white/60 mt-2.5">
                                        <i class="fa-regular fa-calendar text-[var(--gold)]/80 text-[10px]"></i>
                                        {{ $item['date']?->locale('id')->translatedFormat('d F Y') }}
                                        @if($item['author'])
                                            <span class="text-white/35">&middot;</span> {{ $item['author'] }}
                                        @endif
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </div>

                    {{-- Dot indicator: jumlah = jumlah berita; klik = lompat ke slide tsb --}}
                    @if($beritaTerbaru->count() > 1)
                    <div class="flex items-center justify-center gap-2.5 mt-4" data-berita-dots role="group" aria-label="Pilih berita">
                        @foreach($beritaTerbaru as $item)
                            <button type="button" data-berita-dot aria-label="Berita {{ $loop->iteration }}: {{ $item['title'] }}"
                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                    class="berita-dot {{ $loop->first ? 'is-active' : '' }}"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- ===== BLOK B — BULETIN (ringkas, ±1/3): list statis ===== --}}
                @if($buletinTerbaru->isNotEmpty())
                <div class="reveal {{ $beritaTerbaru->isNotEmpty() ? '' : 'lg:col-span-3' }}" style="--reveal-i:2;">
                    <div class="social-card h-full flex flex-col">
                        <div class="social-card-header">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background:#3F704D;color:#F7F5EF;">Buletin</span>
                            <span class="text-[11px] text-white/50">Terbitan terbaru</span>
                        </div>
                        <div class="flex-1 flex flex-col">
                            @foreach($buletinTerbaru as $b)
                                <a href="{{ $b['url'] }}" @if($b['target'] === '_blank') target="_blank" rel="noopener" @endif
                                   class="group flex items-center gap-3.5 px-4 py-3.5 border-b border-white/[.07] hover:bg-white/[.04] transition-colors {{ $loop->last ? 'border-b-0' : '' }}">
                                    @if($loop->first)
                                        <span class="w-12 h-16 shrink-0 rounded-md overflow-hidden border border-[rgba(201,166,107,.35)]" style="background:linear-gradient(155deg,#1C442B,#0D2818);">
                                            @if($b['image'])
                                                <img src="{{ asset('storage/' . $b['image']) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()">
                                            @endif
                                        </span>
                                    @endif
                                    <span class="flex-1 min-w-0">
                                        <span class="block font-bold text-sm text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">{{ $b['title'] }}</span>
                                        <span class="block text-[11px] text-white/50 mt-1">
                                            {{ $b['date']?->locale('id')->translatedFormat('d M Y') }}@if($b['author']) &middot; {{ $b['author'] }} @endif
                                        </span>
                                    </span>
                                    <i class="fa-solid {{ $b['target'] === '_blank' ? 'fa-file-pdf' : 'fa-arrow-right' }} text-xs text-[var(--gold)]/80 shrink-0"></i>
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('info', ['tab' => 'buletin']) }}" class="flex items-center gap-1.5 px-4 py-3.5 text-xs font-bold text-[var(--gold)] border-t border-white/[.07] hover:bg-white/[.04] transition-colors">
                            Semua Buletin <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ================= SECTION 2: SPOTLIGHT LABORATORIUM PAI =================
         Gaya editorial, bg HIJAU PALING GELAP (--green-s0). Split 50:50 + garis
         vertikal emas, list modul interaktif (cross-fade preview), callout tugas. --}}
    @php
        $modulKelas = [
            'x' => [
                ['judul' => 'Fikih: Thaharah & Shalat Fardhu',            'guru' => 'Ust. Fauzi, S.Pd.',    'ikon' => 'fa-hands-bubbles'],
                ['judul' => 'Akidah Akhlak: Makna Rukun Iman',            'guru' => 'Usth. Rahma, S.Pd.I',  'ikon' => 'fa-heart'],
                ['judul' => 'SKI: Dakwah Nabi Muhammad ﷺ',                 'guru' => 'Ust. Yusuf, S.Ag.',    'ikon' => 'fa-landmark'],
                ['judul' => "Al-Qur'an & Hadits: Tilawah & Tajwid Dasar", 'guru' => 'Usth. Aini, S.Pd.I',   'ikon' => 'fa-book-quran'],
            ],
            'xi' => [
                ['judul' => 'Fikih: Zakat, Infak & Sedekah',              'guru' => 'Ust. Fauzi, S.Pd.',    'ikon' => 'fa-hand-holding-heart'],
                ['judul' => 'Akidah Akhlak: Akhlak kepada Sesama',        'guru' => 'Usth. Rahma, S.Pd.I',  'ikon' => 'fa-people-arrows'],
                ['judul' => 'SKI: Islam di Nusantara',                    'guru' => 'Ust. Yusuf, S.Ag.',    'ikon' => 'fa-map-location-dot'],
                ['judul' => "Al-Qur'an & Hadits: Kaidah Tafsir Dasar",    'guru' => 'Usth. Aini, S.Pd.I',   'ikon' => 'fa-book-open'],
            ],
            'xii' => [
                ['judul' => 'Fikih: Muamalah & Jual Beli',                'guru' => 'Ust. Fauzi, S.Pd.',    'ikon' => 'fa-scale-balanced'],
                ['judul' => 'Akidah Akhlak: Meneladani Para Ulama',       'guru' => 'Usth. Rahma, S.Pd.I',  'ikon' => 'fa-user-graduate'],
                ['judul' => 'SKI: Islam Modern & Pembaruan',              'guru' => 'Ust. Yusuf, S.Ag.',    'ikon' => 'fa-lightbulb'],
                ['judul' => "Al-Qur'an & Hadits: Hadits Tematik Pilihan", 'guru' => 'Usth. Aini, S.Pd.I',   'ikon' => 'fa-quote-right'],
            ],
        ];
        $kelasLabel = ['x' => 'Kelas X', 'xi' => 'Kelas XI', 'xii' => 'Kelas XII'];
    @endphp
    <section id="labor" class="relative z-10 w-full" style="background:var(--green-s0);border-top:1px solid rgba(247,245,239,.06);">
        <div class="pat-islami"></div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 sm:py-24 relative">

            {{-- ===== A. Split 50:50 — teks (kiri) + foto (kanan) ===== --}}
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="reveal" style="--reveal-i:0;">
                    <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Dari Ruang Laboratorium PAI</p>
                    <h2 class="font-display font-extrabold text-[var(--cream)] text-3xl sm:text-5xl leading-[1.08] tracking-tight mt-4">
                        KAMI BINA KARAKTER, BUKAN SEKADAR HAFALAN
                    </h2>
                    <p class="text-white/75 text-base sm:text-lg leading-relaxed mt-5 max-w-xl">
                        Laboratorium PAI adalah pusat praktikum keilmuan Islam &amp; laboratorium karakter —
                        tempat nilai keislaman dipraktikkan, bukan cuma dihafal.
                    </p>

                    <div class="space-y-5 mt-8">
                        <div class="vpoint">
                            <h4 class="font-display font-extrabold uppercase">Sejarah Singkat</h4>
                            <p class="text-sm mt-1">Bukan sekadar ruang fisik — pusat pembinaan karakter siswa/i SMAN 1 Bukittinggi.</p>
                        </div>
                        <div class="vpoint">
                            <h4 class="font-display font-extrabold uppercase">Visi Rabbani</h4>
                            <p class="text-sm mt-1">Unggul, beriman, berakhlak mulia — kepemimpinan yang hidup dalam keseharian.</p>
                        </div>
                        <div class="vpoint">
                            <h4 class="font-display font-extrabold uppercase">Digital &amp; Kolaboratif</h4>
                            <p class="text-sm mt-1">Sumber belajar digital &amp; modul praktikum bagi siswa dan guru PAI.</p>
                        </div>
                    </div>

                    <a href="{{ route('laboratorium.pai') }}#profil" class="btn-gold mt-9">
                        Profil Laboratorium <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="reveal relative" style="--reveal-i:1;">
                    {{-- Carousel foto Laboratorium PAI: 3 foto, auto cross-fade + dot indicator.
                         Taruh file foto di public/images/laboratorium/foto-1.jpg, foto-2.jpg, foto-3.jpg --}}
                    <div class="ph aspect-[9/10]" data-lab-rotator>
                        <img src="{{ asset('images/laboratorium/foto-1.jpg') }}" alt="Suasana Laboratorium PAI — foto 1"
                             class="lab-slide is-active" data-lab-slide loading="lazy" onerror="this.remove()">
                        <img src="{{ asset('images/laboratorium/foto-2.jpg') }}" alt="Suasana Laboratorium PAI — foto 2"
                             class="lab-slide" data-lab-slide loading="lazy" onerror="this.remove()">
                        <img src="{{ asset('images/laboratorium/foto-3.jpg') }}" alt="Suasana Laboratorium PAI — foto 3"
                             class="lab-slide" data-lab-slide loading="lazy" onerror="this.remove()">
                        <span class="ph-todo">TODO: Foto asli menyusul</span>

                        {{-- Dot indicator: klik = lompat langsung ke foto tsb --}}
                        <div class="lab-dots" data-lab-dots role="group" aria-label="Pilih foto Laboratorium PAI">
                            <button type="button" data-lab-dot aria-label="Foto 1" aria-pressed="true"  class="lab-dot is-active"></button>
                            <button type="button" data-lab-dot aria-label="Foto 2" aria-pressed="false" class="lab-dot"></button>
                            <button type="button" data-lab-dot aria-label="Foto 3" aria-pressed="false" class="lab-dot"></button>
                        </div>
                    </div>
                    {{-- Aksen emas offset di pojok — depth ala editorial --}}
                    <span class="absolute -bottom-3 -left-3 w-24 h-24 rounded-2xl pointer-events-none" style="border:2px solid rgba(201,166,107,.6);"></span>
                </div>
            </div>

            {{-- ===== B. List kelas interaktif + preview berganti (cross-fade 250ms) ===== --}}
            <div class="mt-20 sm:mt-28">
                <div class="reveal" style="--reveal-i:0;">
                    <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Modul Pembelajaran</p>
                    <h3 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.08] tracking-tight mt-4">
                        3 TINGKATAN, PULUHAN MODUL
                    </h3>
                </div>

                {{-- Pola "ganti-ganti": list kiri (hover/klik) → preview kanan cross-fade.
                    Auto-rotate 4.5s, berhenti permanen saat user berinteraksi. --}}
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-14 items-stretch mt-10" data-swap="modul">
                    <div class="reveal flex flex-col" style="--reveal-i:1;" role="tablist" aria-label="Modul per tingkatan kelas">
                        @foreach($modulKelas as $kelasKey => $moduls)
                            <button type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    class="swap-item w-full text-left px-5 py-6 flex items-center gap-5 border-b border-white/10 {{ $loop->first ? 'is-active' : '' }}"
                                    data-swap-item data-index="{{ $loop->index }}">
                                <span class="swap-bar"></span>
                                <span class="font-display font-extrabold text-[var(--gold)] text-sm tracking-[0.12em] uppercase w-24 shrink-0">{{ $kelasLabel[$kelasKey] }}</span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-bold text-sm text-[var(--cream)] truncate">{{ $moduls[0]['judul'] }}</span>
                                    <span class="block text-xs text-white/60 mt-1">4 mapel — Fikih, Akidah Akhlak, SKI, Al-Qur'an &amp; Hadits</span>
                                </span>
                                <i class="fa-solid fa-arrow-right text-xs text-[var(--gold)]"></i>
                            </button>
                        @endforeach
                        <a href="{{ route('laboratorium.pai') }}#modul" class="group inline-flex items-center gap-1.5 mt-5 text-[var(--gold)] text-sm font-bold px-5">
                            {{-- Panah bergeser via transform, bukan animasi gap (gap = layout reflow) --}}
                            Lihat semua modul <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    {{-- Preview: slide bertumpuk, cross-fade 250ms + caption ikut berganti --}}
                    <div class="reveal relative aspect-[4/5] sm:aspect-[5/4] lg:aspect-auto" style="--reveal-i:2;" data-swap-preview>
                        @foreach($modulKelas as $kelasKey => $moduls)
                            <figure class="swap-slide flex flex-col {{ $loop->first ? 'is-active' : '' }}" data-swap-slide data-index="{{ $loop->index }}">
                                <div class="ph flex-1 min-h-0">
                                    <img src="{{ asset('images/placeholders/placeholder-kelas-' . $kelasKey . '.png') }}"
                                         alt="Praktikum {{ $kelasLabel[$kelasKey] }}" class="w-full h-full object-cover" loading="lazy"
                                         onerror="this.remove()">
                                    <span class="ph-todo">TODO: Foto asli menyusul</span>
                                </div>
                                <figcaption class="shrink-0 pt-4">
                                    <p class="font-display font-extrabold text-[var(--cream)] text-sm">{{ $kelasLabel[$kelasKey] }} — 4 modul contoh</p>
                                    <p class="text-xs text-white/60 mt-1 line-clamp-2">{{ collect($moduls)->pluck('judul')->implode(' · ') }}</p>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== C. Info Pengumpulan Tugas (callout emas di atas hijau tua) ===== --}}
            <div class="callout-tugas rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-5 sm:gap-7 mt-20 sm:mt-28 reveal" style="--reveal-i:0;">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full shrink-0 flex items-center justify-center" style="background:rgba(201,166,107,.16);border:1px solid rgba(201,166,107,.5);">
                    <i class="fa-brands fa-google text-2xl sm:text-3xl text-[var(--gold)]"></i>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="font-display font-extrabold text-[var(--cream)] text-lg sm:text-xl">Info Pengumpulan Tugas</h3>
                    <p class="text-white/70 text-sm mt-1.5 leading-relaxed">
                        Kumpulkan tugasmu langsung ke <strong class="text-[var(--gold)]">Google Classroom</strong> guru mapel masing-masing — cek kode kelas di guru pengampumu.
                    </p>
                </div>
                <a href="{{ route('laboratorium.pai') }}#tugas" class="btn-gold shrink-0">
                    Lihat Semua Tugas <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ================= SECTION 3: PERPUSTAKAAN DIGITAL =================
         Satu step LEBIH TERANG (--green-s1) — ritme visual, tetap satu keluarga.
         Zig-zag: preview cover di KIRI, list interaktif di KANAN. --}}
    <section id="perpus" class="relative z-10 w-full" style="background:var(--green-s1);border-top:1px solid rgba(247,245,239,.06);">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 sm:py-24">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-14 items-stretch" data-swap="perpus">

                {{-- Preview (kiri): slide cover bertumpuk + caption & tombol ikut cross-fade --}}
                <div class="reveal relative order-2 lg:order-1" style="--reveal-i:1;">
                    @if($katalogPerpus->isNotEmpty())
                        <div class="relative aspect-[4/5]">
                            @foreach($katalogPerpus->take(5) as $b)
                                <figure class="swap-slide flex flex-col {{ $loop->first ? 'is-active' : '' }}" data-swap-slide data-index="{{ $loop->index }}">
                                    <div class="ph flex-1 min-h-0">
                                        <img src="{{ $b['image'] ? asset('storage/' . $b['image']) : asset('images/placeholders/placeholder-perpustakaan.png') }}"
                                             alt="Cover {{ $b['title'] }}" class="w-full h-full object-cover" loading="lazy"
                                             onerror="this.remove()">
                                        @if(empty($b['image']))
                                            <span class="ph-todo">TODO: Cover menyusul</span>
                                        @endif
                                    </div>
                                    <figcaption class="shrink-0 pt-4">
                                        <p class="ed-eyebrow">{{ ucfirst($b['category']) }}</p>
                                        <p class="font-display font-extrabold text-[var(--cream)] text-lg leading-snug mt-1 line-clamp-1">{{ $b['title'] }}</p>
                                        <div class="flex gap-2 mt-3">
                                            <a href="{{ $b['pdf'] ?? route('perpustakaan') }}" target="_blank" rel="noopener" class="btn-gold">
                                                <i class="fa-solid fa-book-open-reader text-[10px]"></i> Baca Online
                                            </a>
                                            @if($b['pdf'])
                                                <a href="{{ $b['pdf'] }}" download
                                                   class="inline-flex items-center gap-1.5 font-bold text-[11px] px-4 py-2.5 rounded-full transition hover:bg-white/5 border border-white/25 text-[var(--cream)]">
                                                    <i class="fa-solid fa-download text-[10px]"></i> Unduh PDF
                                                </a>
                                            @endif
                                        </div>
                                    </figcaption>
                                </figure>
                            @endforeach
                        </div>
                    @else
                        <div class="ph">
                            <img src="{{ asset('images/placeholders/placeholder-perpustakaan.png') }}"
                                 alt="Perpustakaan Digital TSAQIB" class="w-full aspect-[4/5] object-cover" loading="lazy"
                                 onerror="this.remove()">
                            <span class="ph-todo">TODO: Foto asli menyusul</span>
                        </div>
                    @endif
                </div>

                {{-- List (kanan): judul koleksi, hover/klik → preview kiri berganti --}}
                <div class="order-1 lg:order-2 flex flex-col justify-center">
                    <div class="reveal" style="--reveal-i:0;">
                        <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Rak Baca Digital</p>
                        <h2 class="font-display font-extrabold text-[var(--cream)] text-3xl sm:text-5xl leading-[1.08] tracking-tight mt-4">
                            BACA DI MANA SAJA, UNDUH KAPAN SAJA
                        </h2>
                        <p class="text-white/75 text-base sm:text-lg leading-relaxed mt-5 max-w-xl">
                            Buletin, e-book, dan risalah islamiyah koleksi FSI. Arahkan kursor ke judul untuk mengintip cover-nya.
                        </p>
                    </div>

                    @if($katalogPerpus->isNotEmpty())
                        <div class="mt-8 reveal" style="--reveal-i:2;" role="tablist" aria-label="Daftar koleksi perpustakaan">
                            @foreach($katalogPerpus->take(5) as $b)
                                <button type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        class="swap-item w-full text-left px-5 py-4 flex items-center gap-4 border-b border-white/10 {{ $loop->first ? 'is-active' : '' }}"
                                        data-swap-item data-index="{{ $loop->index }}">
                                    <span class="swap-bar"></span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block font-bold text-sm text-[var(--cream)] truncate">{{ $b['title'] }}</span>
                                        <span class="block text-xs text-white/60 mt-0.5">{{ ucfirst($b['category']) }}@if($b['author']) &middot; {{ $b['author'] }} @endif</span>
                                    </span>
                                    <i class="fa-solid fa-arrow-right text-xs text-[var(--gold)]"></i>
                                </button>
                            @endforeach
                        </div>
                        <a href="{{ route('perpustakaan') }}" class="group inline-flex items-center gap-1.5 mt-6 text-[var(--gold)] text-sm font-bold px-5 reveal" style="--reveal-i:3;">
                            Kunjungi Perpustakaan <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    @else
                        <p class="text-white/70 text-sm mt-8 px-5 reveal" style="--reveal-i:2;">
                            Belum ada koleksi — nantikan buletin &amp; e-book terbaru dari kami.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ================= SECTION 4: KOMUNITAS & KADERISASI FSI =================
         Kembali ke --green-s0. Card feed ala sosial media, staggered slide-in,
         2 CTA terpisah (anggota FSI ≠ akun platform). --}}
    @if(!empty($daftarKomunitas))
    <section id="komunitas-preview" class="relative z-10 w-full" style="background:var(--green-s0);border-top:1px solid rgba(247,245,239,.06);">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 sm:py-24 relative">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 reveal" style="--reveal-i:0;">
                <div>
                    <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Sapa Circle-nya — {{ count($daftarKomunitas) }} Komunitas Minat &amp; Bakat</p>
                    <h2 class="font-display font-extrabold text-[var(--cream)] text-3xl sm:text-5xl leading-[1.08] tracking-tight mt-4">
                        AYO IKUT KOMUNITAS!
                    </h2>
                    <p class="text-white/75 text-sm sm:text-base mt-3 max-w-xl">
                        Tiap komunitas punya karakter sendiri. Intip dari dekat, lalu pilih yang paling cocok dengan minatmu.
                    </p>
                </div>
                <a href="{{ route('komunitas', 'semua') }}" class="btn-gold shrink-0 self-start sm:self-auto">
                    Lihat Semua Komunitas <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Mobile: horizontal scroll carousel; Desktop: grid 3-kolom.
                 Stagger via --reveal-i (0.09s/kartu). --}}
            <div class="flex lg:grid lg:grid-cols-3 gap-4 overflow-x-auto pb-4 lg:pb-0 scrollbar-hide snap-x snap-mandatory">
                @foreach($daftarKomunitas as $k)
                    <a href="{{ route('komunitas', $k['slug']) }}"
                       class="social-card reveal flex-shrink-0 w-full max-w-[320px] lg:max-w-none snap-center"
                       style="--reveal-i:{{ $loop->index + 1 }};">
                        <div class="social-card-header">
                            <img src="{{ asset($k['image']) }}" alt="{{ $k['nama'] }}"
                                 class="social-card-avatar"
                                 loading="lazy" onerror="this.remove()">
                            <div class="min-w-0">
                                <h4 class="font-display font-bold text-sm text-[var(--cream)] truncate">{{ $k['nama'] }}</h4>
                                <p class="text-[10px] text-white/50">Komunitas TSAQIB</p>
                            </div>
                        </div>

                        <div class="relative h-48" style="background:linear-gradient(155deg,#1C442B,#0D2818);">
                            <img src="{{ asset($k['image']) }}" alt="Aktivitas {{ $k['nama'] }}"
                                 class="social-card-image"
                                 loading="lazy" onerror="this.remove()">
                        </div>

                        <div class="social-card-caption">
                            <p class="text-white/75 text-sm leading-relaxed line-clamp-3">
                                {{ $k['deskripsi_singkat'] }}
                            </p>
                        </div>

                        <div class="social-card-footer">
                            <span class="badge-default text-[10px] font-semibold px-2 py-0.5 rounded-full border">Siap Gabung</span>
                            <span class="flex items-center gap-1.5 font-semibold text-[var(--gold)]">
                                Lihat Feed <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- ===== 2 CTA terpisah: pendaftaran organisasi vs akun platform ===== --}}
            <div class="grid sm:grid-cols-2 gap-5 mt-12 relative z-10">
                <a href="{{ route('open.recruitment') }}" class="reveal flex items-start gap-4 rounded-2xl p-6 transition-transform hover:-translate-y-1.5 hover:shadow-xl hover:shadow-black/30"
                   style="background:rgba(247,245,239,.04);border:1px solid rgba(201,166,107,.3); --reveal-i:1;">
                    <span class="w-11 h-11 rounded-full shrink-0 flex items-center justify-center" style="background:rgba(201,166,107,.14);border:1px solid rgba(201,166,107,.4);">
                        <i class="fa-solid fa-users text-[var(--gold)]"></i>
                    </span>
                    <span class="flex-1">
                        <span class="block font-display font-extrabold text-[var(--cream)] text-base">Daftar Anggota FSI</span>
                        <span class="block text-white/70 text-sm mt-1 leading-relaxed">Ikuti kaderisasi &amp; jadi bagian organisasi FSI.</span>
                    </span>
                    <i class="fa-solid fa-arrow-right text-[var(--gold)] mt-1.5"></i>
                </a>

                <a href="{{ route('register') }}" class="reveal flex items-start gap-4 rounded-2xl p-6 transition-transform hover:-translate-y-1.5 hover:shadow-xl hover:shadow-black/30"
                   style="background:rgba(247,245,239,.04);border:1px solid rgba(1,121,95,.4); --reveal-i:2;">
                    <span class="w-11 h-11 rounded-full shrink-0 flex items-center justify-center" style="background:rgba(1,121,95,.16);border:1px solid rgba(1,121,95,.45);">
                        <i class="fa-solid fa-user-plus text-[#3DBD98]"></i>
                    </span>
                    <span class="flex-1">
                        <span class="block font-display font-extrabold text-[var(--cream)] text-base">Buat Akun Tsaqib</span>
                        <span class="block text-white/70 text-sm mt-1 leading-relaxed">Akses portal belajar &amp; modul (Portal LMS).</span>
                    </span>
                    <i class="fa-solid fa-arrow-right text-[#3DBD98] mt-1.5"></i>
                </a>
            </div>

        </div>
    </section>
    @endif

    {{-- ================= FOOTER — logo institusi berlabel di baris paling bawah ================= --}}
    <footer class="relative z-10 border-t border-white/10 mt-auto" style="background:var(--green-s0);">
        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 py-6 flex flex-col lg:flex-row items-center justify-between gap-5">

            <p class="text-white text-[11px] font-label text-center lg:text-left order-2 lg:order-1">
                &copy; {{ date('Y') }} TSAQIB &middot; Forum Studi Islam SMAN 1 Bukittinggi
            </p>

            <div class="flex items-start gap-4 sm:gap-6 order-1 lg:order-2 bg-white/[.05] border border-white/10 rounded-2xl px-5 py-3 sm:px-6 sm:py-3.5">
                {{-- Logo + label kecil di bawah tiap logo. File belum ada → img hilang (onerror), label tetap. --}}
                <div class="flex flex-col items-center gap-1.5 w-16">
                    <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Kementerian Agama" title="Kementerian Agama" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" loading="lazy" onerror="this.remove()">
                    <span class="text-[9px] text-white/60 text-center leading-tight">Kemenag</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 w-16">
                    <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Tut Wuri Handayani" title="Tut Wuri Handayani" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" loading="lazy" onerror="this.remove()">
                    <span class="text-[9px] text-white/60 text-center leading-tight">Tut Wuri Handayani</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 w-16">
                    <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Pemerintah Provinsi Sumatera Barat" title="Pemerintah Provinsi Sumatera Barat" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" loading="lazy" onerror="this.remove()">
                    <span class="text-[9px] text-white/60 text-center leading-tight">Pemprov Sumbar</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 w-16">
                    <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="SMAN 1 Bukittinggi" title="SMAN 1 Bukittinggi" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" loading="lazy" onerror="this.remove()">
                    <span class="text-[9px] text-white/60 text-center leading-tight">SMAN 1 Bukittinggi</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 w-16">
                    <img src="{{ asset('assets/logo-instansi/fsi.webp') }}" alt="Forum Studi Islam" title="Forum Studi Islam" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" loading="lazy" onerror="this.remove()">
                    <span class="text-[9px] text-white/60 text-center leading-tight">Forum Studi Islam</span>
                </div>
            </div>

        </div>
    </footer>

    {{-- Kembali ke atas — muncul setelah hero keluar viewport (JS di bawah) --}}
    <button id="to-top" type="button" inert aria-label="Kembali ke atas"
            class="fixed bottom-5 right-5 z-[80] w-11 h-11 rounded-full flex items-center justify-center text-[var(--cream)] border border-[rgba(201,166,107,.5)] shadow-lg"
            style="background:rgba(13,40,24,.85);backdrop-filter:blur(6px);">
        <i class="fa-solid fa-arrow-up text-sm"></i>
    </button>
</div>

<script>
    // ===== Gerbang auth Komunitas =====
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

        const AUTO_SPEED   = 0.5;   // px / frame
        const RESUME_DELAY = 1500;  // ms idle before auto-scroll resumes
        const SNAP_EASE    = 0.25;  // convergence per frame for arrow/release snap
        const CARD_GAP     = 24;    // px, = .carousel-set > * margin-right

        const pitch = () => {
            const c = track.querySelector('.carousel-card');
            return c ? c.offsetWidth + CARD_GAP : 244;
        };
        let setWidth = 0;
        const measure = () => {
            const set = track.querySelector('.carousel-set');
            setWidth = set ? set.children.length * pitch() : 0;
        };
        measure();

        let pos = 0;          // unbounded scroll position — only the render wraps it
        let mode = 'auto';    // 'auto' | 'drag' | 'snap'
        let target = 0;
        let resumeAt = 0;

        const wrap = () => ((pos % setWidth) + setWidth) % setWidth;
        const apply = () => { track.style.transform = 'translate3d(' + (-wrap()) + 'px,0,0)'; };

        let last = performance.now();
        function tick(now) {
            const dt = Math.min(now - last, 100); // cap: tab idle tidak membuat lompatan
            last = now;
            if (mode === 'snap') {
                pos += (target - pos) * SNAP_EASE;
                if (Math.abs(target - pos) < 0.5) {
                    pos = target;
                    mode = 'auto';
                    resumeAt = now + RESUME_DELAY;
                }
            } else if (mode === 'auto' && now >= resumeAt) {
                pos += AUTO_SPEED * (dt / 16.7); // dikalibrasi 60fps → kecepatan konsisten di layar 120Hz
            }
            apply();
            requestAnimationFrame(tick);
        }

        const snapBy = (dir) => {
            target = Math.round(pos / pitch()) * pitch() + dir * pitch();
            mode = 'snap';
            resumeAt = performance.now() + RESUME_DELAY;
        };
        const prev = document.getElementById('carousel-prev');
        const next = document.getElementById('carousel-next');
        if (prev) prev.addEventListener('click', () => snapBy(-1));
        if (next) next.addEventListener('click', () => snapBy(1));

        // Drag via Pointer Events. move/up di window (bukan setPointerCapture) agar
        // click tetap jatuh ke <a> card — bukan ke track.
        let dragging = false, activeId = null, startX = 0, startPos = 0, moved = false;

        const onMove = (e) => {
            if (!dragging || e.pointerId !== activeId) return;
            const dx = e.clientX - startX;
            if (!moved && Math.abs(dx) > 8) moved = true;
            if (moved) { mode = 'drag'; pos = startPos - dx; }
        };
        const onUp = (e) => {
            if (!dragging || e.pointerId !== activeId) return;
            dragging = false; activeId = null;
            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup', onUp);
            window.removeEventListener('pointercancel', onUp);
            if (!moved) { resumeAt = performance.now(); return; } // klik bersih -> biarkan navigasi
            track.addEventListener('click', (ev) => ev.preventDefault(), { capture: true, once: true });
            target = Math.round(pos / pitch()) * pitch();
            mode = 'snap';
            resumeAt = performance.now() + RESUME_DELAY;
        };
        const onDown = (e) => {
            dragging = true; moved = false; activeId = e.pointerId;
            startX = e.clientX; startPos = pos;
            resumeAt = Infinity;
            window.addEventListener('pointermove', onMove);
            window.addEventListener('pointerup', onUp);
            window.addEventListener('pointercancel', onUp);
        };
        track.addEventListener('pointerdown', onDown);

        viewport.addEventListener('mouseenter', () => { if (mode === 'auto') resumeAt = Infinity; });
        viewport.addEventListener('mouseleave', () => { if (mode === 'auto') resumeAt = 0; });

        window.addEventListener('resize', measure, { passive: true });

        requestAnimationFrame(tick);
    })();

    // ===== Reveal on scroll (IntersectionObserver): fade-up sekali per elemen =====
    (function () {
        var items = document.querySelectorAll('.reveal');
        if (!items.length) return;
        if (!('IntersectionObserver' in window)) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        document.documentElement.classList.add('js-reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target); // sekali reveal — tidak replay saat scroll ulang
                    // Animasi selesai → lepas class reveal: will-change turun dari memori
                    // dan transisi hover komponen (250ms) kembali aktif menggantikan
                    // transisi reveal (700ms).
                    entry.target.addEventListener('transitionend', function () {
                        entry.target.classList.remove('reveal', 'is-visible');
                    }, { once: true });
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        items.forEach(function (el) { io.observe(el); });
    })();

    // ===== Hero counter: count-up 0 → angka asli saat load =====
    (function () {
        var counters = document.querySelectorAll('.counter');
        if (!counters.length) return;
        var targets = Array.prototype.map.call(counters, function (c) {
            return parseInt(c.dataset.target, 10) || 0;
        });
        // Reserve lebar angka final SEBELUM count-up → label di sebelahnya tidak
        // terdorong bergeser selama angka bertambah (anti layout-shift).
        counters.forEach(function (c, i) {
            c.style.minWidth = Math.max(2, String(targets[i]).length) + 'ch';
        });
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            counters.forEach(function (c, i) { c.textContent = targets[i]; });
            return;
        }

        var DURATION = 1800;
        var t0 = performance.now();
        function frame(now) {
            var t = Math.min((now - t0) / DURATION, 1);
            var ease = 1 - (1 - t) * (1 - t);   // ease-out quad
            counters.forEach(function (c, i) { c.textContent = Math.round(targets[i] * ease); });
            if (t < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    })();

    // ===== Pola "list berganti" (Modul & Perpustakaan) =====================
    // Hover/klik/focus item → slide ber-index sama cross-fade 250ms.
    // Auto-rotate 4.5s; berhenti PERMANEN saat user berinteraksi.
    (function () {
        var AUTO_MS = 4500;
        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        document.querySelectorAll('[data-swap]').forEach(function (group) {
            var items  = group.querySelectorAll('[data-swap-item]');
            var slides = group.querySelectorAll('[data-swap-slide]');
            if (!items.length || !slides.length) return;

            var current = 0, timer = null;

            function show(i) {
                current = i;
                items.forEach(function (el, k) {
                    el.classList.toggle('is-active', k === i);
                    el.setAttribute('aria-selected', k === i ? 'true' : 'false');
                });
                slides.forEach(function (el, k) {
                    el.classList.toggle('is-active', k === i);
                    el.toggleAttribute('inert', k !== i);
                });
            }
            function stopAuto() { if (timer) { clearInterval(timer); timer = null; } }
            function startAuto() {
                if (reduced || timer) return;
                timer = setInterval(function () { show((current + 1) % items.length); }, AUTO_MS);
            }

            items.forEach(function (item, i) {
                item.addEventListener('mouseenter', function () { stopAuto(); show(i); });
                item.addEventListener('focus',       function () { stopAuto(); show(i); });
                item.addEventListener('click',       function () { stopAuto(); show(i); });
            });

            startAuto();
        });
    })();

    // ===== Kembali ke atas: tampil saat hero keluar viewport =====
    (function () {
        var btn  = document.getElementById('to-top');
        var hero = document.querySelector('main');
        if (!btn || !hero || !('IntersectionObserver' in window)) return;
        var io = new IntersectionObserver(function (entries) {
            var show = !entries[0].isIntersecting;
            btn.classList.toggle('is-show', show);
            btn.toggleAttribute('inert', !show);
        }, { threshold: 0 });
        io.observe(hero);
        btn.addEventListener('click', function () {
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
        });
    })();

    // ===== Rotator Berita (Blok A Kabar): cross-fade 250ms + auto 4.5s =====
    // Hover/focus = pause SEMENTARA, keluar = lanjut. Dot clickable = lompat.
    (function () {
        var root   = document.querySelector('[data-berita-rotator]');
        if (!root) return;
        var slides = root.querySelectorAll('[data-berita-slide]');
        var dots   = root.querySelectorAll('[data-berita-dot]');
        if (slides.length < 2) return;   // 1 berita → statis, tanpa rotator

        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var AUTO_MS = 4500, i = 0, timer = null;

        function show(n) {
            i = (n + slides.length) % slides.length;
            slides.forEach(function (el, k) {
                el.classList.toggle('is-active', k === i);
                el.toggleAttribute('inert', k !== i);
            });
            dots.forEach(function (el, k) {
                el.classList.toggle('is-active', k === i);
                el.setAttribute('aria-pressed', k === i ? 'true' : 'false');
            });
        }
        function stop()  { if (timer) { clearInterval(timer); timer = null; } }
        function start() { if (!reduced && !timer) timer = setInterval(function () { show(i + 1); }, AUTO_MS); }

        dots.forEach(function (d, k) {
            d.addEventListener('click', function () { show(k); start(); });
        });
        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);
        root.addEventListener('focusin',    stop);
        root.addEventListener('focusout',   start);

        show(0);
        start();
    })();

    // ===== Carousel foto Laboratorium PAI: cross-fade 1s + auto-loop 4s + dots =====
    // Hover/focus = pause sementara, keluar = lanjut. Dot clickable = lompat langsung.
    (function () {
        var root   = document.querySelector('[data-lab-rotator]');
        if (!root) return;
        var slides = root.querySelectorAll('[data-lab-slide]');
        var dots   = root.querySelectorAll('[data-lab-dot]');
        if (slides.length < 2) return;   // 1 foto (atau kurang) → statis, tanpa rotator

        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var AUTO_MS = 4000, i = 0, timer = null;

        function show(n) {
            i = (n + slides.length) % slides.length;
            slides.forEach(function (el, k) { el.classList.toggle('is-active', k === i); });
            dots.forEach(function (el, k) {
                el.classList.toggle('is-active', k === i);
                el.setAttribute('aria-pressed', k === i ? 'true' : 'false');
            });
        }
        function stop()  { if (timer) { clearInterval(timer); timer = null; } }
        function start() { if (!reduced && !timer) timer = setInterval(function () { show(i + 1); }, AUTO_MS); }

        dots.forEach(function (d, k) {
            d.addEventListener('click', function () { show(k); start(); });
        });
        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);
        root.addEventListener('focusin',    stop);
        root.addEventListener('focusout',   start);

        show(0);
        start();
    })();
</script>

</body>
</html>