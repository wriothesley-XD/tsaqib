<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sekolah Floating Island - TSAQIB SMAN 1 Bukittinggi</title>
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
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        'tsaqib-primary': '#01795F',
                        'tsaqib-dark': '#3F704D',
                    }
                }
            }
        }
    </script>
    <style>
        /* -----------------------------------------------------------
           MODULAR LAYER ANIMATIONS & SUBTLE IDLE EFFECTS
           ----------------------------------------------------------- */
        
        /* 1. Subtle Floating Idle Motion (Very slight, 4px max) */
        @keyframes floatSubtleIsland1 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        @keyframes floatSubtleIsland2 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3px); }
        }
        .idle-float-1 {
            animation: floatSubtleIsland1 5s ease-in-out infinite;
        }
        .idle-float-2 {
            animation: floatSubtleIsland2 4.5s ease-in-out infinite 0.8s;
        }

        /* 2. Soft Cloud Drift Animation */
        @keyframes cloudSoftDrift {
            0% { transform: translateX(-5%); }
            100% { transform: translateX(105%); }
        }
        .animate-cloud-drift {
            animation: cloudSoftDrift 45s linear infinite;
        }

        /* 3. Tiny Indicator Pulse */
        @keyframes tinyPulse {
            0%, 100% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.25); opacity: 1; box-shadow: 0 0 12px #01795F; }
        }
        .pulse-dot {
            animation: tinyPulse 2s ease-in-out infinite;
        }

        /* 4. Sequential Opening Animation (2-3s, Bottom-up Easing) */
        .seq-sky {
            opacity: 0;
            animation: fadeInSky 0.8s ease-out forwards;
        }
        .seq-clouds {
            opacity: 0;
            animation: fadeInSky 1.0s ease-out 0.2s forwards;
        }
        .seq-school {
            opacity: 0;
            transform: translateY(40px);
            animation: riseUpBottom 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
        }
        .seq-islands {
            opacity: 0;
            transform: translateY(60px);
            animation: riseUpBottom 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.7s forwards;
        }
        .seq-trees {
            opacity: 0;
            transform: translateY(40px);
            animation: riseUpBottom 1.0s cubic-bezier(0.16, 1, 0.3, 1) 1.0s forwards;
        }
        .seq-masjid {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
            animation: popUpMasjid 1.0s cubic-bezier(0.16, 1, 0.3, 1) 1.3s forwards;
        }
        .seq-indicator {
            opacity: 0;
            animation: fadeInSky 0.6s ease-out 1.8s forwards;
        }

        @keyframes fadeInSky {
            to { opacity: 1; }
        }
        @keyframes riseUpBottom {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes popUpMasjid {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-900 font-sans h-screen w-screen relative overflow-hidden select-none">

    <!-- NO NAVBAR ON LANDING PAGE AS SPECIFIED -->

    <!-- =========================================================
         LAYER 1: BACKGROUND LANGIT (SKY LAYER)
         ========================================================= -->
    <div id="layer-sky" class="seq-sky absolute inset-0 z-0 bg-gradient-to-b from-sky-100 via-sky-50 to-emerald-50"></div>

    <!-- =========================================================
         LAYER 2: AWAN (MULTIPLE CLOUD LAYERS)
         ========================================================= -->
    <div id="layer-clouds" class="seq-clouds absolute inset-0 z-10 pointer-events-none overflow-hidden">
        <div class="animate-cloud-drift absolute top-10 -left-40 opacity-50">
            <div class="w-72 h-16 bg-white rounded-full blur-md"></div>
        </div>
        <div class="animate-cloud-drift absolute top-24 -left-60 opacity-30" style="animation-duration: 65s;">
            <div class="w-96 h-20 bg-white rounded-full blur-lg"></div>
        </div>
    </div>

    <!-- =========================================================
         LAYER 3: BANGUNAN SEKOLAH (SCHOOL BACKDROP)
         ========================================================= -->
    <div id="layer-school" class="seq-school absolute inset-0 z-10 pointer-events-none flex items-center justify-center opacity-30">
        @php
            $hasBgAsset = file_exists(public_path('images/hires 1.png'));
        @endphp
        @if($hasBgAsset)
            <img src="{{ asset('images/hires 1.png') }}" alt="Latar Bangunan Sekolah" class="w-full h-full object-cover filter brightness-95">
        @else
            <div class="text-center opacity-40">
                <i class="fa-solid fa-school text-[160px] text-[#3F704D]"></i>
            </div>
        @endif
    </div>

    <!-- =========================================================
         MODULAR FLOATING ISLANDS CONTAINER (MAIN FOCUS)
         ========================================================= -->
    <div class="relative z-20 w-full h-full flex flex-col items-center justify-between p-6">

        <!-- Header Title (Clean, Subtle) -->
        <div class="seq-indicator text-center pt-4 z-30">
            <span class="inline-block px-3 py-1 rounded-full bg-white/80 border border-slate-200 text-[#01795F] text-xs font-semibold shadow-sm mb-1">
                Eksplorasi Dunia TSAQIB SMAN 1 Bukittinggi
            </span>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                Peta Sekolah Floating Island
            </h1>
        </div>

        <!-- TWO SEPARATE FLOATING ISLANDS CANVAS -->
        <div class="w-full max-w-5xl flex-1 relative flex flex-col md:flex-row items-center justify-center gap-12 sm:gap-20 py-4">

            <!-- =========================================================
                 PULAU 1: PULAU TSAQIB (DENGAN MASJID & TINY INDICATOR)
                 ========================================================= -->
            <div id="layer-island-tsaqib" class="seq-islands relative flex flex-col items-center">
                <div class="idle-float-1 relative flex flex-col items-center">

                    <!-- LAYER MASJID (OBJEK INTERAKTIF) -->
                    <div id="layer-masjid" class="seq-masjid relative z-30 flex flex-col items-center group cursor-pointer"
                         onclick="handleMasjidClick()">

                        <!-- TINY INDICATOR DOT WITH HOVER TOOLTIP (NO BIG BUTTONS) -->
                        <div class="seq-indicator absolute -top-3 z-40 relative group/tooltip">
                            <div class="w-4 h-4 rounded-full bg-[#01795F] border-2 border-white shadow-md pulse-dot"></div>
                            
                            <!-- ELEGANT TOOLTIP -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/tooltip:block group-hover:block transition duration-200 z-50">
                                <div class="bg-slate-900 text-white text-xs py-1.5 px-3 rounded-lg shadow-xl whitespace-nowrap text-center">
                                    <strong class="block text-amber-300 font-bold">Masjid TSAQIB</strong>
                                    <span class="text-[10px] text-slate-300">Masuk ke Komunitas TSAQIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- MASJID ICON / ASSET PLACEHOLDER -->
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white/90 border border-slate-200 shadow-lg flex flex-col items-center justify-center p-3 transition duration-200 group-hover:scale-105 group-hover:border-[#01795F]">
                            <i class="fa-solid fa-mosque text-4xl text-[#01795F]"></i>
                            <span class="text-[10px] font-bold text-slate-700 mt-1 uppercase">Masjid</span>
                        </div>
                    </div>

                    <!-- LAYER POHON & SUNGAI / ORNAMEN -->
                    <div id="layer-trees-decor" class="seq-trees mt-2 w-56 sm:w-72 h-20 sm:h-24 rounded-[50%] bg-[#3F704D] border-2 border-white shadow-lg relative flex items-center justify-center overflow-hidden">
                        <!-- Sungai Effect -->
                        <div class="absolute inset-x-0 h-3 bg-sky-400/40 top-1/2 -translate-y-1/2 rounded-full"></div>
                        <span class="text-[10px] font-bold text-white tracking-widest uppercase z-10">PULAU TSAQIB</span>
                    </div>

                </div>
            </div>

            <!-- =========================================================
                 PULAU 2: PULAU PERPUSTAKAAN (PULAU TERPISAH & TINY INDICATOR)
                 ========================================================= -->
            <div id="layer-island-perpus" class="seq-islands relative flex flex-col items-center">
                <div class="idle-float-2 relative flex flex-col items-center">

                    <!-- PULAU PERPUSTAKAAN (OBJEK INTERAKTIF -> DIRECT ACCESS) -->
                    <a href="{{ route('perpustakaan') }}"
                       class="relative z-30 flex flex-col items-center group cursor-pointer">

                        <!-- TINY INDICATOR DOT WITH HOVER TOOLTIP -->
                        <div class="seq-indicator absolute -top-3 z-40 relative group/tooltip">
                            <div class="w-4 h-4 rounded-full bg-[#3F704D] border-2 border-white shadow-md pulse-dot"></div>
                            
                            <!-- ELEGANT TOOLTIP -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/tooltip:block group-hover:block transition duration-200 z-50">
                                <div class="bg-slate-900 text-white text-xs py-1.5 px-3 rounded-lg shadow-xl whitespace-nowrap text-center">
                                    <strong class="block text-emerald-300 font-bold">Pulau Perpustakaan</strong>
                                    <span class="text-[10px] text-slate-300">Akses Perpustakaan Digital FSI</span>
                                </div>
                            </div>
                        </div>

                        <!-- PERPUSTAKAAN ICON / ASSET PLACEHOLDER -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/90 border border-slate-200 shadow-lg flex flex-col items-center justify-center p-3 transition duration-200 group-hover:scale-105 group-hover:border-[#3F704D]">
                            <i class="fa-solid fa-book-open-reader text-3xl text-[#3F704D]"></i>
                            <span class="text-[10px] font-bold text-slate-700 mt-1 uppercase">Maktabah</span>
                        </div>
                    </a>

                    <!-- LAYER ORNAMEN PULAU PERPUSTAKAAN -->
                    <div class="mt-2 w-48 sm:w-60 h-16 sm:h-20 rounded-[50%] bg-[#01795F] border-2 border-white shadow-lg relative flex items-center justify-center overflow-hidden">
                        <span class="text-[10px] font-bold text-white tracking-widest uppercase z-10">PULAU PERPUSTAKAAN</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Footer Footer Note -->
        <div class="seq-indicator pb-2 text-center z-30">
            <p class="text-[10px] text-slate-500 bg-white/80 px-3 py-1 rounded-full border border-slate-200">
                &copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi
            </p>
        </div>

    </div>

    <!-- SCRIPT LOGIKA MASJID CLICK & AUTH & ROLE PERSISTENCE CHECK -->
    <script>
        function handleMasjidClick() {
            @auth
                @if(Auth::user()->selected_community)
                    // Sudah login & sudah punya selected_community -> DIRECT KE KOMUNITAS
                    window.location.href = "{{ route('komunitas') }}";
                @else
                    // Sudah login & BELUM punya selected_community -> Diarahkan ke Select Role
                    window.location.href = "{{ route('select-role') }}";
                @endif
            @else
                // Guest -> Redirect ke Halaman Login/Register
                window.location.href = "{{ route('login') }}";
            @endauth
        }
    </script>

</body>
</html>
