{{-- Sub-halaman Laboratorium PAI: Modul Pembelajaran (PDF).
     Layout editorial left-aligned — breadcrumb kiri atas, header split
     (judul kiri / search kanan), tab underline, 2 grup filter berlabel,
     kartu aksen garis emas. Filter client-side (_labor-scripts). --}}
@php($pageTitle = 'Modul Pembelajaran - Laboratorium PAI')
@push('styles')@include('tsaqib._labor-styles')@endpush
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Modul pembelajaran PDF Laboratorium PAI — filter per kelas dan kategori.">
    <title>{{ $pageTitle }}</title>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full">

        @unless($verified)
            {{-- ===== Gate: belum terverifikasi ===== --}}
            <div class="tsaqib-card-flat p-12 text-center">
                <i class="fa-solid fa-lock text-3xl text-white/15 block mb-3"></i>
                <p class="text-white/60 text-sm font-semibold">Akun kamu belum terverifikasi sebagai siswa.</p>
                <p class="text-white/45 text-xs mt-1.5">Verifikasi NISN untuk membuka semua modul pembelajaran.</p>
                <a href="{{ auth()->check() ? route('verifikasi.siswa.form') : route('login') }}"
                   class="cta-primary inline-flex items-center gap-1.5 mt-5 text-white font-bold text-xs px-5 py-2.5 rounded-full">
                    <i class="fa-solid fa-id-card text-[10px]"></i>
                    {{ auth()->check() ? 'Verifikasi Sekarang' : 'Login Dulu' }}
                </a>
            </div>
        @else
        <div data-filter-root>

            {{-- ===== 1. Breadcrumb (pojok kiri atas, teks kecil — bukan badge pill) ===== --}}
            <nav class="flex items-center gap-1.5 text-[10px]" aria-label="Breadcrumb">
                <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
                <i class="fa-solid fa-chevron-right text-[7px] text-white/25" aria-hidden="true"></i>
                <span class="font-semibold text-white/65">Modul Pembelajaran</span>
            </nav>

            {{-- ===== 2. Header row: judul kiri (uppercase display, pola Bina Karakter) + search kanan ===== --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mt-4">
                <div class="reveal" style="--reveal-i:0;">
                    <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.08] tracking-tight uppercase">
                        Modul Pembelajaran
                    </h1>
                    <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2.5 max-w-xl">
                        Kumpulan modul PDF Pendidikan Agama Islam — baca langsung di browser, unduh bila perlu.
                    </p>
                </div>

                <label class="relative shrink-0 sm:mb-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[10px] text-white/35" aria-hidden="true"></i>
                    <input type="search" data-filter-search placeholder="Cari judul modul…"
                           class="tsaqib-input w-full sm:w-64 pl-9 pr-3 py-2 text-xs">
                </label>
            </div>

            {{-- ===== 3. Tab navigasi 3 halaman — underline kecil, rata kiri ===== --}}
            <div class="mt-6 reveal" style="--reveal-i:1;">
                @include('tsaqib._labor-subnav', ['active' => 'modul'])
            </div>

            {{-- ===== 4. Dua grup filter berlabel, rata kiri ===== --}}
            <div class="flex flex-wrap items-start gap-x-10 gap-y-5 mt-7 reveal" style="--reveal-i:2;">
                <div>
                    <p class="grp-label mb-2">Filter Kelas</p>
                    <div class="flex items-center gap-5 border-b border-white/10" role="group" aria-label="Filter kelas">
                        @foreach(['all' => 'Semua', 'X' => 'X', 'XI' => 'XI', 'XII' => 'XII'] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kelas" data-value="{{ $val }}"
                                    class="u-tab {{ $loop->first ? 'is-active' : '' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="grp-label mb-2">Filter Kategori</p>
                    <div class="flex flex-wrap gap-2" role="group" aria-label="Filter kategori">
                        @foreach(['all' => 'Semua', "Aqidah" => "Aqidah", "Fiqih" => "Fiqih", "Al-Qur'an" => "Al-Qur'an", "SKI" => "SKI", "Akhlak" => "Akhlak", "Praktikum" => "Praktikum"] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kategori" data-value="{{ $val }}"
                                    class="labor-chip {{ $loop->first ? 'is-active' : '' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-white/40 mt-6 reveal" style="--reveal-i:3;">
                Menampilkan <span data-filter-count class="font-bold text-[var(--gold)]">{{ $moduls->count() }}</span> modul.
            </p>

            {{-- ===== 5. Grid hasil — kartu aksen garis emas kiri (bukan kotak simetris) ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                @foreach($moduls as $modul)
                    <button type="button"
                            data-filter-item
                            data-kelas="{{ $modul->target_kelas }}"
                            data-kategori="{{ $modul->kategori }}"
                            data-search-text="{{ strtolower($modul->judul . ' ' . ($modul->kategori ?? '') . ' ' . ($modul->user?->name ?? '')) }}"
                            data-pdf-src="{{ asset('storage/' . $modul->file_path) }}"
                            data-pdf-title="{{ $modul->judul }}"
                            class="labor-modul-card reveal tsaqib-card-flat text-left cursor-pointer group flex items-center gap-4 p-5"
                            style="--reveal-i:{{ $loop->index % 6 }};">
                        <span class="shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-xl text-[var(--gold)] bg-[rgba(201,166,107,0.12)] border border-[rgba(201,166,107,0.3)] transition-transform duration-300 group-hover:scale-105">
                            <i class="fa-solid fa-file-pdf text-lg" aria-hidden="true"></i>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block font-display font-bold text-[14px] text-[var(--cream)] leading-snug line-clamp-1">{{ $modul->judul }}</span>
                            <span class="block text-[10px] text-white/40 mt-1 truncate">{{ $modul->user?->name ?? 'Guru PAI' }}</span>
                            <span class="flex items-center gap-1.5 mt-2 flex-wrap">
                                @if($modul->kategori)
                                    <span class="px-2 py-0.5 rounded-full border text-[9px] font-bold uppercase tracking-wider bg-[rgba(201,166,107,0.16)] text-[var(--gold)] border-[rgba(201,166,107,0.3)]">{{ $modul->kategori }}</span>
                                @endif
                                @if($modul->target_kelas)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">Kelas {{ $modul->target_kelas }}</span>
                                @endif
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-white/25 group-hover:text-[var(--gold)] transition shrink-0" aria-hidden="true"></i>
                    </button>
                @endforeach
            </div>

            {{-- Empty state kombinasi filter --}}
            <div data-filter-empty class="hidden tsaqib-card-flat p-12 text-center mt-4">
                <i class="fa-solid fa-folder-open text-3xl text-white/15 block mb-3"></i>
                <p class="text-white/45 text-xs">Belum ada modul untuk kategori/kelas ini.</p>
            </div>

        </div>
        @endunless

    </main>

    {{-- ===== Modal preview PDF (viewer native browser) + unduh ===== --}}
    <div id="pdf-modal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Pratinjau modul PDF">
        <div class="absolute inset-x-0 top-0 flex items-center justify-between gap-3 px-4 py-3 bg-black/40">
            <p id="pdf-modal-title" class="text-xs font-bold text-[var(--cream)] truncate"></p>
            <div class="flex items-center gap-2 shrink-0">
                <a id="pdf-modal-download" href="#" download
                   class="inline-flex items-center gap-1.5 text-[11px] font-bold text-[#3fd6b0] hover:text-[var(--gold)] transition cursor-pointer px-3 py-1.5 rounded-full border border-white/10">
                    <i class="fa-solid fa-download text-[10px]"></i> Unduh
                </a>
                <button type="button" data-pdf-close
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white cursor-pointer transition" aria-label="Tutup pratinjau">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <iframe id="pdf-modal-frame" src="" title="Pratinjau modul PDF"
                class="absolute inset-x-0 top-[52px] bottom-0 w-full h-[calc(100%-52px)] bg-white"></iframe>
    </div>

    @include('tsaqib._labor-scripts')

    {{-- Modal PDF + klik kartu --}}
    <script>
    (function () {
        var modal    = document.getElementById('pdf-modal');
        var frame    = document.getElementById('pdf-modal-frame');
        var titleEl  = document.getElementById('pdf-modal-title');
        var download = document.getElementById('pdf-modal-download');
        if (!modal || !frame) return;

        function open(src, title) {
            titleEl.textContent = title;
            download.href = src;
            frame.src = src;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function close() {
            frame.src = ''; // hentikan loading PDF
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('[data-pdf-src]').forEach(function (card) {
            card.addEventListener('click', function () {
                open(card.dataset.pdfSrc, card.dataset.pdfTitle);
            });
        });
        modal.querySelector('[data-pdf-close]').addEventListener('click', close);
        modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
        });
    })();
    </script>

</body>
</html>
