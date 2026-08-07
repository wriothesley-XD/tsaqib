<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
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

        /* ===== Background hero: TIDAK pakai foto asli (belum ada aset foto di project),
           jadi pakai gradient hijau-emas on-brand + pola bintang 8 khas Islami (SVG, ringan).
           Kalau nanti ada foto asli (misal foto masjid sekolah / kegiatan FSI), tinggal ganti
           baris `background-image` di .hero-bg dengan url('{{ asset('images/hero-foto.jpg') }}')
           ditaruh SEBELUM gradient overlay-nya, biar teks tetap kebaca. ===== */
        .hero-bg{
            background-color:var(--ink);
            background-image:
                radial-gradient(circle at 15% 20%, rgba(1,121,95,.55), transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(201,166,107,.35), transparent 50%),
                linear-gradient(160deg, #0d3327 0%, #10140F 55%, #10140F 100%);
        }
        .hero-pattern{
            position:absolute;
            inset:0;
            opacity:.14;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='84' height='84' viewBox='0 0 84 84'%3E%3Cg fill='none' stroke='%23F7F5EF' stroke-width='1'%3E%3Cpath d='M42 4 L60 20 L78 4 M42 4 L24 20 L6 4 M42 80 L60 64 L78 80 M42 80 L24 64 L6 80 M4 42 L20 24 L4 6 M4 42 L20 60 L4 78 M80 42 L64 24 L80 6 M80 42 L64 60 L80 78' /%3E%3Ccircle cx='42' cy='42' r='16'/%3E%3C/g%3E%3C/svg%3E");
            background-size:84px 84px;
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

        /* ===== Carousel kartu ===== */
        .carousel-track{
            scroll-snap-type:x mandatory;
            scrollbar-width:none;
        }
        .carousel-track::-webkit-scrollbar{ display:none; }
        .carousel-card{
            scroll-snap-align:start;
            flex:0 0 auto;
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
            padding:18px;transition:transform .35s ease, box-shadow .35s ease;
            border:1px solid rgba(247,245,239,.12);
        }
        @media (min-width:1024px){ .card-face{ width:220px; height:320px; } }
        .card-face:hover{ transform:translateY(-8px); box-shadow:0 20px 40px -12px rgba(0,0,0,.5); }
        .card-face::after{
            content:'';position:absolute;inset:0;
            background:linear-gradient(180deg, transparent 35%, rgba(0,0,0,.75) 100%);
        }
        .card-face .card-icon,
        .card-face .card-label,
        .card-face .card-desc,
        .card-face .card-arrow{ position:relative; z-index:2; }

        .c-labor   { background:linear-gradient(155deg,#0f7a5c,#0a4a3a); }
        .c-perpus  { background:linear-gradient(155deg,#3f704d,#1f3a26); }
        .c-komunitas{ background:linear-gradient(155deg,#c9a66b,#8a6a3b); }
        .c-figma   { background:linear-gradient(155deg,#10140f,#01795f); }

        @media (prefers-reduced-motion: reduce){
            *{ transition-duration:.01ms !important; animation-duration:.01ms !important; }
        }
    </style>
</head>
<body class="antialiased bg-[#10140F]">

<div class="relative min-h-screen hero-bg overflow-hidden flex flex-col">
    <div class="hero-pattern pointer-events-none"></div>

    {{-- ================= NAVBAR SEDERHANA (khusus hero) ================= --}}
    <header class="relative z-20">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-5 flex items-center justify-between">

            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="brand-mark group-hover:scale-105 transition-transform">TS</div>
                <div class="leading-none">
                    <span class="block font-display font-extrabold text-base text-[var(--cream)] tracking-tight">TSAQIB</span>
                    <span class="block font-label text-[9px] text-[var(--gold)] font-bold tracking-widest uppercase mt-0.5">FSI SMAN 1 Bukittinggi</span>
                </div>
            </a>

            {{-- Navigasi utama (desktop) --}}
            <nav class="hidden lg:flex items-center gap-1 font-label text-xs font-semibold">
                <a href="{{ route('laboratorium.pai') }}" class="px-3.5 py-2 rounded-full text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition">Laboratorium PAI</a>
                <a href="{{ route('perpustakaan') }}" class="px-3.5 py-2 rounded-full text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition">Perpustakaan</a>
                <button type="button" onclick="handleKomunitasClick()" class="px-3.5 py-2 rounded-full text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition">Komunitas</button>
                <a href="{{ route('open.recruitment') }}" class="px-3.5 py-2 rounded-full text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition">Open Recruitment</a>
            </nav>

            {{-- Sosial media + login/profile --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="hidden sm:flex items-center gap-1.5">
                    {{-- TODO Rara: ganti href "#" dengan link akun sosmed FSI TSAQIB yang asli --}}
                    <a href="https://www.instagram.com/fsi.smansa_landbouw?igsh=MXVzMzd5Nms0eDZpNQ==

" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition" title="Instagram">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="https://ytfsi.carrd.co" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition" title="Youtube">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                    <a href="https://www.facebook.com/share/1BJMFJvK5k/" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition" title="Facebook">
                        <i class="fa-brands fa-facebook text-sm"></i>
                    </a>
                </div>

                <div class="w-px h-6 bg-white/15 hidden sm:block"></div>

                @auth
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 pl-1.5 pr-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/15 transition">
                        <x-community-avatar :user="Auth::user()" size="xs" />
                        <span class="text-xs font-label font-semibold text-[var(--cream)] hidden sm:inline">Profil</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-[var(--green)] hover:bg-[var(--green-dark)] text-white text-xs font-label font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-right-to-bracket text-[11px]"></i>
                        <span>Masuk</span>
                    </a>
                @endauth

                {{-- Toggle menu mobile --}}
                <button type="button" id="mobile-nav-btn" class="lg:hidden w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)] bg-white/10 hover:bg-white/20 transition">
                    <i class="fa-solid fa-bars text-sm" id="mobile-nav-icon"></i>
                </button>
            </div>
        </div>

        {{-- Drawer mobile --}}
        <div id="mobile-nav-menu" class="hidden lg:hidden max-w-7xl mx-auto px-5 pb-5 flex flex-col gap-1 font-label text-sm font-semibold">
            <a href="{{ route('laboratorium.pai') }}" class="px-4 py-2.5 rounded-xl text-[var(--cream)]/90 hover:bg-white/10 transition">Laboratorium PAI</a>
            <a href="{{ route('perpustakaan') }}" class="px-4 py-2.5 rounded-xl text-[var(--cream)]/90 hover:bg-white/10 transition">Perpustakaan</a>
            <button type="button" onclick="handleKomunitasClick()" class="text-left px-4 py-2.5 rounded-xl text-[var(--cream)]/90 hover:bg-white/10 transition">Komunitas</button>
            <a href="{{ route('open.recruitment') }}" class="px-4 py-2.5 rounded-xl text-[var(--cream)]/90 hover:bg-white/10 transition">Open Recruitment</a>
            <div class="flex items-center gap-1.5 px-4 pt-2">
                <a href="#" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-instagram text-sm"></i></a>
                <a href="#" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-tiktok text-sm"></i></a>
                <a href="#" target="_blank" rel="noopener" class="w-9 h-9 rounded-full flex items-center justify-center text-[var(--cream)]/80 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-whatsapp text-sm"></i></a>
            </div>
        </div>
    </header>

    {{-- ================= HERO CONTENT ================= --}}
    <main class="relative z-10 flex-1 max-w-7xl w-full mx-auto px-5 sm:px-8 flex flex-col lg:flex-row lg:items-center gap-10 lg:gap-6 py-8 lg:py-0">

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

            <p class="text-[var(--cream)]/70 text-sm sm:text-[15px] leading-relaxed mt-5 max-w-md">
                Wadah kaderisasi dan pengembangan diri siswa/i SMAN 1 Bukittinggi berbasis nilai-nilai
                keislaman &mdash; menghubungkan Laboratorium PAI, Perpustakaan Digital, dan komunitas
                minat &amp; bakat dalam satu ekosistem.
            </p>

            <div class="flex flex-wrap items-center gap-4 mt-8">
                <a href="{{ route('open.recruitment') }}" class="cta-primary inline-flex items-center gap-2.5 text-white font-label font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftar Jadi Anggota</span>
                </a>
                <a href="#program" class="inline-flex items-center gap-2 text-[var(--cream)]/80 hover:text-white font-label font-semibold text-xs sm:text-sm transition">
                    <span>Lihat Semua Program</span>
                    <i class="fa-solid fa-arrow-down text-[10px]"></i>
                </a>
            </div>
        </div>

        {{-- Kanan: carousel horizontal --}}
        <div id="program" class="lg:w-[54%] lg:pl-4">
            <div class="flex items-center justify-between mb-4 lg:mb-5">
                <h2 class="font-label text-[var(--cream)]/60 text-[11px] font-bold uppercase tracking-widest">
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

            <div class="relative">
                <div id="carousel-track" class="carousel-track flex gap-4 overflow-x-auto pb-3 -mx-1 px-1">

                    <a href="{{ route('laboratorium.pai') }}" class="carousel-card card-face c-labor">
                        <i class="card-icon fa-solid fa-flask text-2xl text-white/90 mb-3"></i>
                        <span class="card-label block font-display font-bold text-white text-lg leading-tight">Laboratorium<br>PAI</span>
                        <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Materi, riset, dan simulasi ibadah</span>
                        <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                            Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="{{ route('perpustakaan') }}" class="carousel-card card-face c-perpus">
                        <i class="card-icon fa-solid fa-book-open text-2xl text-white/90 mb-3"></i>
                        <span class="card-label block font-display font-bold text-white text-lg leading-tight">Perpustakaan<br>Digital</span>
                        <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Koleksi buku &amp; referensi FSI</span>
                        <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                            Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <button type="button" onclick="handleKomunitasClick()" class="carousel-card card-face c-komunitas text-left">
                        <i class="card-icon fa-solid fa-users text-2xl text-white/90 mb-3"></i>
                        <span class="card-label block font-display font-bold text-white text-lg leading-tight">Komunitas<br>TSAQIB</span>
                        <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">7 komunitas minat &amp; bakat</span>
                        <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                            Buka <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </button>

                    <a href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2"
                       target="_blank" rel="noopener noreferrer" class="carousel-card card-face c-figma">
                        <i class="card-icon fa-solid fa-diagram-project text-2xl text-white/90 mb-3"></i>
                        <span class="card-label block font-display font-bold text-white text-lg leading-tight">Prototype<br>TSAQIB</span>
                        <span class="card-desc block text-white/70 text-[11px] mt-1.5 leading-snug">Desain awal di Figma</span>
                        <span class="card-arrow flex items-center gap-1.5 text-white text-[11px] font-bold mt-3">
                            Lihat <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </span>
                    </a>

                </div>

                {{-- Page indicator --}}
                <div class="flex items-center justify-between mt-1">
                    <div class="flex sm:hidden items-center gap-2">
                        <button type="button" id="carousel-prev-mobile" class="carousel-nav-btn" aria-label="Sebelumnya">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" id="carousel-next-mobile" class="carousel-nav-btn" aria-label="Berikutnya">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                    <span class="ml-auto font-label text-[var(--cream)]/50 text-xs font-bold tracking-wider">
                        <span id="carousel-index">01</span> / 04
                    </span>
                </div>
            </div>
        </div>
    </main>

    <footer class="relative z-10 max-w-7xl w-full mx-auto px-5 sm:px-8 py-5 text-center lg:text-left">
        <p class="text-[var(--cream)]/40 text-[11px] font-label">
            &copy; {{ date('Y') }} TSAQIB &middot; Forum Studi Islam SMAN 1 Bukittinggi
        </p>
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
            window.location.href = "{{ route('login') }}";
        @endauth
    }

    // ===== Mobile nav drawer =====
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-nav-btn');
        const menu = document.getElementById('mobile-nav-menu');
        const icon = document.getElementById('mobile-nav-icon');
        if (btn && menu) {
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-xmark');
            });
        }

        // ===== Carousel: prev/next + page indicator =====
        const track = document.getElementById('carousel-track');
        const indexLabel = document.getElementById('carousel-index');
        const cards = track ? Array.from(track.children) : [];
        const totalCards = cards.length;

        function scrollByCard(direction) {
            if (!track || !cards.length) return;
            const cardWidth = cards[0].getBoundingClientRect().width + 16; // + gap-4
            track.scrollBy({ left: direction * cardWidth, behavior: 'smooth' });
        }

        ['carousel-prev', 'carousel-prev-mobile'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', () => scrollByCard(-1));
        });
        ['carousel-next', 'carousel-next-mobile'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', () => scrollByCard(1));
        });

        if (track && indexLabel && totalCards) {
            track.addEventListener('scroll', function () {
                const cardWidth = cards[0].getBoundingClientRect().width + 16;
                const current = Math.min(totalCards, Math.max(1, Math.round(track.scrollLeft / cardWidth) + 1));
                indexLabel.textContent = String(current).padStart(2, '0');
            }, { passive: true });
        }
    });
</script>

</body>
</html>
