<!-- resources/views/beranda.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi</title>
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
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar (6 Menu Items) -->
    @include('partials.navbar')

    <!-- HERO SECTION (Clean, Minimal, Modern Exploration Vibe) -->
    <section class="py-12 sm:py-16 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider mb-3">
                Ekosistem TSAQIB SMAN 1 Bukittinggi
            </span>

            <h1 class="text-3xl sm:text-5xl font-bold text-slate-900 tracking-tight leading-tight mb-3">
                Selamat Datang di <span class="text-[#01795F]">Dunia TSAQIB</span>
            </h1>

            <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-6">
                Pusat kegiatan, ukhuwah, keilmuan Al-Qur'an, dan syiar kebaikan siswa SMAN 1 Bukittinggi. Silakan jelajahi 13 komunitas di bawah ini.
            </p>

            <div class="flex justify-center space-x-3">
                <a href="{{ route('komunitas') }}"
                   class="px-6 py-3 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                    <i class="fa-solid fa-users mr-1.5"></i>Buka Feed Komunitas
                </a>
                <a href="{{ route('open.recruitment') }}"
                   class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs transition border border-slate-200">
                    <i class="fa-solid fa-user-plus text-[#01795F] mr-1.5"></i>Open Recruitment
                </a>
            </div>

        </div>
    </section>

    <!-- MAIN CONTENT SECTIONS -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12 w-full">

        <!-- 1. PETA 13 KOMUNITAS (IKON-IKON DAPAT DIKLIK) -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-[#01795F] fa-compass text-[#01795F]"></i>
                        <span>Peta Eksplorasi 13 Komunitas</span>
                    </h2>
                    <p class="text-slate-500 text-xs mt-0.5">Klik salah satu ikon komunitas untuk menjelajahi aktivitasnya</p>
                </div>
            </div>

            <!-- Grid 13 Community Icons -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($daftarKomunitas as $k)
                    <a href="{{ route('komunitas', $k['slug']) }}"
                       class="group p-4 rounded-xl bg-slate-50 hover:bg-[#01795F]/5 border border-slate-200 hover:border-[#01795F] transition duration-200 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-[#01795F] text-xl font-bold mb-2 shadow-sm group-hover:scale-105 transition">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="font-bold text-xs text-slate-900 group-hover:text-[#01795F] transition truncate w-full">
                            {{ $k['nama'] }}
                        </h3>
                        <span class="text-[10px] text-slate-400 mt-0.5">Lihat Feed &rarr;</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 2. RECENT POSTS / AKTIVITAS TERBARU -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Postingan Activity Feed Terbaru</h2>
                    <p class="text-slate-500 text-xs mt-0.5">Diskusi & dokumentasi anggota FSI SMAN 1 Bukittinggi</p>
                </div>
                <a href="{{ route('komunitas') }}" class="text-xs font-semibold text-[#01795F] hover:underline">
                    Lihat Semua Feed &rarr;
                </a>
            </div>

            @if(isset($posts) && count($posts) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($posts as $post)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 mb-2">
                                    <span class="font-bold text-[#01795F] uppercase">{{ $post->community_slug }}</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="font-bold text-sm text-slate-900 mb-1 leading-snug">{{ $post->title }}</h4>
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $post->content }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center space-x-2 text-xs text-slate-500">
                                <i class="fa-solid fa-user-circle text-slate-400"></i>
                                <span>{{ $post->user->name ?? 'Anggota TSAQIB' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400 text-xs">
                    Belum ada postingan terbaru. Jadilah yang pertama membuat postingan di halaman Komunitas!
                </div>
            @endif
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
