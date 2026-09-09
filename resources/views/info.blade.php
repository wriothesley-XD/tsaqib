@extends('layouts.master')

@php($pageTitle = 'Pusat Informasi - TSAQIB SMAN 1 Bukittinggi')

@push('styles')
<style>
    .info-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 700;
        color: rgba(247, 245, 239, 0.65);
        background: rgba(247, 245, 239, 0.05);
        border: 1px solid rgba(247, 245, 239, 0.12);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .info-tab:hover {
        color: var(--cream);
        background: rgba(247, 245, 239, 0.1);
        border-color: rgba(201, 166, 107, 0.3);
    }
    .info-tab.is-active {
        color: #ffffff;
        background: var(--green);
        border-color: var(--green);
        box-shadow: 0 6px 20px -6px rgba(1, 121, 95, 0.7);
    }
    .info-tab .count {
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 9999px;
        background: rgba(247, 245, 239, 0.15);
    }
    .info-tab.is-active .count {
        background: rgba(255, 255, 255, 0.25);
    }

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
</style>
@endpush

@section('content')
<main class="flex-1 w-full relative">
    <div class="pat-islami"></div>

    {{-- Hero Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-6 text-center relative z-10 space-y-3">
        <span class="eyebrow-pill eyebrow-pill-green">
            <i class="fa-solid fa-circle-info text-[10px]"></i> Pusat Informasi Terpadu
        </span>
        <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-[var(--cream)] tracking-tight">
            Info &amp; Warta <span class="text-[var(--gold)]">TSAQIB</span>
        </h1>
        <p class="text-white/60 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
            Berita kegiatan, terbitan buletin berkala, dan dokumentasi visual Forum Studi Islam SMAN 1 Bukittinggi.
        </p>
    </div>

    {{-- Sticky Tab Bar --}}
    <div class="sticky top-16 xl:top-20 z-30 bg-[#0D2818]/90 backdrop-blur-md border-y border-white/10 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center gap-2 sm:gap-3 overflow-x-auto pb-0.5">
                <button type="button" data-info-tab="berita"
                        class="info-tab {{ $initialTab === 'berita' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-bullhorn text-[11px]"></i>
                    <span>Warta Berita</span>
                    @if($news->isNotEmpty())<span class="count">{{ $news->count() }}</span>@endif
                </button>
                <button type="button" data-info-tab="buletin"
                        class="info-tab {{ $initialTab === 'buletin' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-book-open text-[11px]"></i>
                    <span>Buletin Cetak</span>
                    @if($buletin->isNotEmpty())<span class="count">{{ $buletin->count() }}</span>@endif
                </button>
                <button type="button" data-info-tab="dokumentasi"
                        class="info-tab {{ $initialTab === 'dokumentasi' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-images text-[11px]"></i>
                    <span>Dokumentasi</span>
                    @if($documentations->isNotEmpty())<span class="count">{{ $documentations->count() }}</span>@endif
                </button>
            </div>
        </div>
    </div>

    {{-- Content Panels --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10">

        {{-- ===== Panel: Berita ===== --}}
        <div data-info-panel="berita" class="{{ $initialTab === 'berita' ? '' : 'hidden' }}">
            @if($news->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 js-pager-grid" data-per-page="6">
                    @foreach($news as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="js-pager-item group tsaqib-card-interactive overflow-hidden flex flex-col">
                            <div class="relative aspect-[16/10] overflow-hidden bg-black/40">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                                        <span class="font-display font-extrabold text-2xl tracking-wider text-white/15">TSAQIB</span>
                                    </div>
                                @endif
                                <span class="absolute inset-0 bg-gradient-to-t from-[#0D2818]/90 via-transparent to-transparent"></span>
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/50 backdrop-blur-sm text-[9px] font-bold text-[var(--cream)] uppercase tracking-wider">
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
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.remove()">
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
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 js-pager-grid" data-per-page="8">
                    @foreach($documentations as $doc)
                        <a href="{{ route('info.dokumentasi.show', $doc->slug) }}"
                           class="js-pager-item group tsaqib-card-interactive overflow-hidden flex flex-col">
                            <div class="relative aspect-[16/11] bg-black/40 overflow-hidden">
                                @if($doc->cover_image)
                                    <img src="{{ asset('storage/' . $doc->cover_image) }}" alt="{{ $doc->title }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                                        <i class="fa-solid fa-camera text-3xl text-white/20"></i>
                                    </div>
                                @endif
                                <span class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></span>
                                @if($doc->event_date)
                                    <span class="absolute bottom-2 left-2 text-[9px] font-bold text-white/80 bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm">
                                        {{ $doc->event_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-3.5 flex flex-col flex-1">
                                <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--cream)] leading-snug line-clamp-2 group-hover:text-[var(--gold)] transition-colors">
                                    {{ $doc->title }}
                                </h3>
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
        t.addEventListener('click', () => switchTab(t.dataset.infoTab));
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
    });
})();
</script>
@endpush
