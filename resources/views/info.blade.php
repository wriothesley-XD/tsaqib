@extends('layouts.master')

@php($pageTitle = 'Pusat Informasi - TSAQIB SMAN 1 Bukittinggi')
@php($tabLabels = ['berita' => 'Warta Berita', 'buletin' => 'Buletin Cetak', 'dokumentasi' => 'Dokumentasi'])

@push('styles')
<style>
    .info-pager {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 2.5rem;
    }
    .info-pager-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem;
        height: 2.25rem;
        padding: 0 0.6rem;
        border-radius: 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(247, 245, 239, 0.65);
        background: rgba(247, 245, 239, 0.05);
        border: 1px solid rgba(247, 245, 239, 0.12);
        transition: all 0.18s ease;
        cursor: pointer;
    }
    .info-pager-btn:hover:not(:disabled) {
        color: var(--cream);
        background: rgba(247, 245, 239, 0.1);
        border-color: rgba(201, 166, 107, 0.3);
    }
    .info-pager-btn.is-active {
        color: #fff;
        background: var(--green);
        border-color: var(--green);
        box-shadow: 0 6px 18px -6px rgba(1, 121, 95, 0.6);
    }
    .info-pager-btn:disabled { opacity: 0.35; cursor: not-allowed; }
    .cat-hidden { display: none !important; } /* filter kategori menimpa display pager */

    /* Fallback cover card video: hijau tua + pattern geometris (titik emas +
       garis diagonal) — tampil saat thumbnail kosong/gagal dimuat */
    .doc-video-fallback{
        background-color:#0D2818;
        background-image:
            radial-gradient(rgba(201,166,107,.16) 1px, transparent 1.5px),
            repeating-linear-gradient(45deg, rgba(247,245,239,.045) 0 2px, transparent 2px 14px);
        background-size:18px 18px, auto;
    }
</style>
@endpush

