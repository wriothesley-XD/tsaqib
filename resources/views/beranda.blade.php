{{-- resources/views/beranda.blade.php --}}
@php($pageTitle = 'Beranda TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <!-- HERO SECTION -->
    <section class="py-12 sm:py-16 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <span class="eyebrow-pill eyebrow-pill-green mb-3">
                <i class="fa-solid fa-compass text-[10px]"></i>
                Ekosistem TSAQIB SMAN 1 Bukittinggi
            </span>

            <h1 class="text-3xl sm:text-5xl font-display font-extrabold text-[var(--cream)] tracking-tight leading-tight mb-3 mt-4">
                Selamat Datang di <span class="text-[var(--gold)]">Dunia TSAQIB</span>
            </h1>

            <p class="text-white/60 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-6">
                Pusat kegiatan, ukhuwah, keilmuan Al-Qur'an, dan syiar kebaikan siswa SMAN 1 Bukittinggi. Silakan jelajahi 13 komunitas di bawah ini.
            </p>

            <div class="flex justify-center space-x-3">
                <a href="{{ route('komunitas') }}"
                   class="cta-primary inline-flex items-center gap-2 text-white font-label font-bold text-xs px-6 py-3 rounded-full">
                    <i class="fa-solid fa-users text-xs"></i>Buka Feed Komunitas
                </a>
                <a href="{{ route('open.recruitment') }}"
                   class="px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/15 text-[var(--cream)] font-label font-bold text-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-[var(--gold)] text-xs"></i>Open Recruitment
                </a>
            </div>

        </div>
    </section>

    <!-- MAIN CONTENT SECTIONS -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12 w-full">

        <!-- 1. PETA 13 KOMUNITAS (IKON-IKON DAPAT DIKLIK) -->
        <section class="tsaqib-card p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div>
                    <h2 class="text-xl font-display font-bold text-[var(--cream)] flex items-center space-x-2">
                        <i class="fa-solid fa-compass text-[var(--gold)]"></i>
                        <span>Peta Eksplorasi 13 Komunitas</span>
                    </h2>
                    <p class="text-white/50 text-xs mt-0.5">Klik salah satu ikon komunitas untuk menjelajahi aktivitasnya</p>
                </div>
            </div>

            <!-- Grid 13 Community Icons -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($daftarKomunitas as $k)
                    <a href="{{ route('komunitas', $k['slug']) }}"
                       class="group p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-[#01795F] transition duration-200 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-[#3fd6b0] text-xl font-bold mb-2 group-hover:scale-105 transition">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="font-bold text-xs text-[var(--cream)] group-hover:text-[var(--gold)] transition truncate w-full">
                            {{ $k['nama'] }}
                        </h3>
                        <span class="text-[10px] text-white/40 mt-0.5">Lihat Feed &rarr;</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 2. RECENT POSTS / AKTIVITAS TERBARU -->
        <section class="tsaqib-card p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div>
                    <h2 class="text-xl font-display font-bold text-[var(--cream)]">Postingan Activity Feed Terbaru</h2>
                    <p class="text-white/50 text-xs mt-0.5">Diskusi & dokumentasi anggota FSI SMAN 1 Bukittinggi</p>
                </div>
                <a href="{{ route('komunitas') }}" class="text-xs font-semibold text-[var(--gold)] hover:underline">
                    Lihat Semua Feed &rarr;
                </a>
            </div>

            @if(isset($posts) && count($posts) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($posts as $post)
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-[10px] text-white/40 mb-2">
                                    <span class="font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="font-bold text-sm text-[var(--cream)] mb-1 leading-snug">{{ $post->title }}</h4>
                                <p class="text-xs text-white/60 line-clamp-3 leading-relaxed">{{ $post->content }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-white/10 flex items-center space-x-2 text-xs text-white/50">
                                <i class="fa-solid fa-user-circle text-white/40"></i>
                                <span>{{ $post->user->name ?? 'Anggota TSAQIB' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-white/40 text-xs">
                    Belum ada postingan terbaru. Jadilah yang pertama membuat postingan di halaman Komunitas!
                </div>
            @endif
        </section>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
