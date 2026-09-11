<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi. Pusat kegiatan dakwah, Laboratorium PAI, Perpustakaan Digital, dan komunitas minat & bakat siswa.">
    <title>TSAQIB — Forum Studi Islam SMAN 1 Bukittinggi</title>
    @include('partials.theme-head')

    <style>
        /* Section specific subtle ambient lights */
        .hero-section {
            background-color: var(--green-s0);
            background-image:
                radial-gradient(1200px circle at 80% 20%, rgba(1, 121, 95, 0.15), transparent 70%),
                radial-gradient(800px circle at 15% 90%, rgba(201, 166, 107, 0.10), transparent 60%);
        }

        /* Carousel cards */
        .carousel-viewport { overflow: hidden; scrollbar-width: none; }
        .carousel-viewport::-webkit-scrollbar { display: none; }
        .carousel-track {
            display: flex;
            width: max-content;
            will-change: transform;
            cursor: grab;
            touch-action: pan-y;
            user-select: none;
        }
        .carousel-track:active { cursor: grabbing; }
        .carousel-set { display: flex; }
        .carousel-set > * { margin-right: 1.25rem; }

        .card-program {
            width: 205px;
            aspect-ratio: 4/5;
            border-radius: 1rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.25rem;
            color: var(--cream);
            background: var(--green-s2);
            border: 1px solid rgba(201, 166, 107, 0.25);
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.35s ease, box-shadow 0.35s ease;
            flex-shrink: 0;
        }
        @media (min-width: 1024px) {
            .card-program { width: 225px; }
        }
        .card-program:hover {
            transform: translateY(-6px);
            border-color: rgba(201, 166, 107, 0.6);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.7);
        }
        .card-program .card-photo {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .card-program:hover .card-photo {
            transform: scale(1.08);
        }
        .card-program::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(
                320px circle at var(--mouse-x, -999px) var(--mouse-y, -999px),
                rgba(201, 166, 107, 0.22),
                transparent 65%
            );
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 3;
        }
        .card-program:hover::before {
            opacity: 1;
        }
        .card-program::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13, 40, 24, 0.95) 0%, rgba(13, 40, 24, 0.5) 45%, transparent 100%);
        }
        .card-program-content {
            position: relative;
            z-index: 2;
        }

        /* Swap slide for interactive lists */
        .swap-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: opacity 0.25s ease-out, transform 0.25s ease-out;
        }
        .swap-slide.is-active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .swap-item {
            position: relative;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .swap-item .swap-bar {
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--gold);
            opacity: 0.2;
            border-radius: 999px;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .swap-item.is-active {
            background: rgba(201, 166, 107, 0.08);
        }
        .swap-item.is-active .swap-bar {
            opacity: 1;
            transform: scaleY(1.1);
        }

        /* Dots indicator */
        .slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(247, 245, 239, 0.3);
            border: 1px solid rgba(247, 245, 239, 0.2);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .slider-dot.is-active {
            background: var(--gold);
            border-color: var(--gold);
            transform: scale(1.3);
        }

        /* Lab photo carousel cross-fade */
        .lab-photo-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }
        .lab-photo-slide.is-active {
            opacity: 1;
        }

        /* Scroll reveal */
        .js-reveal .reveal:not(.is-visible) {
            opacity: 0;
            transform: translateY(18px);
        }
        .js-reveal .reveal {
            transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1), transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: calc(var(--reveal-i, 0) * 80ms);
        }

        /* Section Highlight on Role Selector Jump */
        .target-section-highlight {
            animation: pulse-border 1.8s cubic-bezier(0.22, 1, 0.36, 1);
        }
        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(201, 166, 107, 0.7); }
            50% { box-shadow: 0 0 0 16px rgba(201, 166, 107, 0); }
            100% { box-shadow: 0 0 0 0 rgba(201, 166, 107, 0); }
        }
    </style>
