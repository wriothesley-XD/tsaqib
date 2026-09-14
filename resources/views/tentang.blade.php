{{-- resources/views/tentang.blade.php — Halaman "Tentang Tsaqib" --}}
@php($pageTitle = 'Tentang TSAQIB - Portal Digitalisasi PAI & Media Dakwah SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col" style="background-color: var(--green-s0);">

    @include('partials.navbar')

    <main class="flex-1">

        {{-- ===== HERO: Split Layout 2 Kolom ===== --}}
        <section class="relative h-auto border-b border-white/10 overflow-hidden" style="background-color: var(--green-s1);">
            <div class="section-glow" style="--glow-x: 25%; --glow-y: 50%;"></div>
            <div class="pat-islami opacity-15"></div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center py-16 h-auto">

                    {{-- KIRI: Glow diam di tengah, logo melayang di atasnya --}}
                    <div class="relative flex items-center justify-center">
                        <style>
                            @keyframes tsaqibFloatIdle {
                                0%, 100% { transform: translateY(0px); }
                                50%      { transform: translateY(-12px); }
                            }
                            @keyframes tsaqibPulseGlow {
                                0%, 100% { opacity: 0.5; transform: scale(1); }
                                50%      { opacity: 0.85; transform: scale(1.1); }
                            }
                            @media (prefers-reduced-motion: reduce) {
                                .hero-logo-float, .hero-logo-glow { animation: none !important; }
                            }
                        </style>
                        <div class="hero-logo-glow absolute inset-0 -m-8 sm:-m-12 bg-yellow-500/25 blur-3xl rounded-full pointer-events-none" style="animation: tsaqibPulseGlow 3s ease-in-out infinite;" aria-hidden="true"></div>
                        <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt="Logo TSAQIB"
                             class="hero-logo-float relative w-48 sm:w-64 lg:w-80 h-auto object-contain drop-shadow-2xl select-none"
                             style="animation: tsaqibFloatIdle 3.5s ease-in-out infinite;">
                    </div>

                    {{-- KANAN: Teks rata kiri --}}
                    <div class="text-left space-y-5">
                        <span class="eyebrow-pill eyebrow-pill-gold">
                            <i class="fa-solid fa-circle-info text-[10px]"></i>
                            Tentang Kami
                        </span>
                        <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight">
                            Tentang TSAQIB
                        </h1>
                        <p class="text-lg sm:text-2xl font-display leading-relaxed text-shimmer">
                            Portal Digitalisasi PAI &amp; Media Dakwah Inklusif SMAN 1 Bukittinggi.
                        </p>
                        <p class="text-white/70 text-sm sm:text-base leading-relaxed">
                            Tsaqib berarti <strong class="text-white">tajam, menembus, bersinar terang, atau cerdas</strong>. Mengambil inspirasi dari QS. As-Saffat: 10 tentang <em class="text-[var(--gold)]">syihabun tsaqib</em> (cahaya/meteor yang menembus kegelapan), platform ini hadir untuk menuntun generasi muda memanfaatkan teknologi dalam ruang lingkup Islam.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===== 3 PILAR ===== --}}
        <section class="relative py-16 border-b border-white/10" style="background-color: var(--green-s0);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="eyebrow-pill eyebrow-pill-green">
                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                        Tiga Pilar Utama
                    </span>
                    <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-[var(--cream)] tracking-tight">
                        Apa yang kami lakukan?
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                    {{-- Pilar 1: BELAJAR --}}
                    <div class="tsaqib-card p-6 sm:p-7 relative overflow-hidden group hover:border-[#01795F] transition">
                        <div class="absolute top-0 right-0 w-28 h-28 bg-[#01795F]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold mb-4">
                            <i class="fa-solid fa-flask"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">BELAJAR.</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">Laboratorium PAI</strong>, siswa mempraktikkan ibadah, mengakses modul kurikulum resmi kelas X-XII, dan menyelesaikan penugasan terstruktur.
                        </p>
                    </div>

                    {{-- Pilar 2: BERKARYA --}}
                    <div class="tsaqib-card p-6 sm:p-7 relative overflow-hidden group hover:border-[var(--gold)] transition">
                        <div class="absolute top-0 right-0 w-28 h-28 bg-[var(--gold)]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="w-12 h-12 rounded-2xl bg-[var(--gold)]/20 border border-[var(--gold)]/35 flex items-center justify-center text-[var(--gold)] text-xl font-bold mb-4">
                            <i class="fa-solid fa-book-open"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">BERKARYA.</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">Perpustakaan Digital</strong>, siswa membaca ratusan buku islami, buletin dakwah gratis, serta menerbitkan risalah dan karya tulis mandiri.
                        </p>
                    </div>

                    {{-- Pilar 3: BERKOMUNITAS --}}
                    <div class="tsaqib-card p-6 sm:p-7 relative overflow-hidden group hover:border-[#3fd6b0] transition">
                        <div class="absolute top-0 right-0 w-28 h-28 bg-[#3fd6b0]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold mb-4">
                            <i class="fa-solid fa-users"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">BERKOMUNITAS.</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed">
                            Melalui <strong class="text-white">13 Circle Komunitas</strong>, setiap siswa menyalurkan minat &amp; bakat positif—dari hafalan Al-Qur'an, sains OSN, olahraga, hingga kreasi digital.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== TUJUAN KAMI ===== --}}
        <section class="relative py-16 border-b border-white/10 overflow-hidden" style="background-color: var(--green-s1);">
            <div class="section-glow" style="--glow-x: 50%; --glow-y: 30%;"></div>
            <div class="pat-islami opacity-15"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
                <span class="eyebrow-pill eyebrow-pill-gold">
                    <i class="fa-solid fa-bullseye text-[10px]"></i>
                    Tujuan Kami
                </span>
                <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-[var(--cream)] tracking-tight">
                    Visi Kami
                </h2>
                <blockquote class="relative font-display text-xl sm:text-3xl leading-relaxed text-shimmer font-semibold">
                    <i class="fa-solid fa-quote-left text-[var(--gold)]/40 text-2xl absolute -left-2 sm:-left-10 -top-4" aria-hidden="true"></i>
                    Membentuk generasi muslim yang kreatif, inovatif, dan cerdas berteknologi demi meraih ridho Allah SWT.
                    <i class="fa-solid fa-quote-right text-[var(--gold)]/40 text-2xl absolute -right-2 sm:-right-10 -bottom-4" aria-hidden="true"></i>
                </blockquote>
            </div>
        </section>

    </main>

    @include('partials.site-footer')

</body>
</html>
