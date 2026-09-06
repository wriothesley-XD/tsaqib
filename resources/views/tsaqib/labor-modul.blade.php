{{-- Sub-halaman Laboratorium PAI: Modul Pembelajaran (PDF). --}}
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

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full space-y-8">

        @unless($verified)
            {{-- ===== Gate: belum terverifikasi ===== --}}
            <nav class="flex items-center gap-1.5 text-[11px]" aria-label="Breadcrumb">
                <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
                <i class="fa-solid fa-chevron-right text-[8px] text-white/30" aria-hidden="true"></i>
                <span class="font-semibold text-white/70">Modul Pembelajaran</span>
            </nav>

            <div class="tsaqib-card p-8 sm:p-12 text-center max-w-xl mx-auto my-12 rounded-3xl relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-[var(--gold)]/15 border border-[var(--gold)]/30 text-[var(--gold)] flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <span class="eyebrow-pill eyebrow-pill-gold mb-3 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card text-[10px]"></i>
                    Akses Khusus Siswa &amp; Pengajar
                </span>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-[var(--cream)] mb-2 mt-2">
                    Akses Modul Terbatas
                </h2>
                <p class="text-white/60 text-xs sm:text-sm leading-relaxed max-w-md mx-auto">
                    Koleksi modul dan lembar kerja praktikum PAI hanya dapat diakses oleh siswa/i terverifikasi SMAN 1 Bukittinggi atau dewan guru.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ auth()->check() ? route('verifikasi.siswa.form') : route('login') }}"
                       class="btn-gold text-xs px-6 py-3 w-full sm:w-auto">
                        <i class="fa-solid fa-id-badge"></i>
                        <span>{{ auth()->check() ? 'Verifikasi NISN Sekarang' : 'Masuk ke Akun' }}</span>
                    </a>
                    <a href="{{ route('laboratorium.pai') }}"
                       class="btn-outline text-xs px-6 py-3 w-full sm:w-auto">
                        <span>Kembali ke Ikhtisar</span>
                    </a>
                </div>
            </div>
        @else
        <div data-filter-root class="space-y-8">

            {{-- ===== 1. Breadcrumb ===== --}}
            <nav class="flex items-center gap-1.5 text-[11px]" aria-label="Breadcrumb">
                <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
                <i class="fa-solid fa-chevron-right text-[8px] text-white/30" aria-hidden="true"></i>
                <span class="font-semibold text-white/70">Modul Pembelajaran</span>
            </nav>

            {{-- ===== 2. Header row: judul kiri + search kanan ===== --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                <div class="reveal" style="--reveal-i:0;">
                    <span class="eyebrow-pill eyebrow-pill-green mb-3 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-book text-[10px]"></i>
                        Bahan Ajar &amp; Praktikum
                    </span>
                    <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.1] tracking-tight uppercase mt-1">
                        Modul <span class="text-[var(--gold)]">Pembelajaran</span>
                    </h1>
                    <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2 max-w-xl">
                        Kumpulan modul kurikulum PAI digital — telaah langsung di peramban atau unduh berkas untuk belajar luring.
                    </p>
                </div>

                <label class="relative shrink-0 sm:mb-1 w-full sm:w-72 input-glow-group block">
                    <i class="fa-solid fa-magnifying-glass input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-white/40 pointer-events-none" aria-hidden="true"></i>
                    <input type="search" data-filter-search placeholder="Cari judul modul atau materi…"
                           class="tsaqib-input w-full pl-9 pr-4 py-2.5 text-xs rounded-xl">
                </label>
            </div>

            {{-- ===== 3. Sub-navigation ===== --}}
            <div class="reveal" style="--reveal-i:1;">
                @include('tsaqib._labor-subnav', ['active' => 'modul'])
            </div>

            {{-- ===== 4. Dua grup filter berlabel ===== --}}
            <div class="tsaqib-card p-5 sm:p-6 flex flex-wrap items-start gap-x-12 gap-y-6 reveal" style="--reveal-i:2;">
                <div>
                    <p class="grp-label mb-2 text-white/40">Tingkat Kelas</p>
                    <div class="flex items-center gap-6 border-b border-white/10 pb-1" role="group" aria-label="Filter kelas">
                        @foreach(['all' => 'Semua Kelas', 'X' => 'Kelas X', 'XI' => 'Kelas XI', 'XII' => 'Kelas XII'] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kelas" data-value="{{ $val }}"
                                    class="u-tab {{ $loop->first ? 'is-active' : '' }} text-xs font-semibold">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="flex-1 min-w-[260px]">
                    <p class="grp-label mb-2 text-white/40">Kategori Materi</p>
                    <div class="flex flex-wrap gap-2" role="group" aria-label="Filter kategori">
                        @foreach(['all' => 'Semua Kategori', "Aqidah" => "Aqidah", "Fiqih" => "Fiqih", "Al-Qur'an" => "Al-Qur'an", "SKI" => "SKI", "Akhlak" => "Akhlak", "Praktikum" => "Praktikum"] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kategori" data-value="{{ $val }}"
                                    class="labor-chip {{ $loop->first ? 'is-active' : '' }} text-xs">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-white/45 reveal" style="--reveal-i:3;">
                <p>Menampilkan <span data-filter-count class="font-bold text-[var(--gold)]">{{ $moduls->count() }}</span> modul aktif.</p>
                <span class="text-[11px]"><i class="fa-solid fa-circle-info text-[var(--gold)] mr-1"></i>Klik kartu untuk pratinjau</span>
            </div>

            {{-- ===== 5. Grid hasil modul ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($moduls as $modul)
                    <button type="button"
                            data-filter-item
                            data-kelas="{{ $modul->target_kelas }}"
                            data-kategori="{{ $modul->kategori }}"
                            data-search-text="{{ strtolower($modul->judul . ' ' . ($modul->kategori ?? '') . ' ' . ($modul->user?->name ?? '')) }}"
                            data-pdf-src="{{ asset('storage/' . $modul->file_path) }}"
                            data-pdf-title="{{ $modul->judul }}"
                            class="labor-modul-card reveal tsaqib-card p-5 text-left cursor-pointer group flex items-start gap-4 hover:border-[var(--gold)]/40 transition-all duration-300"
                            style="--reveal-i:{{ $loop->index % 6 }};">
                        <span class="shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-2xl text-[var(--gold)] bg-[rgba(201,166,107,0.12)] border border-[rgba(201,166,107,0.3)] transition-transform duration-300 group-hover:scale-105 shadow-sm">
                            <i class="fa-solid fa-file-pdf text-xl" aria-hidden="true"></i>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block font-display font-bold text-sm sm:text-base text-[var(--cream)] leading-snug group-hover:text-[var(--gold)] transition-colors line-clamp-2">
                                {{ $modul->judul }}
                            </span>
                            <span class="block text-[11px] text-white/45 mt-1 truncate">
                                Disusun oleh: {{ $modul->user?->name ?? 'Guru PAI' }}
                            </span>
                            <span class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                                @if($modul->kategori)
                                    <span class="px-2.5 py-0.5 rounded-full border text-[9px] font-bold uppercase tracking-wider bg-[rgba(201,166,107,0.14)] text-[var(--gold)] border-[rgba(201,166,107,0.28)]">
                                        {{ $modul->kategori }}
                                    </span>
                                @endif
                                @if($modul->target_kelas)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">
                                        Kelas {{ $modul->target_kelas }}
                                    </span>
                                @endif
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-white/25 group-hover:text-[var(--gold)] transition shrink-0 mt-1" aria-hidden="true"></i>
                    </button>
                @endforeach
            </div>

            {{-- Empty state kombinasi filter --}}
            <div data-filter-empty class="hidden tsaqib-card-flat p-12 text-center rounded-2xl">
                <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 text-white/30 text-xl">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <p class="text-white/60 text-sm font-semibold">Tidak Ada Modul Ditemukan</p>
                <p class="text-white/40 text-xs mt-1">Coba sesuaikan kata kunci atau kombinasi filter kelas dan kategori.</p>
            </div>

        </div>
        @endunless

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    {{-- ===== Modal preview PDF (viewer native browser) + unduh ===== --}}
    <div id="pdf-modal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-md" role="dialog" aria-modal="true" aria-label="Pratinjau modul PDF">
        <div class="absolute inset-x-0 top-0 flex items-center justify-between gap-3 px-6 py-3.5 bg-black/60 border-b border-white/10">
            <div class="flex items-center gap-2 min-w-0">
                <i class="fa-solid fa-file-pdf text-[var(--gold)]"></i>
                <p id="pdf-modal-title" class="text-xs sm:text-sm font-bold text-[var(--cream)] truncate"></p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a id="pdf-modal-download" href="#" download
                   class="btn-gold btn-download text-xs px-3.5 py-1.5">
                    <i class="fa-solid fa-download text-[11px]"></i>
                    <span>Unduh PDF</span>
                </a>
                <button type="button" data-pdf-close
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white cursor-pointer transition flex items-center justify-center" aria-label="Tutup pratinjau">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>
        <iframe id="pdf-modal-frame" src="" title="Pratinjau modul PDF"
                class="absolute inset-x-0 top-[56px] bottom-0 w-full h-[calc(100%-56px)] bg-[#525659]"></iframe>
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
            frame.src = '';
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