@section('content')
<main class="flex-1 w-full relative">
    <div class="pat-islami"></div>

    {{-- Header section — pola sama dgn <x-page-header> di halaman Laboratorium PAI --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-6 relative z-10">
        <nav class="flex items-center justify-center gap-2 text-[11px] text-white/45 mb-4" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-[var(--cream)] transition-colors">Beranda</a>
            <i class="fa-solid fa-angle-right text-[8px]" aria-hidden="true"></i>
            <a href="{{ route('info') }}" class="hover:text-[var(--cream)] transition-colors">Info</a>
            <i class="fa-solid fa-angle-right text-[8px]" aria-hidden="true"></i>
            <span class="font-semibold text-[var(--gold)]" data-breadcrumb-current>{{ $tabLabels[$initialTab] ?? 'Warta Berita' }}</span>
        </nav>

        <x-page-header
            eyebrow="Kabar &amp; Dokumentasi"
            eyebrow-icon="fa-solid fa-circle-info"
            title="Warta &amp; Dokumentasi <span class='text-[var(--gold)]'>TSAQIB</span>"
            subtitle="Berita kegiatan, terbitan buletin berkala, dan dokumentasi visual Forum Studi Islam SMAN 1 Bukittinggi." />
    </div>

    {{-- Sub-navigasi tab — struktur sama dgn _labor-subnav Laboratorium PAI (nav + border-b + .u-tab) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex items-center gap-4 sm:gap-6 border-b border-white/10 overflow-x-auto" aria-label="Navigasi Info">
            @foreach($tabLabels as $key => $label)
                <button type="button" data-info-tab="{{ $key }}"
                        class="u-tab {{ $initialTab === $key ? 'is-active' : '' }}">
                    <span>{{ $label }}</span>
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Content Panels --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10">

        {{-- ===== Panel: Berita ===== --}}
        <div data-info-panel="berita" class="{{ $initialTab === 'berita' ? '' : 'hidden' }}">
            @if($news->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 js-pager-grid" data-per-page="6">
                    @foreach($news as $item)
                        @php($cat = $item->category ?? 'berita')
                        <a href="{{ route('berita.show', $item->slug) }}" class="js-pager-item group tsaqib-card-interactive overflow-hidden flex flex-col">
                            <div class="relative aspect-[16/10] overflow-hidden bg-black/40">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    {{-- Placeholder jelas: bg solid + ikon, bukan watermark transparan --}}
                                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-[#1C442B] to-[#0D2818] text-white/25">
                                        <i class="fa-solid fa-newspaper text-4xl"></i>
                                        <span class="text-[9px] font-bold uppercase tracking-wider">Warta TSAQIB</span>
                                    </div>
                                @endif
                                <span class="absolute inset-0 bg-gradient-to-t from-[#0D2818]/90 via-transparent to-transparent"></span>
                                {{-- Badge kategori (kiri, warna per kategori) + tanggal (kanan) --}}
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full backdrop-blur-sm text-[9px] font-bold uppercase tracking-wider {{ $cat === 'pengumuman' ? 'bg-[rgba(201,166,107,.92)] text-[#10140F]' : 'bg-[#01795F]/90 text-white' }}">
                                    <i class="fa-solid {{ $cat === 'pengumuman' ? 'fa-bullhorn' : 'fa-newspaper' }} text-[8px]"></i>{{ ucfirst($cat) }}
                                </span>
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/50 backdrop-blur-sm text-[9px] font-bold text-[var(--cream)] uppercase tracking-wider">
                                    <i class="fa-regular fa-calendar text-[8px] text-[var(--gold)]"></i>{{ $item->published_at?->format('d M Y') }}
                                </span>
                            </div>

                            <div class="p-4 sm:p-5 flex flex-col flex-1 space-y-2">
                                <h3 class="font-display font-bold text-base text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                    {{ $item->title }}
                                </h3>
                                @if($item->excerpt)
                                    <p class="text-xs text-white/60 line-clamp-2 leading-relaxed">{{ $item->excerpt }}</p>
                                @endif
                                <div class="mt-auto pt-3 flex items-center justify-between gap-2 border-t border-white/5">
                                    <span class="text-[10px] text-white/40 truncate">{{ $item->user?->name ?? 'Redaksi TSAQIB' }}</span>
                                    <span class="text-[10px] font-bold text-[var(--gold)] inline-flex items-center gap-1 shrink-0">
                                        Baca <i class="fa-solid fa-arrow-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <nav class="info-pager js-pager-nav" aria-label="Halaman berita" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-newspaper text-3xl text-white/20 block mb-2"></i>
                    <p>Belum ada berita yang dipublikasikan.</p>
                </div>
            @endif
        </div>

        {{-- ===== Panel: Buletin ===== --}}
        <div data-info-panel="buletin" class="{{ $initialTab === 'buletin' ? '' : 'hidden' }}">
            @if($buletin->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 js-pager-grid" data-per-page="8">
                    @foreach($buletin as $book)
                        <div class="js-pager-item group tsaqib-card-interactive overflow-hidden flex flex-col">
                            <div class="relative aspect-[3/4] bg-[#143520] overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-[#1C442B] to-[#0D2818] text-white/25">
                                        <i class="fa-solid fa-book-open text-4xl"></i>
                                        <span class="text-[9px] font-bold uppercase tracking-wider">Edisi Buletin</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3.5 sm:p-4 flex flex-col flex-1 space-y-2">
                                <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                    {{ $book->title }}
                                </h3>
                                @if($book->author)
                                    <p class="text-[10px] text-white/45 truncate">{{ $book->author }}</p>
                                @endif
                                <div class="mt-auto pt-2">
                                    @if($book->pdf_path)
                                        <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" rel="noopener"
                                           class="btn-primary w-full py-2 text-[11px] justify-center">
                                            <i class="fa-solid fa-file-pdf text-[10px]"></i> Buka PDF
                                        </a>
                                    @else
                                        <span class="block text-center text-[10px] text-white/35 py-1.5 bg-white/5 rounded-lg">PDF Belum Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <nav class="info-pager js-pager-nav" aria-label="Halaman buletin" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-book-open text-3xl text-white/20 block mb-2"></i>
                    <p>Belum ada edisi buletin yang diunggah.</p>
                </div>
            @endif
        </div>

        {{-- ===== Panel: Dokumentasi ===== --}}
        <div data-info-panel="dokumentasi" class="{{ $initialTab === 'dokumentasi' ? '' : 'hidden' }}">
            @if($documentations->isNotEmpty())
                {{-- Filter kategori (client-side) — komponen .u-tab yang sama dgn sub-nav di atas --}}
                <div class="flex items-center gap-3 flex-wrap mb-6" data-doc-filter role="group" aria-label="Filter kategori dokumentasi">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/40 shrink-0">Kategori</span>
                    <button type="button" data-cat="*" class="u-tab is-active"><span>Semua</span></button>
                    @foreach($documentations->pluck('category')->filter()->unique() as $cat)
                        <button type="button" data-cat="{{ $cat }}" class="u-tab"><span>{{ $cat }}</span></button>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 js-pager-grid" data-per-page="8">
                    @foreach($documentations as $doc)
                        {{-- Cover = foto pertama galeri (kolom cover_image TIDAK ada di
                             tabel ini — pola sama dgn daftar admin _list_documentations). --}}
                        @php($cover = $doc->photos->first()?->image_path)
                        <a href="{{ route('info.dokumentasi.show', $doc->slug) }}" data-cat="{{ $doc->category }}"
                           class="js-pager-item group tsaqib-card-interactive overflow-hidden flex flex-col">
                            <div @class(['relative overflow-hidden bg-black/40', $doc->video_path ? 'aspect-video' : 'aspect-[16/11]'])>
                                @if($doc->video_path)
                                    {{-- ===== CARD VIDEO =====
                                         Fallback cover dirender SELALU di belakang img:
                                         thumbnail kosong atau onerror (img di-remove) →
                                         otomatis tersingkap. pattern via .doc-video-fallback --}}
                                    <div class="doc-video-fallback absolute inset-0 flex flex-col items-center justify-center gap-2">
                                        <span class="w-14 h-14 rounded-full bg-[var(--gold)] text-[#10140F] flex items-center justify-center text-lg shadow-[0_10px_30px_-8px_rgba(201,166,107,.8)]">
                                            <i class="fa-solid fa-play translate-x-[2px]"></i>
                                        </span>
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-white/60">Dokumentasi Video Tsaqib</span>
                                    </div>
                                    @if($cover)
                                        <img src="{{ asset('storage/' . $cover) }}" alt="{{ $doc->title }}"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                        <span class="absolute inset-0 bg-black/40"></span>
                                        <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <span class="w-11 h-11 rounded-full bg-[var(--gold)]/90 text-[#10140F] flex items-center justify-center shadow-lg transition-transform duration-200 group-hover:scale-110">
                                                <i class="fa-solid fa-play text-xs ml-0.5"></i>
                                            </span>
                                        </span>
                                    @endif
                                    {{-- Judul di cover (kiri) + badge durasi (kanan, diisi JS;
                                         video Drive tidak punya metadata durasi → badge disembunyikan) --}}
                                    <span class="absolute inset-x-2 bottom-2 flex items-end justify-between gap-2">
                                        <span class="text-[11px] font-bold text-white leading-tight line-clamp-2 drop-shadow-[0_1px_2px_rgba(0,0,0,.8)]">{{ $doc->title }}</span>
                                        @if($doc->isLocalVideo())
                                            <span data-doc-duration data-video-src="{{ asset('storage/' . $doc->video_path) }}"
                                                  class="hidden shrink-0 text-[9px] font-bold text-white bg-black/70 px-1.5 py-0.5 rounded tabular-nums">--:--</span>
                                        @endif
                                    </span>
                                @else
                                    {{-- ===== CARD GALERI FOTO ===== --}}
                                    @if($cover)
                                        <img src="{{ asset('storage/' . $cover) }}" alt="{{ $doc->title }}"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                                            <i class="fa-solid fa-camera text-3xl text-white/20"></i>
                                            <span class="text-[9px] font-bold uppercase tracking-wider text-white/30">Gambar tidak tersedia</span>
                                        </div>
                                    @endif
                                    <span class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></span>
                                    @if($doc->event_date)
                                        <span class="absolute bottom-2 left-2 text-[9px] font-bold text-white/80 bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm">
                                            {{ $doc->event_date->format('d M Y') }}
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <div class="p-3.5 flex flex-col flex-1">
                                @if(! $doc->video_path)
                                    {{-- Card video: judul sudah di cover — hindari duplikat --}}
                                    <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                        {{ $doc->title }}
                                    </h3>
                                @endif
                                @if($doc->location)
                                    <p class="text-[10px] text-[var(--gold)] mt-1 truncate">
                                        <i class="fa-solid fa-location-dot text-[8px] mr-1"></i>{{ $doc->location }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <nav class="info-pager js-pager-nav" aria-label="Halaman dokumentasi" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-images text-3xl text-white/20 block mb-2"></i>
                    <p>Belum ada dokumentasi kegiatan.</p>
                </div>
            @endif
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script>
(function () {
    // Tab switching
    const tabs = document.querySelectorAll('[data-info-tab]');
    const panels = document.querySelectorAll('[data-info-panel]');

    function switchTab(name) {
        tabs.forEach(t => t.classList.toggle('is-active', t.dataset.infoTab === name));
        panels.forEach(p => p.classList.toggle('hidden', p.dataset.infoPanel !== name));
        history.replaceState(null, '', '?tab=' + name);
    }

    tabs.forEach(t => {
        t.addEventListener('click', () => {
            switchTab(t.dataset.infoTab);
            var bc = document.querySelector('[data-breadcrumb-current]');
            if (bc) bc.textContent = t.textContent.trim();
        });
    });

    // Client-side paginator for grids
    document.querySelectorAll('.js-pager-grid').forEach(grid => {
        const items = Array.from(grid.querySelectorAll('.js-pager-item'));
        const perPage = parseInt(grid.dataset.perPage, 10) || 6;
        const nav = grid.parentElement.querySelector('.js-pager-nav');
        if (!nav || items.length <= perPage) return;

        const totalPages = Math.ceil(items.length / perPage);
        let currentPage = 1;

        function renderPage(page) {
            currentPage = page;
            items.forEach((item, idx) => {
                const show = idx >= (page - 1) * perPage && idx < page * perPage;
                item.style.display = show ? '' : 'none';
            });

            nav.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'info-pager-btn' + (i === page ? ' is-active' : '');
                btn.textContent = i;
                btn.onclick = () => renderPage(i);
                nav.appendChild(btn);
            }
            nav.hidden = false;
        }

        renderPage(1);
        grid.__renderPage = renderPage; // dipakai ulang oleh filter kategori
    });

    // Filter kategori dokumentasi (client-side; volume data kecil)
    var filterBox = document.querySelector('[data-doc-filter]');
    if (filterBox) {
        var panel = filterBox.closest('[data-info-panel]');
        var grid  = panel.querySelector('.js-pager-grid');
        var nav   = panel.querySelector('.js-pager-nav');

        filterBox.addEventListener('click', function (e) {
            var chip = e.target.closest('[data-cat]');
            if (!chip) return;
            filterBox.querySelectorAll('[data-cat]').forEach(function (c) {
                c.classList.toggle('is-active', c === chip);
            });

            if (chip.dataset.cat === '*') {
                grid.querySelectorAll('.cat-hidden').forEach(function (item) {
                    item.classList.remove('cat-hidden');
                });
                nav.hidden = false;
                if (grid.__renderPage) grid.__renderPage(1);
                return;
            }

            grid.querySelectorAll('.js-pager-item').forEach(function (item) {
                item.classList.toggle('cat-hidden', item.dataset.cat !== chip.dataset.cat);
            });
            nav.hidden = true; // pager hanya untuk tampilan "Semua"
        });
    }
})();

/* Badge durasi card video: baca metadata file lokal (preload="metadata"),
   format mm:ss. Video Drive tidak punya metadata via iframe → badge tetap hidden. */
(function () {
    document.querySelectorAll('[data-doc-duration]').forEach(function (el) {
        var probe = document.createElement('video');
        probe.preload = 'metadata';
        probe.addEventListener('loadedmetadata', function () {
            var total = Math.round(probe.duration);
            if (!isFinite(total) || total <= 0) return;
            var m = Math.floor(total / 60), s = total % 60;
            el.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            el.classList.remove('hidden');
        });
        probe.src = el.dataset.videoSrc;
    });
})();
</script>
@endpush