</head>
<body class="antialiased text-[var(--cream)] overflow-x-hidden">


    {{-- Global Unified Navbar --}}
    @include('partials.navbar')

    {{-- =========================================================================
       HERO SECTION — Modern, Bersih, Above-The-Fold
       ========================================================================= --}}
    <header class="relative hero-section py-12 lg:py-20 border-b border-white/10 overflow-hidden">
        {{-- Real FSI SMAN 1 Bukittinggi Monument Background Layer --}}
        <div class="absolute inset-0 z-0 pointer-events-none select-none">
            <picture>
                <source srcset="{{ asset('assets/landing/fsi.webp') }}" type="image/webp">
                <img src="{{ asset('assets/landing/fsi.jpg') }}" alt="Gerbang FSI SMAN 1 Bukittinggi"
                     class="w-full h-full object-cover object-[center_35%] transform scale-105 filter brightness-[0.70] contrast-105">
            </picture>
            {{-- Deep Luxurious Emerald Gradients (Assures 100% WCAG AAA Text Contrast) --}}
            <div class="absolute inset-0 bg-gradient-to-r from-[#07170E]/60 via-[#0D2818]/85 to-[#07170E]/95"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#07170E]/50 via-[#0D2818]/70 to-[#07170E]"></div>
            <div class="pat-islami opacity-10"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row lg:items-center justify-between gap-10 lg:gap-14">

            {{-- Kolom Kiri: Value Proposition Utama --}}
            <div class="lg:w-1/2 space-y-6">
                <div>
                    <span class="eyebrow-pill eyebrow-pill-green">
                        <i class="fa-solid fa-mosque text-[10px]"></i>
                        Forum Studi Islam &middot; SMAN 1 Bukittinggi
                    </span>
                </div>

                <div class="space-y-2">
                    <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl text-[var(--cream)] tracking-tight leading-[1.08]">
                        TSAQIB
                    </h1>
                    <p class="font-display font-semibold text-lg sm:text-xl text-shimmer tracking-tight">
                        Cerdas, Unggul, dan Berakhlak Mulia
                    </p>
                    <p class="text-xs sm:text-sm font-semibold tracking-wider text-[var(--gold)]">
                        Pusat Belajar PAI &middot; Ruang Tumbuh Siswa SMAN 1 Bukittinggi
                    </p>
                </div>

                <p class="text-white/80 text-sm sm:text-base leading-relaxed max-w-xl">
                    Platform digital resmi Forum Studi Islam (FSI) SMAN 1 Bukittinggi. Menghadirkan silabus riset
                    <strong class="text-[var(--cream)]">Laboratorium PAI</strong>, ratusan koleksi <strong class="text-[var(--cream)]">Perpustakaan Digital</strong>, serta ruang ukhuwah <strong class="text-[var(--cream)]">13 circle komunitas</strong> mandiri karya siswa.
                </p>

                {{-- Call To Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('register') }}" class="btn-gold">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Yuk, Gabung TSAQIB!</span>
                    </a>
                    <a href="{{ route('open.recruitment') }}" class="btn-outline">
                        <i class="fa-solid fa-users text-xs"></i>
                        <span>Daftar Jadi Anggota FSI</span>
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Program Showcase Carousel --}}
            <div class="lg:w-1/2">
                <div class="flex items-center justify-between mb-3 px-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/50">
                        Jelajahi Program Unggulan
                    </span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="carousel-prev" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 hover:border-[var(--gold)]/40 flex items-center justify-center text-xs text-white/70 hover:text-white transition" aria-label="Program Sebelumnya">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </button>
                        <button type="button" id="carousel-next" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 hover:border-[var(--gold)]/40 flex items-center justify-center text-xs text-white/70 hover:text-white transition" aria-label="Program Berikutnya">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <div class="relative carousel-viewport py-2">
                    <div id="carousel-track" class="carousel-track">
                        {{-- Set 1: Kartu Program Utama --}}
                        <div class="carousel-set">
                            {{-- Card 1: Laboratorium PAI --}}
                            <a href="{{ route('laboratorium.pai') }}" class="card-program group">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-labor.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="Laboratorium PAI" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2">
                                        <i class="fa-solid fa-flask"></i>
                                    </span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Laboratorium PAI</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Riset ibadah, modul PDF &amp; tugas siswa</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">
                                        Buka Portal <i class="fa-solid fa-arrow-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                                    </span>
                                </div>
                            </a>

                            {{-- Card 2: Perpustakaan Digital --}}
                            <a href="{{ route('perpustakaan') }}" class="card-program group">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-perpus.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="Perpustakaan Digital" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2">
                                        <i class="fa-solid fa-book-open"></i>
                                    </span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Perpustakaan Digital</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Koleksi kitab, buletin &amp; e-book Islami</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">
                                        Buka Koleksi <i class="fa-solid fa-arrow-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                                    </span>
                                </div>
                            </a>

                            {{-- Card 3: Komunitas TSAQIB --}}
                            <a href="{{ route('komunitas', 'semua') }}" class="card-program group">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-komunitas.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="Komunitas TSAQIB" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2">
                                        <i class="fa-solid fa-users"></i>
                                    </span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Komunitas TSAQIB</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Wadah minat, bakat &amp; mentoring circle</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">
                                        Lihat Circle <i class="fa-solid fa-arrow-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                                    </span>
                                </div>
                            </a>

                            {{-- Card 4: Prototype Figma --}}
                            <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2" target="_blank" rel="noopener noreferrer" class="card-program group">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-figma.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="Prototype Figma" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2">
                                        <i class="fa-brands fa-figma"></i>
                                    </span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Prototype TSAQIB</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Eksplorasi rancangan UI/UX di Figma</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">
                                        Buka Desain <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                    </span>
                                </div>
                            </a>
                        </div>

                        {{-- Set 2: Duplikat Identik untuk Infinite Loop --}}
                        <div class="carousel-set" aria-hidden="true">
                            <a href="{{ route('laboratorium.pai') }}" class="card-program group" tabindex="-1">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-labor.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-labor.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2"><i class="fa-solid fa-flask"></i></span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Laboratorium PAI</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Riset ibadah, modul PDF &amp; tugas siswa</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">Buka Portal <i class="fa-solid fa-arrow-right text-[8px]"></i></span>
                                </div>
                            </a>
                            <a href="{{ route('perpustakaan') }}" class="card-program group" tabindex="-1">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-perpus.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-perpus.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2"><i class="fa-solid fa-book-open"></i></span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Perpustakaan Digital</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Koleksi kitab, buletin &amp; e-book Islami</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">Buka Koleksi <i class="fa-solid fa-arrow-right text-[8px]"></i></span>
                                </div>
                            </a>
                            <a href="{{ route('komunitas', 'semua') }}" class="card-program group" tabindex="-1">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-komunitas.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-komunitas.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2"><i class="fa-solid fa-users"></i></span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Komunitas TSAQIB</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Wadah minat, bakat &amp; mentoring circle</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">Lihat Circle <i class="fa-solid fa-arrow-right text-[8px]"></i></span>
                                </div>
                            </a>
                            <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2" target="_blank" rel="noopener noreferrer" class="card-program group" tabindex="-1">
                                <picture>
                                    <source srcset="{{ asset('assets/landing/card-figma.webp') }}" type="image/webp">
                                    <img src="{{ asset('assets/landing/card-figma.jpg') }}" alt="" class="card-photo" loading="lazy" onerror="this.remove()">
                                </picture>
                                <div class="card-program-content">
                                    <span class="w-7 h-7 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center text-[var(--gold)] text-xs mb-2"><i class="fa-brands fa-figma"></i></span>
                                    <h3 class="font-display font-bold text-base text-[var(--cream)] leading-tight">Prototype TSAQIB</h3>
                                    <p class="text-[11px] text-white/65 mt-1 leading-snug">Eksplorasi rancangan UI/UX di Figma</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[var(--gold)] mt-2.5">Buka Desain <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- =========================================================================
       SCENE 03: "JADI, TSAQIB ITU APA?" (The Clarity Anchor / 5-Second Rule)
       ========================================================================= --}}
    <section id="tentang-tsaqib" class="relative py-14 sm:py-20 border-b border-white/10" style="background-color: var(--green-s1);">
        <div class="section-glow" style="--glow-x: 50%; --glow-y: 20%;"></div>
        <div class="pat-islami opacity-15"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="eyebrow-pill eyebrow-pill-gold">
                    <i class="fa-solid fa-compass text-[10px]"></i>
                    Orientasi Singkat &middot; 5 Detik Paham
                </span>
                <h2 class="font-display font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight">
                    Jadi, TSAQIB itu apa?
                </h2>
                <p class="text-base sm:text-2xl font-display text-shimmer leading-relaxed">
                    TSAQIB adalah <strong class="text-[var(--cream)] font-bold">ruang tumbuh siswa</strong> SMAN 1 Bukittinggi.
                </p>
                <p class="text-white/70 text-xs sm:text-sm leading-relaxed max-w-2xl mx-auto">
                    Bukan sekadar website profil sekolah biasa. Ini adalah ruang digital terpadu di mana siswa dibina karakternya, diasah potensinya, dan dihubungkan dalam ukhuwah yang nyata.
                </p>
            </div>

            {{-- Triad: BELAJAR -> BERKARYA -> BERKOMUNITAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                {{-- Pilar 1: BELAJAR --}}
                <div class="tsaqib-card p-6 sm:p-7 flex flex-col justify-between group hover:border-[#01795F] transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-[#01795F]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold">
                                <i class="fa-solid fa-flask"></i>
                            </span>
                            <span class="font-display font-black text-2xl sm:text-3xl text-white/10 group-hover:text-[#3fd6b0]/30 transition">01</span>
                        </div>
                        <span class="eyebrow-pill eyebrow-pill-green text-[10px] mb-2">Pilar Pertama</span>
                        <h3 class="font-display font-extrabold text-xl sm:text-2xl text-[var(--cream)] tracking-tight mt-2">
                            BELAJAR.
                        </h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">Laboratorium PAI</strong>, siswa mempraktikkan ibadah, mengakses modul kurikulum resmi kelas X-XII, dan menyelesaikan penugasan terstruktur.
                        </p>
                    </div>
                    <div class="pt-5 mt-4 border-t border-white/10 flex items-center justify-between">
                        <a href="#labor" class="text-xs font-bold text-[#3fd6b0] hover:underline inline-flex items-center gap-1.5">
                            Jelajahi Laboratorium <i class="fa-solid fa-arrow-down text-[10px]"></i>
                        </a>
                    </div>
                </div>

                {{-- Pilar 2: BERKARYA --}}
                <div class="tsaqib-card p-6 sm:p-7 flex flex-col justify-between group hover:border-[var(--gold)] transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-[var(--gold)]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="w-12 h-12 rounded-2xl bg-[var(--gold)]/20 border border-[var(--gold)]/35 flex items-center justify-center text-[var(--gold)] text-xl font-bold">
                                <i class="fa-solid fa-book-open"></i>
                            </span>
                            <span class="font-display font-black text-2xl sm:text-3xl text-white/10 group-hover:text-[var(--gold)]/30 transition">02</span>
                        </div>
                        <span class="eyebrow-pill eyebrow-pill-gold text-[10px] mb-2">Pilar Kedua</span>
                        <h3 class="font-display font-extrabold text-xl sm:text-2xl text-[var(--cream)] tracking-tight mt-2">
                            BERKARYA.
                        </h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">Perpustakaan Digital</strong>, siswa membaca ratusan buku islami, buletin dakwah gratis, serta menerbitkan risalah dan karya tulis mandiri.
                        </p>
                    </div>
                    <div class="pt-5 mt-4 border-t border-white/10 flex items-center justify-between">
                        <a href="#perpus" class="text-xs font-bold text-[var(--gold)] hover:underline inline-flex items-center gap-1.5">
                            Buka Perpustakaan <i class="fa-solid fa-arrow-down text-[10px]"></i>
                        </a>
                    </div>
                </div>

                {{-- Pilar 3: BERKOMUNITAS --}}
                <div class="tsaqib-card p-6 sm:p-7 flex flex-col justify-between group hover:border-[#3fd6b0] transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-[#3fd6b0]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold">
                                <i class="fa-solid fa-users"></i>
                            </span>
                            <span class="font-display font-black text-2xl sm:text-3xl text-white/10 group-hover:text-[#3fd6b0]/30 transition">03</span>
                        </div>
                        <span class="eyebrow-pill eyebrow-pill-green text-[10px] mb-2">Pilar Ketiga</span>
                        <h3 class="font-display font-extrabold text-xl sm:text-2xl text-[var(--cream)] tracking-tight mt-2">
                            BERKOMUNITAS.
                        </h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">13 Circle Komunitas</strong>, setiap siswa menyalurkan minat &amp; bakat positif—dari hafalan Al-Qur'an, sains OSN, olahraga, hingga kreasi digital.
                        </p>
                    </div>
                    <div class="pt-5 mt-4 border-t border-white/10 flex items-center justify-between">
                        <a href="#komunitas-preview" class="text-xs font-bold text-[#3fd6b0] hover:underline inline-flex items-center gap-1.5">
                            Lihat 13 Circle <i class="fa-solid fa-arrow-down text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================================
       SCENE 04: "PROVE IT: MEREKA ADALAH TSAQIB" (Visual Storytelling)
       ========================================================================= --}}
    <section id="prove-it" class="relative py-16 sm:py-24 border-b border-white/10 overflow-hidden" style="background-color: var(--green-s0);">
        <div class="section-glow" style="--glow-x: 90%; --glow-y: 50%;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-12">
            <div class="text-center max-w-2xl mx-auto">
                <span class="eyebrow-pill eyebrow-pill-gold">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    Kiprah Nyata Siswa SMAN 1 Bukittinggi
                </span>
                <h2 class="font-display font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight mt-3">
                    BUKTI BUKAN SEKADAR KATA.
                </h2>
                <p class="text-white/65 text-xs sm:text-sm mt-2">
                    Setiap hari di SMAN 1 Bukittinggi, mereka tidak hanya hadir untuk belajar—mereka berkarya dan membangun masa depan.
                </p>
            </div>

            {{-- 4 Story Beats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Beat 1 --}}
                <div class="group relative rounded-2xl overflow-hidden border border-white/10 aspect-[3/4] shadow-xl">
                    <picture>
                        <source srcset="{{ asset('assets/images/laboratorium/foto-1.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/images/laboratorium/foto-1.jpg') }}" alt="MEREKA BELAJAR"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter brightness-[0.75] contrast-105" loading="lazy">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#07170E] via-[#07170E]/40 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 flex flex-col justify-end">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#3fd6b0] mb-1">Fokus &middot; Keilmuan</span>
                        <h3 class="font-display font-black text-xl sm:text-2xl text-[var(--cream)] tracking-tight leading-none">
                            MEREKA BELAJAR.
                        </h3>
                        <p class="text-[11px] text-white/70 mt-2 leading-snug">
                            Mendalami esensi syariat, akidah, dan risalah PAI di Laboratorium.
                        </p>
                    </div>
                </div>

                {{-- Beat 2 --}}
                <div class="group relative rounded-2xl overflow-hidden border border-white/10 aspect-[3/4] shadow-xl">
                    <picture>
                        <source srcset="{{ asset('assets/landing/fsi.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/landing/fsi.jpg') }}" alt="MEREKA BERKARYA"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter brightness-[0.75] contrast-105" loading="lazy">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#07170E] via-[#07170E]/40 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 flex flex-col justify-end">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[var(--gold)] mb-1">Dedikasi &middot; Dakwah</span>
                        <h3 class="font-display font-black text-xl sm:text-2xl text-[var(--cream)] tracking-tight leading-none">
                            MEREKA BERKARYA.
                        </h3>
                        <p class="text-[11px] text-white/70 mt-2 leading-snug">
                            Menulis buletin warta, riset keilmuan, dan menyebarkan syiar kebaikan.
                        </p>
                    </div>
                </div>

                {{-- Beat 3 --}}
                <div class="group relative rounded-2xl overflow-hidden border border-white/10 aspect-[3/4] shadow-xl">
                    <picture>
                        <source srcset="{{ asset('assets/images/laboratorium/foto-2.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/images/laboratorium/foto-2.jpg') }}" alt="MEREKA BERKOMUNITAS"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter brightness-[0.75] contrast-105" loading="lazy">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#07170E] via-[#07170E]/40 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 flex flex-col justify-end">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#5fd3b0] mb-1">Ukhuwah &middot; Sinergi</span>
                        <h3 class="font-display font-black text-xl sm:text-2xl text-[var(--cream)] tracking-tight leading-none">
                            MEREKA BERKOMUNITAS.
                        </h3>
                        <p class="text-[11px] text-white/70 mt-2 leading-snug">
                            Saling menguatkan dalam 13 circle minat &amp; bakat yang penuh berkah.
                        </p>
                    </div>
                </div>

                {{-- Beat 4 --}}
                <div class="group relative rounded-2xl overflow-hidden border border-white/10 aspect-[3/4] shadow-xl">
                    <picture>
                        <source srcset="{{ asset('assets/landing/2.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/landing/2.jpg') }}" alt="MEREKA MEMBANGUN SESUATU"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter brightness-[0.75] contrast-105" loading="lazy">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#07170E] via-[#07170E]/40 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 flex flex-col justify-end">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-amber-300 mb-1">Kreasi Digital</span>
                        <h3 class="font-display font-black text-xl sm:text-2xl text-[var(--cream)] tracking-tight leading-none">
                            MEREKA MEMBANGUN SESUATU.
                        </h3>
                        <p class="text-[11px] text-white/70 mt-2 leading-snug">
                            Menciptakan sistem platform mandiri yang digunakan bersama di sekolah.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Climax Quote Callout --}}
            <div class="p-6 sm:p-8 rounded-2xl border border-[var(--gold)]/30 bg-gradient-to-r from-[rgba(201,166,107,0.12)] via-transparent to-[rgba(1,121,95,0.15)] text-center max-w-4xl mx-auto">
                <h3 class="font-display font-black text-2xl sm:text-4xl text-[var(--cream)] tracking-tight">
                    MEREKA ADALAH <span class="text-[var(--gold)]">TSAQIB</span>.
                </h3>
                <p class="text-xs sm:text-sm text-white/80 mt-2 max-w-2xl mx-auto leading-relaxed">
                    Generasi muda yang cerdas, unggul, dan berakhlak mulia. Bukan hanya penikmat perubahan, melainkan penggerak nyata di SMAN 1 Bukittinggi.
                </p>
            </div>
        </div>
    </section>

    {{-- =========================================================================
       SECTION 1: KABAR TERBARU (2 Blok Asimetris: Berita & Buletin)
       ========================================================================= --}}
    @if($beritaTerbaru->isNotEmpty() || $buletinTerbaru->isNotEmpty())
    <section id="kabar" class="relative py-16 sm:py-20 border-b border-white/10" style="background-color: var(--green-s0);">
        <div class="section-glow" style="--glow-x: 90%; --glow-y: 10%;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-end justify-between gap-4 mb-8">
                <div>
                    <p class="ed-eyebrow">Warta &amp; Terbitan</p>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[var(--cream)] tracking-tight mt-2">
                        KABAR TERBARU FSI
                    </h2>
                </div>
                <a href="{{ route('info') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-[var(--gold)] hover:underline">
                    Semua Kabar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-6 items-stretch">
                {{-- Blok A: Rotator Berita (2 Kolom) --}}
                @if($beritaTerbaru->isNotEmpty())
                <div class="lg:col-span-2 flex flex-col" data-berita-rotator>
                    <div class="relative aspect-[16/9] rounded-2xl overflow-hidden border border-white/10 bg-black/40 shadow-xl">
                        @foreach($beritaTerbaru as $item)
                            <a href="{{ $item['url'] }}" @if($item['target'] === '_blank') target="_blank" rel="noopener" @endif
                               class="swap-slide {{ $loop->first ? 'is-active' : '' }} group block"
                               data-berita-slide @unless($loop->first) inert @endunless>
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                         loading="lazy" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                                        <i class="fa-solid fa-newspaper text-5xl text-white/10"></i>
                                    </div>
                                @endif
                                <span class="absolute inset-0 bg-gradient-to-t from-[#0D2818] via-[#0D2818]/50 to-transparent"></span>

                                <span class="absolute top-4 left-4 sm:top-5 sm:left-5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[var(--gold)] text-[var(--green-s0)] shadow">
                                    Warta Utama
                                </span>

                                <span class="absolute inset-x-0 bottom-0 p-5 sm:p-7">
                                    <span class="block font-display font-bold text-lg sm:text-2xl text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                        {{ $item['title'] }}
                                    </span>
                                    <span class="flex items-center gap-2 text-xs text-white/60 mt-2 font-sans">
                                        <i class="fa-regular fa-calendar text-[var(--gold)] text-[11px]"></i>
                                        {{ $item['date']?->locale('id')->translatedFormat('d F Y') }}
                                        @if($item['author'])
                                            <span class="text-white/30">&middot;</span> {{ $item['author'] }}
                                        @endif
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </div>

                    @if($beritaTerbaru->count() > 1)
                    <div class="flex items-center justify-center gap-2 mt-4" data-berita-dots role="group" aria-label="Navigasi Berita">
                        @foreach($beritaTerbaru as $item)
                            <button type="button" data-berita-dot aria-label="Berita ke-{{ $loop->iteration }}"
                                    class="slider-dot {{ $loop->first ? 'is-active' : '' }}"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- Blok B: List Buletin Ringkas (1 Kolom) --}}
                @if($buletinTerbaru->isNotEmpty())
                <div class="tsaqib-card p-5 sm:p-6 flex flex-col {{ $beritaTerbaru->isNotEmpty() ? '' : 'lg:col-span-3' }}">
                    <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
                        <span class="eyebrow-pill eyebrow-pill-gold">
                            <i class="fa-solid fa-book-open text-[9px]"></i> Buletin Terkini
                        </span>
                        <a href="{{ route('info', ['tab' => 'buletin']) }}" class="text-[11px] font-bold text-[var(--gold)] hover:underline">
                            Semua <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>

                    <div class="space-y-4 flex-1">
                        @foreach($buletinTerbaru as $b)
                            <a href="{{ $b['url'] }}" @if($b['target'] === '_blank') target="_blank" rel="noopener" @endif
                               class="group flex items-center gap-3.5 pb-3 border-b border-white/5 last:border-0 hover:bg-white/[0.03] p-1.5 rounded-lg transition-colors">
                                @if($b['image'])
                                    <img src="{{ asset('storage/' . $b['image']) }}" alt="" class="w-11 h-14 object-cover rounded border border-white/15 shrink-0" loading="lazy" onerror="this.remove()">
                                @else
                                    <span class="w-11 h-14 rounded bg-white/5 border border-white/10 flex items-center justify-center text-[var(--gold)] text-sm shrink-0">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-xs sm:text-sm text-[var(--cream)] line-clamp-2 leading-snug group-hover:text-[var(--gold)] transition-colors">
                                        {{ $b['title'] }}
                                    </h3>
                                    <p class="text-[10px] text-white/50 mt-1">
                                        {{ $b['date']?->locale('id')->translatedFormat('d M Y') }}
                                        @if($b['author']) &middot; {{ $b['author'] }} @endif
                                    </p>
                                </div>
                                <i class="fa-solid fa-angle-right text-xs text-white/30 group-hover:text-[var(--gold)] group-hover:translate-x-0.5 transition"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- =========================================================================
       SECTION 2: SPOTLIGHT LABORATORIUM PAI (Bina Karakter, Modul & Tugas)
       ========================================================================= --}}
    @php
        $modulKelas = [
            'x' => [
                ['judul' => 'Fikih: Thaharah & Shalat Fardhu', 'guru' => 'Ust. Fauzi, S.Pd.'],
                ['judul' => 'Akidah Akhlak: Makna Rukun Iman', 'guru' => 'Usth. Rahma, S.Pd.I'],
                ['judul' => 'SKI: Dakwah Nabi Muhammad ﷺ', 'guru' => 'Ust. Yusuf, S.Ag.'],
                ['judul' => "Al-Qur'an & Hadits: Tilawah & Tajwid Dasar", 'guru' => 'Usth. Aini, S.Pd.I'],
            ],
            'xi' => [
                ['judul' => 'Fikih: Zakat, Infak & Sedekah', 'guru' => 'Ust. Fauzi, S.Pd.'],
                ['judul' => 'Akidah Akhlak: Akhlak kepada Sesama', 'guru' => 'Usth. Rahma, S.Pd.I'],
                ['judul' => 'SKI: Islam di Nusantara', 'guru' => 'Ust. Yusuf, S.Ag.'],
                ['judul' => "Al-Qur'an & Hadits: Kaidah Tafsir Dasar", 'guru' => 'Usth. Aini, S.Pd.I'],
            ],
            'xii' => [
                ['judul' => 'Fikih: Muamalah & Jual Beli', 'guru' => 'Ust. Fauzi, S.Pd.'],
                ['judul' => 'Akidah Akhlak: Meneladani Para Ulama', 'guru' => 'Usth. Rahma, S.Pd.I'],
                ['judul' => 'SKI: Islam Modern & Pembaruan', 'guru' => 'Ust. Yusuf, S.Ag.'],
                ['judul' => "Al-Qur'an & Hadits: Hadits Tematik Pilihan", 'guru' => 'Usth. Aini, S.Pd.I'],
            ],
        ];
        $kelasLabel = ['x' => 'Kelas X', 'xi' => 'Kelas XI', 'xii' => 'Kelas XII'];
    @endphp

    <section id="labor" class="relative py-16 sm:py-24 border-b border-white/10" style="background-color: var(--green-s1);">
        <div class="section-glow" style="--glow-x: 10%; --glow-y: 80%;"></div>
        <div class="pat-islami is-large"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16 sm:space-y-20">

            {{-- 1. Split 50:50: Penjelasan & Carousel Foto Laboratorium --}}
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                <div class="space-y-6">
                    <p class="ed-eyebrow">Laboratorium Pendidikan Agama Islam</p>
                    <h2 class="font-display font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight leading-[1.1]">
                        KAMI BINA KARAKTER, BUKAN SEKADAR HAFALAN
                    </h2>
                    <p class="text-white/75 text-sm sm:text-base leading-relaxed">
                        Laboratorium PAI adalah ruang perpaduan antara keilmuan Islam, pembinaan akhlak mulia, serta praktikum ibadah
                        yang kontekstual bagi seluruh siswa/i SMAN 1 Bukittinggi.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="vpoint">
                            <h4>Pusat Bina Karakter</h4>
                            <p class="mt-0.5">Praktikum ibadah, kepemimpinan Islam, dan pembiasaan adab harian.</p>
                        </div>
                        <div class="vpoint">
                            <h4>Modul Digital Terpadu</h4>
                            <p class="mt-0.5">Silabus dan materi pembelajaran kurikulum PAI yang mudah diakses.</p>
                        </div>
                        <div class="vpoint">
                            <h4>Kolaborasi Guru &amp; Siswa</h4>
                            <p class="mt-0.5">Penugasan tersistematisasi langsung melalui Google Classroom.</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('laboratorium.pai') }}#profil" class="btn-gold">
                            <span>Profil Lengkap Laboratorium</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Carousel Foto Laboratorium PAI --}}
                <div class="relative">
                    <div class="ph aspect-[4/3] sm:aspect-[16/11] rounded-2xl shadow-2xl overflow-hidden border border-[var(--gold)]/30" data-lab-rotator>
                        <picture>
                            <source srcset="{{ asset('assets/images/laboratorium/foto-1.webp') }}" type="image/webp">
                            <img src="{{ asset('assets/images/laboratorium/foto-1.jpg') }}" alt="Suasana Laboratorium PAI - 1" class="lab-photo-slide is-active" data-lab-slide loading="lazy" onerror="this.remove()">
                        </picture>
                        <picture>
                            <source srcset="{{ asset('assets/images/laboratorium/foto-2.webp') }}" type="image/webp">
                            <img src="{{ asset('assets/images/laboratorium/foto-2.jpg') }}" alt="Suasana Laboratorium PAI - 2" class="lab-photo-slide" data-lab-slide loading="lazy" onerror="this.remove()">
                        </picture>
                        <picture>
                            <source srcset="{{ asset('assets/images/laboratorium/foto-3.webp') }}" type="image/webp">
                            <img src="{{ asset('assets/images/laboratorium/foto-3.jpg') }}" alt="Suasana Laboratorium PAI - 3" class="lab-photo-slide" data-lab-slide loading="lazy" onerror="this.remove()">
                        </picture>

                        {{-- Dots --}}
                        <div class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-2 z-10" data-lab-dots role="group" aria-label="Pilih foto laboratorium">
                            <button type="button" data-lab-dot aria-label="Foto 1" class="slider-dot is-active"></button>
                            <button type="button" data-lab-dot aria-label="Foto 2" class="slider-dot"></button>
                            <button type="button" data-lab-dot aria-label="Foto 3" class="slider-dot"></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Modul Pembelajaran Interaktif (Swap per Tingkatan Kelas) --}}
            <div class="pt-4" data-swap="modul">
                <div class="flex items-end justify-between gap-4 mb-6">
                    <div>
                        <p class="ed-eyebrow">Khazanah Modul PAI</p>
                        <h3 class="font-display font-bold text-xl sm:text-3xl text-[var(--cream)] tracking-tight mt-2">
                            3 TINGKATAN, PULUHAN MODUL
                        </h3>
                    </div>
                    <a href="{{ route('laboratorium.pai') }}#modul" class="text-xs font-bold text-[var(--gold)] hover:underline">
                        Lihat Semua Modul <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="grid lg:grid-cols-2 gap-6 lg:gap-10 items-stretch">
                    {{-- List Kiri: Tombol Tingkatan Kelas --}}
                    <div class="space-y-2 flex flex-col justify-center" role="tablist" aria-label="Tingkatan Kelas">
                        @foreach($modulKelas as $kelasKey => $moduls)
                            <button type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    class="swap-item w-full text-left p-4 sm:p-5 rounded-xl border border-white/10 flex items-center gap-4 {{ $loop->first ? 'is-active' : '' }}"
                                    data-swap-item data-index="{{ $loop->index }}">
                                <span class="swap-bar"></span>
                                <span class="font-display font-extrabold text-[var(--gold)] text-sm tracking-wider uppercase w-20 shrink-0">
                                    {{ $kelasLabel[$kelasKey] }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <span class="block font-bold text-xs sm:text-sm text-[var(--cream)] truncate">
                                        {{ $moduls[0]['judul'] }}
                                    </span>
                                    <span class="block text-[11px] text-white/50 mt-0.5">
                                        4 Mapel: Fikih, Akidah Akhlak, SKI, Al-Qur'an &amp; Hadits
                                    </span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-xs text-[var(--gold)]"></i>
                            </button>
                        @endforeach
                    </div>

                    {{-- Preview Kanan: Card Preview Modul --}}
                    <div class="relative aspect-[16/10] sm:aspect-[16/9] lg:aspect-auto min-h-[220px]">
                        @foreach($modulKelas as $kelasKey => $moduls)
                            <div class="swap-slide h-full flex flex-col {{ $loop->first ? 'is-active' : '' }}" data-swap-slide data-index="{{ $loop->index }}">
                                <div class="ph flex-1 flex flex-col justify-between p-6">
                                    <div class="flex items-center justify-between">
                                        <span class="eyebrow-pill eyebrow-pill-green">
                                            Kurikulum {{ $kelasLabel[$kelasKey] }}
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="font-display font-bold text-lg sm:text-xl text-[var(--cream)]">
                                            Materi Praktikum &amp; Silabus {{ $kelasLabel[$kelasKey] }}
                                        </h4>
                                        <p class="text-xs text-white/70 line-clamp-2">
                                            {{ collect($moduls)->pluck('judul')->implode(' · ') }}
                                        </p>
                                    </div>
                                    <div class="pt-2 flex items-center gap-3">
                                        <a href="{{ route('laboratorium.pai') }}#modul" class="btn-gold py-2 px-4 text-xs">
                                            Unduh Silabus PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2.5 Highlight: Laboratorium PAI Unggulan (Social Proof) --}}
            <div class="tsaqib-card p-6 sm:p-8 grid sm:grid-cols-[minmax(0,320px)_1fr] gap-6 items-center border-[var(--gold)]/30 bg-gradient-to-r from-[rgba(1,121,95,0.12)] to-transparent">
                <a href="{{ $kunjunganVideo ? route('info.dokumentasi.show', $kunjunganVideo->slug) : route('laboratorium.pai') }}"
                   class="group relative block aspect-video rounded-xl overflow-hidden border border-[rgba(201,166,107,0.4)] bg-gradient-to-br from-[#1C442B] to-[#0D2818] shadow-xl"
                   aria-label="Tonton video kunjungan Laboratorium PAI">
                    @if($kunjunganVideo?->photos->first())
                        <img src="{{ asset('storage/' . $kunjunganVideo->photos->first()->image_path) }}" alt="{{ $kunjunganVideo->title }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.remove()">
                    @endif
                    <span class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></span>
                    <span class="absolute inset-0 flex items-center justify-center">
                        <span class="w-14 h-14 rounded-full bg-[var(--gold)]/90 text-[#10140F] flex items-center justify-center shadow-2xl transition-transform duration-200 group-hover:scale-110">
                            <i class="fa-solid fa-play text-base ml-0.5"></i>
                        </span>
                    </span>
                    @if($kunjunganVideo)
                        <span class="absolute bottom-2 left-2 right-2 text-[10px] font-bold text-white/85 truncate">{{ $kunjunganVideo->title }}</span>
                    @else
                        {{-- TODO: dummy — muncul otomatis begitu admin upload video kunjungan (kategori "Kunjungan & Studi Tiru") --}}
                        <span class="absolute bottom-2 left-2 right-2 text-[10px] font-bold text-white/60">Video kunjungan — segera hadir</span>
                    @endif
                </a>

                <div class="space-y-3 text-center sm:text-left">
                    <p class="ed-eyebrow">Laboratorium PAI Unggulan</p>
                    <h3 class="font-display font-extrabold text-xl sm:text-2xl text-[var(--cream)] tracking-tight leading-snug">
                        PUSAT INOVASI &amp; RUJUKAN STUDI TIRU
                    </h3>
                    <p class="text-white/70 text-xs sm:text-sm leading-relaxed">
                        Laboratorium PAI SMAN 1 Bukittinggi menjadi pusat inovasi dan rujukan studi tiru berbagai instansi. Terakreditasi serta aktif menerima kunjungan penilaian eksternal.
                    </p>
                    <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#01795F]/20 border border-[#01795F]/40 text-[11px] font-bold text-[#3fd6b0]">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Terakreditasi
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[rgba(201,166,107,0.14)] border border-[rgba(201,166,107,0.35)] text-[11px] font-bold text-[var(--gold)]">
                            <i class="fa-solid fa-video text-[10px]"></i>
                            {{-- TODO: fallback "4+" dummy — otomatis angka asli saat data kunjungan terisi --}}
                            {{ $kunjunganCount > 0 ? $kunjunganCount.'+ Kunjungan Studi Tiru' : '4+ Kunjungan Studi Tiru' }}
                        </span>
                    </div>
                    <div class="pt-1">
                        <a href="{{ route('laboratorium.pai') }}" class="btn-gold">
                            <span>Jelajahi Profil &amp; Dokumentasi Lab PAI</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. Callout Google Classroom (Tugas Siswa) --}}
            <div class="tsaqib-card p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6 border-[var(--gold)]/30 bg-gradient-to-r from-[rgba(201,166,107,0.1)] to-transparent">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[var(--gold)]/15 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] text-2xl shrink-0">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="flex-1 text-center sm:text-left space-y-1">
                    <h4 class="font-display font-bold text-lg text-[var(--cream)]">Info Pengumpulan Tugas Siswa</h4>
                    <p class="text-xs sm:text-sm text-white/70 leading-relaxed">
                        Tugas praktikum dan portofolio keislaman dikumpulkan terpusat melalui <strong class="text-[var(--gold)]">Google Classroom</strong> masing-masing guru pengampu PAI.
                    </p>
                </div>
                <a href="{{ route('laboratorium.pai') }}#tugas" class="btn-gold shrink-0">
                    <span>Lihat Rincian Tugas</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </section>

    {{-- =========================================================================
       SECTION 3: PERPUSTAKAAN DIGITAL (Preview Koleksi & Buku Terbaru)
       ========================================================================= --}}
    <section id="perpus" class="relative py-16 sm:py-24 border-b border-white/10" style="background-color: var(--green-s0);">
        <div class="section-glow" style="--glow-x: 85%; --glow-y: 90%;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" data-swap="perpus">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

                {{-- Preview Cover Kiri --}}
                <div class="order-2 lg:order-1 relative aspect-[4/5] max-w-sm mx-auto w-full">
                    @if($katalogPerpus->isNotEmpty())
                        @foreach($katalogPerpus->take(5) as $b)
                            <div class="swap-slide h-full flex flex-col {{ $loop->first ? 'is-active' : '' }}" data-swap-slide data-index="{{ $loop->index }}">
                                <div class="ph flex-1 overflow-hidden flex flex-col justify-end p-5 shadow-2xl">
                                    @if($b['image'])
                                        <img src="{{ asset('storage/' . $b['image']) }}" alt="{{ $b['title'] }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" onerror="this.remove()">
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                                            <i class="fa-solid fa-book-bookmark text-5xl text-[var(--gold)]/30"></i>
                                            <span class="text-xs font-display font-bold uppercase tracking-wider text-white/40">Katalog PAI</span>
                                        </div>
                                    @endif
                                    <span class="absolute inset-0 bg-gradient-to-t from-[#0D2818] via-[#0D2818]/60 to-transparent pointer-events-none"></span>

                                    <div class="relative z-10 space-y-2">
                                        <span class="eyebrow-pill eyebrow-pill-gold text-[10px]">
                                            {{ ucfirst($b['category']) }}
                                        </span>
                                        <h4 class="font-display font-bold text-base sm:text-lg text-[var(--cream)] leading-snug line-clamp-2">
                                            {{ $b['title'] }}
                                        </h4>
                                        <div class="flex items-center gap-2 pt-1">
                                            <a href="{{ $b['pdf'] ?? route('perpustakaan') }}" target="_blank" rel="noopener" class="btn-gold py-1.5 px-3.5 text-xs">
                                                <i class="fa-solid fa-book-open text-[10px]"></i> Baca Online
                                            </a>
                                            @if($b['pdf'])
                                                <a href="{{ $b['pdf'] }}" download class="btn-outline py-1.5 px-3 text-xs">
                                                    <i class="fa-solid fa-download text-[10px]"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="ph h-full flex flex-col items-center justify-center p-6 text-center">
                            <i class="fa-solid fa-book-bookmark text-4xl text-white/20 mb-3"></i>
                            <p class="text-xs text-white/50">Koleksi digital sedang disinkronisasi.</p>
                        </div>
                    @endif
                </div>

                {{-- List Interaktif Kanan --}}
                <div class="order-1 lg:order-2 space-y-6">
                    <div>
                        <p class="ed-eyebrow">Maktabah Digital Publik</p>
                        <h2 class="font-display font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight leading-[1.1] mt-2">
                            BACA DI MANA SAJA, UNDUH KAPAN SAJA
                        </h2>
                        <p class="text-white/75 text-sm sm:text-base leading-relaxed mt-4">
                            Koleksi buku digital, risalah Fikih, modul PAI, dan buletin dakwah yang dapat diakses publik tanpa hambatan login.
                        </p>
                    </div>

                    @if($katalogPerpus->isNotEmpty())
                        <div class="space-y-2 pt-2" role="tablist" aria-label="Daftar Buku">
                            @foreach($katalogPerpus->take(5) as $b)
                                <button type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        class="swap-item w-full text-left p-3.5 sm:p-4 rounded-xl border border-white/10 flex items-center gap-3.5 {{ $loop->first ? 'is-active' : '' }}"
                                        data-swap-item data-index="{{ $loop->index }}">
                                    <span class="swap-bar"></span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-bold text-xs sm:text-sm text-[var(--cream)] truncate">
                                            {{ $b['title'] }}
                                        </h3>
                                        <p class="text-[11px] text-white/50 mt-0.5">
                                            {{ ucfirst($b['category']) }} @if($b['author']) &middot; {{ $b['author'] }} @endif
                                        </p>
                                    </div>
                                    <i class="fa-solid fa-angle-right text-xs text-[var(--gold)]"></i>
                                </button>
                            @endforeach
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('perpustakaan') }}" class="btn-gold">
                                <span>Kunjungi Perpustakaan Penuh</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================================
       SECTION 4: 13 KOMUNITAS & KADERISASI FSI
       ========================================================================= --}}
    @if(!empty($daftarKomunitas))
    <section id="komunitas-preview" class="relative py-16 sm:py-24 border-b border-white/10" style="background-color: var(--green-s1);">
        <div class="section-glow" style="--glow-x: 20%; --glow-y: 20%;"></div>
        <div class="pat-islami"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="max-w-2xl">
                    <p class="ed-eyebrow">Keluarga Besar FSI &middot; Circle Minat &amp; Bakat</p>
                    <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-[var(--cream)] tracking-tight mt-2">
                        7 CIRCLE KOMUNITAS TSAQIB
                    </h2>
                    <p class="text-[var(--gold)] font-display text-sm sm:text-base font-semibold mt-2">
                        Kenapa komunitas? Karena berkembang tidak harus sendirian.
                    </p>
                    <p class="text-white/70 text-xs sm:text-sm mt-1 leading-relaxed">
                        Setiap minat memiliki tempat untuk bertumbuh. Dari tahfidz Al-Qur'an, sains OSN, olahraga, hingga gaming santai dan bahasa—temukan circle yang selaras dengan passion-mu dalam naungan ukhuwah.
                    </p>
                </div>
                <a href="{{ route('komunitas', 'semua') }}" class="btn-gold shrink-0 self-start sm:self-auto">
                    <span>Semua Komunitas</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Grid Kartu Komunitas Seragam & Harmonis --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach($daftarKomunitas as $k)
                    <div class="community-card p-5 space-y-4">
                        <div class="flex items-center gap-3.5">
                            <div class="community-logo-container">
                                <img src="{{ asset($k['image']) }}" alt="{{ $k['nama'] }}" loading="lazy" onerror="this.remove()">
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-display font-bold text-sm sm:text-base text-[var(--cream)] truncate">
                                    {{ $k['nama'] }}
                                </h3>
                                <p class="text-[10px] text-[var(--gold)] font-semibold uppercase tracking-wider mt-0.5">
                                    {{ $k['peran'] ?? 'Community Circle' }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--gold)]/80 block">Aktivitas Circle:</span>
                            <p class="text-xs text-white/70 leading-relaxed line-clamp-3">
                                {{ $k['deskripsi_singkat'] }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                            <span class="eyebrow-pill eyebrow-pill-green text-[9px] py-0.5 px-2">
                                Aktif
                            </span>
                            <a href="{{ route('komunitas', $k['slug']) }}" class="text-xs font-bold text-[var(--gold)] hover:underline inline-flex items-center gap-1">
                                Buka Feed <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Dua Jalur Masuk yang Jelas: Oprec vs Akun LMS --}}
            <div class="grid sm:grid-cols-2 gap-5 pt-4">
                <a href="{{ route('open.recruitment') }}" class="tsaqib-card-interactive p-6 flex items-start gap-4">
                    <span class="w-12 h-12 rounded-2xl bg-[var(--gold)]/15 border border-[var(--gold)]/35 flex items-center justify-center text-[var(--gold)] text-xl shrink-0">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-display font-bold text-base text-[var(--cream)]">Daftar Anggota FSI (Kelas X)</h3>
                        <p class="text-xs text-white/65 mt-1 leading-relaxed">
                            Pendaftaran kaderisasi resmi untuk bergabung dalam kepengurusan dan kegiatan FSI SMAN 1 Bukittinggi.
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-[var(--gold)] mt-2"></i>
                </a>

                <a href="{{ route('register') }}" class="tsaqib-card-interactive p-6 flex items-start gap-4">
                    <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/40 flex items-center justify-center text-[#3fd6b0] text-xl shrink-0">
                        <i class="fa-solid fa-laptop-code"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-display font-bold text-base text-[var(--cream)]">Buat Akun Portal TSAQIB</h3>
                        <p class="text-xs text-white/65 mt-1 leading-relaxed">
                            Akses modul pembelajaran, kumpulkan tugas PAI, dan ikuti linimasa interaktif komunitas digital.
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-[#3fd6b0] mt-2"></i>
                </a>
            </div>

        </div>
    </section>
    @endif
    </section>

    {{-- Global Site Footer --}}
    @include('partials.site-footer')

    {{-- Floating Back to Top Button --}}
    <button id="to-top" type="button" aria-label="Kembali ke atas"
            class="fixed bottom-6 right-6 z-[80] w-11 h-11 rounded-full flex items-center justify-center text-[var(--cream)] bg-[#0D2818]/90 border border-[var(--gold)]/50 shadow-2xl backdrop-blur-md hover:border-[var(--gold)] hover:scale-105 transition-all">
        <i class="fa-solid fa-arrow-up text-xs"></i>
    </button>

    {{-- =========================================================================
       CLIENT-SIDE SCRIPTS (Carousel, Counter, Swap Interactivity)
       ========================================================================= --}}
    <script>
        // 1. Program Carousel Infinite Loop + Drag
        (function () {
            const viewport = document.querySelector('.carousel-viewport');
            const track    = document.getElementById('carousel-track');
            if (!viewport || !track) return;

            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReduced) return;

            const AUTO_SPEED   = 0.5;
            const RESUME_DELAY = 1500;
            const SNAP_EASE    = 0.25;
            const CARD_GAP     = 20;

            const pitch = () => {
                const c = track.querySelector('.card-program');
                return c ? c.offsetWidth + CARD_GAP : 240;
            };
            let setWidth = 0;
            const measure = () => {
                const set = track.querySelector('.carousel-set');
                setWidth = set ? set.children.length * pitch() : 0;
            };
            measure();

            let pos = 0, mode = 'auto', target = 0, resumeAt = 0;
            const wrap = () => ((pos % setWidth) + setWidth) % setWidth;
            const apply = () => { track.style.transform = 'translate3d(' + (-wrap()) + 'px,0,0)'; };

            let last = performance.now();
            function tick(now) {
                const dt = Math.min(now - last, 100);
                last = now;
                if (mode === 'snap') {
                    pos += (target - pos) * SNAP_EASE;
                    if (Math.abs(target - pos) < 0.5) {
                        pos = target;
                        mode = 'auto';
                        resumeAt = now + RESUME_DELAY;
                    }
                } else if (mode === 'auto' && now >= resumeAt) {
                    pos += AUTO_SPEED * (dt / 16.7);
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

            window.addEventListener('resize', measure, { passive: true });
            requestAnimationFrame(tick);
        })();

        // 2. Live Number Counters Animation
        (function () {
            const counters = document.querySelectorAll('.counter');
            if (!counters.length) return;

            const targets = Array.from(counters).map(c => parseInt(c.dataset.target, 10) || 0);
            const DURATION = 1600;
            const t0 = performance.now();

            function frame(now) {
                const t = Math.min((now - t0) / DURATION, 1);
                const ease = 1 - (1 - t) * (1 - t);
                counters.forEach((c, i) => {
                    c.textContent = Math.round(targets[i] * ease);
                });
                if (t < 1) requestAnimationFrame(frame);
            }
            requestAnimationFrame(frame);
        })();

        // 3. Tab Swap Interactivity (Modul & Perpustakaan)
        (function () {
            const AUTO_MS = 4500;
            document.querySelectorAll('[data-swap]').forEach(group => {
                const items  = group.querySelectorAll('[data-swap-item]');
                const slides = group.querySelectorAll('[data-swap-slide]');
                if (!items.length || !slides.length) return;

                let current = 0, timer = null;
                function show(idx) {
                    current = idx;
                    items.forEach((el, k) => {
                        el.classList.toggle('is-active', k === idx);
                        el.setAttribute('aria-selected', k === idx ? 'true' : 'false');
                    });
                    slides.forEach((el, k) => {
                        el.classList.toggle('is-active', k === idx);
                    });
                }
                function stop() { if (timer) { clearInterval(timer); timer = null; } }
                function start() {
                    timer = setInterval(() => show((current + 1) % items.length), AUTO_MS);
                }

                items.forEach((item, i) => {
                    item.addEventListener('mouseenter', () => { stop(); show(i); });
                    item.addEventListener('click',      () => { stop(); show(i); });
                });
                start();
            });
        })();

        // 4. Lab Photos Carousel Cross-Fade
        (function () {
            const root = document.querySelector('[data-lab-rotator]');
            if (!root) return;
            const slides = root.querySelectorAll('[data-lab-slide]');
            const dots   = root.querySelectorAll('[data-lab-dot]');
            if (slides.length < 2) return;

            let current = 0, timer = null;
            function show(idx) {
                current = (idx + slides.length) % slides.length;
                slides.forEach((s, k) => s.classList.toggle('is-active', k === current));
                dots.forEach((d, k) => d.classList.toggle('is-active', k === current));
            }
            function start() { timer = setInterval(() => show(current + 1), 4000); }
            function stop()  { if (timer) clearInterval(timer); }

            dots.forEach((dot, k) => {
                dot.addEventListener('click', () => { stop(); show(k); start(); });
            });
            start();
        })();

        // 5. Berita Rotator (Blok A)
        (function () {
            const root = document.querySelector('[data-berita-rotator]');
            if (!root) return;
            const slides = root.querySelectorAll('[data-berita-slide]');
            const dots   = root.querySelectorAll('[data-berita-dot]');
            if (slides.length < 2) return;

            let current = 0, timer = null;
            function show(idx) {
                current = (idx + slides.length) % slides.length;
                slides.forEach((s, k) => s.classList.toggle('is-active', k === current));
                dots.forEach((d, k) => d.classList.toggle('is-active', k === current));
            }
            function start() { timer = setInterval(() => show(current + 1), 4500); }
            function stop()  { if (timer) clearInterval(timer); }

            dots.forEach((dot, k) => {
                dot.addEventListener('click', () => { stop(); show(k); start(); });
            });
            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);
            start();
        })();

        // 6. Back to Top Button
        (function () {
            const btn = document.getElementById('to-top');
            if (!btn) return;
            window.addEventListener('scroll', () => {
                btn.classList.toggle('is-show', window.scrollY > 400);
            }, { passive: true });
            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();

        // 7. Copy Hikmah Hadits
        window.copyHikmah = function (btn) {
            const text = btn.getAttribute('data-quote');
            if (!navigator.clipboard) {
                if (window.showToast) window.showToast('Kutipan hadits: ' + text, 'info');
                return;
            }
            navigator.clipboard.writeText(text).then(() => {
                if (window.showToast) {
                    window.showToast('Mutiara hadits berhasil disalin ke clipboard!', 'gold');
                } else {
                    alert('Mutiara hadits berhasil disalin!');
                }
            }).catch(() => {
                if (window.showToast) window.showToast('Gagal menyalin kutipan.', 'warning');
            });
        };

        // 8. Role Orientation Navigation Controller (Scene 02)
        (function () {
            const roleButtons = document.querySelectorAll('.role-btn[data-role-target]');
            if (!roleButtons.length) return;

            roleButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetSelector = btn.getAttribute('data-role-target');
                    if (!targetSelector) return;
                    const targetEl = document.querySelector(targetSelector);
                    if (!targetEl) return;

                    const navbar = document.querySelector('header.sticky');
                    const navOffset = navbar ? navbar.offsetHeight + 10 : 80;
                    const elPos = targetEl.getBoundingClientRect().top + window.pageYOffset - navOffset;

                    window.scrollTo({
                        top: elPos,
                        behavior: 'smooth'
                    });

                    // Add visual highlight pulse
                    targetEl.classList.remove('target-section-highlight');
                    void targetEl.offsetWidth; // Force reflow
                    targetEl.classList.add('target-section-highlight');
                    setTimeout(() => {
                        targetEl.classList.remove('target-section-highlight');
                    }, 2000);
                });
            });
        })();
    </script>
    {{-- Modal onboarding "Panduan TSAQIB" — auto-sekali + buka ulang via navbar. --}}
    @include('partials.intro-modal')

</body>
</html>