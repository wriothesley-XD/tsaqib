@extends('layouts.master')

@php($pageTitle = 'Pusat Informasi - TSAQIB SMAN 1 Bukittinggi')

@push('styles')
<style>
    /* ===== Tab bar — "tenggelam" ke latar halaman =====
       Tinted hijau senada dengan body (#0a1f1c, BUKAN hitam #10140F) + blur
       + mask gradient di tepi bawah → garis keras hilang, seolah pill melayang
       di atas latar hijau halaman. */
    .info-tabbar{
        background:rgba(10,31,28,.72);
        backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px);
        -webkit-mask-image:linear-gradient(to bottom,#000 0,#000 70%,transparent 100%);
                mask-image:linear-gradient(to bottom,#000 0,#000 70%,transparent 100%);
    }

    /* Pill tab. Inactive = kaca tipis; Active = solid hijau tema dengan glow
       lembut. Tanpa box-shadow tebal → tidak menumpuk di pill tetangga. */
    .info-tab{
        display:inline-flex;align-items:center;gap:.45rem;
        padding:.6rem 1.25rem;border-radius:999px;
        font-size:.8rem;font-weight:600;
        color:rgba(247,245,239,.6);
        background:rgba(247,245,239,.05);
        border:1px solid rgba(247,245,239,.10);
        transition:color .18s ease,background .18s ease,border-color .18s ease,transform .18s ease,box-shadow .18s ease;
        white-space:nowrap;
    }
    .info-tab:hover{ color:rgba(247,245,239,.92); background:rgba(247,245,239,.09); transform:translateY(-1px); }
    .info-tab.is-active{
        color:#fff;
        background:var(--green);
        border-color:var(--green);
        box-shadow:0 8px 22px -8px rgba(1,121,95,.75);
    }
    .info-tab .count{
        font-size:10px;font-weight:700;padding:1px 6px;border-radius:999px;
        background:rgba(247,245,239,.14);line-height:1.4;
    }
    .info-tab.is-active .count{ background:rgba(255,255,255,.24); }

    /* ===== Kartu Berita ===== */
    .news-card{ transition:transform .25s ease, border-color .25s ease; }
    .news-card:hover{ transform:translateY(-4px); border-color:rgba(201,166,107,.4); }
    .news-thumb{ background:linear-gradient(155deg,#0f7a5c,#0a4a3a); }
    .news-thumb img{ transition:transform .5s ease; }
    .news-card:hover .news-thumb img{ transform:scale(1.06); }

    /* ===== Kartu Buletin ===== */
    .buletin-card{ transition:transform .25s ease, border-color .25s ease; }
    .buletin-card:hover{ transform:translateY(-4px); border-color:rgba(201,166,107,.4); }
    .buletin-card img{ transition:transform .5s ease; }
    .buletin-card:hover img{ transform:scale(1.05); }

    /* ===== Pagination =====
       Mengikuti estetika pill tab: kaca tipis saat diam, solid hijau tema +
       glow lembut saat aktif. */
    .info-pager{
        display:flex;align-items:center;justify-content:center;flex-wrap:wrap;
        gap:.4rem;margin-top:2.5rem;
    }
    .info-pager-btn{
        display:inline-flex;align-items:center;justify-content:center;
        min-width:2.25rem;height:2.25rem;padding:0 .6rem;border-radius:.75rem;
        font-size:.8rem;font-weight:600;
        color:rgba(247,245,239,.6);
        background:rgba(247,245,239,.05);
        border:1px solid rgba(247,245,239,.10);
        transition:color .18s ease,background .18s ease,border-color .18s ease,transform .18s ease,box-shadow .18s ease;
        cursor:pointer;
    }
    .info-pager-btn:hover:not(:disabled){
        color:rgba(247,245,239,.92);background:rgba(247,245,239,.09);transform:translateY(-1px);
    }
    .info-pager-btn.is-active{
        color:#fff;background:var(--green);border-color:var(--green);
        box-shadow:0 8px 22px -8px rgba(1,121,95,.75);
    }
    .info-pager-btn:disabled{ opacity:.35;cursor:not-allowed; }
    .info-pager-ellipsis{ color:rgba(247,245,239,.35);padding:0 .15rem;align-self:center; }

    /* ===== Cross-fade ganti tab (200-220ms) =====
       Panel masuk dianimasikan (fade + sedikit naik); panel keluar langsung
       hidden — hasilnya transisi halus tanpa orkestrasi dua arah. */
    @keyframes panelIn{ from{ opacity:0; transform:translateY(6px); } }
    .panel-fade{ animation:panelIn .22s ease; }

    /* ===== Kartu Dokumentasi ===== */
    .doc-card{ transition:transform .25s ease, border-color .25s ease; }
    .doc-card:hover{ transform:translateY(-4px); border-color:rgba(201,166,107,.4); }
    .doc-thumb{ background:linear-gradient(155deg,#0f7a5c,#0a4a3a); }
    .doc-thumb img{ transition:transform .5s ease; }
    .doc-card:hover .doc-thumb img{ transform:scale(1.06); }

    /* ===== Scroll-reveal + stagger (pola sama dgn landing) =====
       Hidden-state HANYA saat <html> ber-class .js-reveal → tanpa JS semua
       tetap terlihat. */
    .js-reveal .reveal{
        opacity:0; transform:translateY(14px);
        transition:opacity .5s ease,transform .5s ease;
        transition-delay:calc(var(--reveal-i,0) * 90ms);
    }
    .js-reveal .reveal.is-visible{ opacity:1; transform:none; }
    @media (prefers-reduced-motion: reduce){
        .js-reveal .reveal{ opacity:1; transform:none; transition:none; }
        .panel-fade{ animation:none; }
    }
</style>
@endpush

@section('content')
<main class="flex-1 w-full">

    {{-- Hero --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-6 text-center">
        <span class="eyebrow-pill eyebrow-pill-green"><i class="fa-solid fa-circle-info text-[10px]"></i> Pusat Informasi FSI</span>
        <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight leading-tight mt-3">
            Info <span class="text-[var(--gold)]">TSAQIB</span>
        </h1>
        <p class="text-white/55 text-xs sm:text-sm mt-2 leading-relaxed max-w-xl mx-auto">
            Berita terbaru, edisi buletin, dan galeri kegiatan Forum Studi Islam SMAN 1 Bukittinggi dalam satu tempat.
        </p>
    </div>

    {{-- ===== Sticky tab bar (di bawah navbar) =====
         top-16 (h-16 mobile) / xl:top-20 (h-20 desktop) = pas di bawah navbar.
         Tinted hijau halaman + blur + mask gradient tepi bawah → garis keras
         hilang, pill seolah melayang di atas latar. Tanpa border solid. --}}
    <div class="sticky top-16 xl:top-20 z-30 info-tabbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-center gap-2 sm:gap-3">
                <button type="button" data-info-tab="berita"
                        class="info-tab {{ $initialTab === 'berita' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-bullhorn text-[11px]"></i>
                    <span>Berita</span>
                    @if($news->isNotEmpty())<span class="count">{{ $news->count() }}</span>@endif
                </button>
                <button type="button" data-info-tab="buletin"
                        class="info-tab {{ $initialTab === 'buletin' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-book-open text-[11px]"></i>
                    <span>Buletin</span>
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

    {{-- Panels --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- ===== Panel: Berita ===== --}}
        <div data-info-panel="berita" class="{{ $initialTab === 'berita' ? '' : 'hidden' }}">
            @if($news->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 js-pager-grid" data-per-page="6">
                    @foreach($news as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="news-card js-pager-item group tsaqib-card overflow-hidden flex flex-col">
                            {{-- Thumbnail (16:10). Ada foto → tampilkan full-bleed; tidak ada →
                                 fallback gradient bertema (bukan ikon placeholder generik). --}}
                            <div class="news-thumb relative aspect-[16/10] overflow-hidden">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                                         class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0" style="background:radial-gradient(circle at 82% 18%, rgba(201,166,107,.45), transparent 55%);"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="font-display font-extrabold text-xl tracking-tight text-white/15">TSAQIB</span>
                                    </div>
                                @endif
                                <span class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></span>
                                <span class="absolute top-2.5 left-2.5 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/45 backdrop-blur-sm text-[9px] font-bold text-[var(--cream)] uppercase tracking-wider">
                                    <i class="fa-regular fa-calendar text-[8px]"></i>{{ $item->published_at?->format('d M Y') }}
                                </span>
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-display font-bold text-[15px] text-[var(--cream)] leading-snug line-clamp-2">{{ $item->title }}</h3>
                                @if($item->excerpt)
                                    <p class="text-xs text-white/50 mt-1.5 line-clamp-2 leading-relaxed">{{ $item->excerpt }}</p>
                                @endif
                                <div class="mt-auto pt-3 flex items-center justify-between gap-2 border-t border-white/5">
                                    <span class="text-[10px] text-white/40 truncate">{{ $item->user?->name ?? 'Redaksi TSAQIB' }}</span>
                                    <span class="text-[10px] font-bold text-[var(--gold)] inline-flex items-center gap-1 shrink-0">
                                        Baca <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- Pager di-render JS sesuai jumlah item --}}
                <nav class="info-pager js-pager-nav" aria-label="Halaman berita" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center">
                    <i class="fa-solid fa-newspaper text-3xl text-white/15 block mb-3"></i>
                    <p class="text-white/45 text-xs">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endif
        </div>

        {{-- ===== Panel: Buletin ===== --}}
        <div data-info-panel="buletin" class="{{ $initialTab === 'buletin' ? '' : 'hidden' }}">
            @if($buletin->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5 js-pager-grid" data-per-page="8">
                    @foreach($buletin as $book)
                        <div class="buletin-card js-pager-item group tsaqib-card overflow-hidden flex flex-col">
                            <div class="relative aspect-[3/4] bg-[#01795F]/10 overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                         class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0" style="background:linear-gradient(155deg,#0f7a5c,#0a4a3a);"></div>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-white/20">
                                        <i class="fa-solid fa-book-open text-4xl"></i>
                                        <span class="text-[10px] font-semibold uppercase tracking-[0.14em]">Buletin</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3.5 flex flex-col flex-1">
                                <h3 class="font-display font-bold text-[13px] text-[var(--cream)] leading-snug line-clamp-2">{{ $book->title }}</h3>
                                @if($book->author)
                                    <p class="text-[10px] text-white/40 mt-1 truncate">{{ $book->author }}</p>
                                @endif
                                <div class="mt-auto pt-3">
                                    @if($book->pdf_path)
                                        <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" rel="noopener"
                                           class="cta-primary inline-flex items-center justify-center gap-1.5 w-full text-white font-bold text-[11px] px-3 py-2 rounded-full">
                                            <i class="fa-solid fa-file-pdf text-[10px]"></i> Buka PDF
                                        </a>
                                    @else
                                        <span class="block text-center text-[10px] text-white/35 py-2">PDF belum tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Pager di-render JS sesuai jumlah item --}}
                <nav class="info-pager js-pager-nav" aria-label="Halaman buletin" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center">
                    <i class="fa-solid fa-book-open text-3xl text-white/15 block mb-3"></i>
                    <p class="text-white/45 text-xs">Belum ada edisi buletin. Admin dapat menambahkannya lewat tab <span class="text-[var(--gold)] font-semibold">Buku PDF</span> dengan kategori <span class="text-[var(--gold)] font-semibold">Buletin</span>.</p>
                </div>
            @endif
        </div>

        {{-- ===== Panel: Dokumentasi (galeri kegiatan) ===== --}}
        <div data-info-panel="dokumentasi" class="{{ $initialTab === 'dokumentasi' ? '' : 'hidden' }}">
            @if($documentations->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5 js-pager-grid" data-per-page="8">
                    @foreach($documentations as $doc)
                        <a href="{{ route('info.dokumentasi.show', $doc->slug) }}"
                           class="doc-card js-pager-item reveal group tsaqib-card overflow-hidden flex flex-col"
                           style="--reveal-i:{{ $loop->index % 8 }};">
                            {{-- Cover = foto pertama kegiatan. --}}
                            <div class="doc-thumb relative aspect-[4/3] overflow-hidden">
                                @if($doc->photos->first())
                                    <img src="{{ asset('storage/' . $doc->photos->first()->image_path) }}" alt="{{ $doc->title }}"
                                         class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 text-white/20">
                                        <i class="fa-solid fa-images text-3xl"></i>
                                    </div>
                                @endif
                                <span class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></span>
                                {{-- Badge jumlah foto. --}}
                                <span class="absolute bottom-2.5 right-2.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-black/50 backdrop-blur-sm text-[9px] font-bold text-[var(--cream)]">
                                    <i class="fa-solid fa-camera text-[8px]"></i>{{ $doc->photos->count() }} Foto
                                </span>
                                {{-- Badge kategori (kalau ada) — tint hijau/emas/krem. --}}
                                @if($doc->category)
                                    <span class="absolute top-2.5 left-2.5 inline-flex items-center px-2 py-0.5 rounded-full border backdrop-blur-sm text-[9px] font-bold uppercase tracking-wider {{ $doc->badgeClass() }}">
                                        {{ $doc->category }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-3.5 flex flex-col flex-1">
                                <h3 class="font-display font-bold text-[13px] text-[var(--cream)] leading-snug line-clamp-2">{{ $doc->title }}</h3>
                                <div class="mt-auto pt-2.5 flex items-center justify-between gap-2 border-t border-white/5">
                                    <span class="text-[10px] text-white/40 inline-flex items-center gap-1">
                                        <i class="fa-regular fa-calendar text-[8px]"></i>{{ $doc->event_date?->format('d M Y') ?? '—' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-[var(--gold)] inline-flex items-center gap-1 shrink-0">
                                        Lihat <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- Pager di-render JS sesuai jumlah item --}}
                <nav class="info-pager js-pager-nav" aria-label="Halaman dokumentasi" hidden></nav>
            @else
                <div class="tsaqib-card-flat p-12 text-center">
                    <i class="fa-solid fa-images text-3xl text-white/15 block mb-3"></i>
                    <p class="text-white/45 text-xs">Belum ada dokumentasi kegiatan yang dipublikasikan.</p>
                </div>
            @endif
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script>
(function () {
    var tabs   = document.querySelectorAll('[data-info-tab]');
    var panels = document.querySelectorAll('[data-info-panel]');
    if (!tabs.length || !panels.length) return;

    function activate(name) {
        tabs.forEach(function (t) { t.classList.toggle('is-active', t.dataset.infoTab === name); });
        panels.forEach(function (p) {
            if (p.dataset.infoPanel === name) {
                if (p.classList.contains('hidden')) {
                    p.classList.remove('hidden');
                    // Replay animasi cross-fade saat panel muncul.
                    p.classList.remove('panel-fade'); void p.offsetWidth; p.classList.add('panel-fade');
                }
            } else {
                p.classList.add('hidden');
            }
        });
    }

    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            var name = t.dataset.infoTab;
            activate(name);
            if (history.replaceState) history.replaceState(null, '', '#' + name);
        });
    });

    // Honor #hash pada load agar /info#buletin bisa dibagikan.
    var hash = (location.hash || '').replace('#', '');
    if (hash === 'berita' || hash === 'buletin' || hash === 'dokumentasi') activate(hash);

    /* ===== Scroll-reveal + stagger (.reveal + --reveal-i) =====
       Pola sama dengan landing: IntersectionObserver memunculkan elemen saat
       masuk viewport. Tanpa JS (tanpa .js-reveal) semua tetap terlihat. */
    if ('IntersectionObserver' in window) {
        document.documentElement.classList.add('js-reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
            });
        }, { threshold: .12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    }

    /* ===== Pagination client-side per-panel =====
       Tiap grid bertanda .js-pager-grid + data-per-page. JS menyembunyikan
       item di luar halaman aktif dan merender nav .js-pager-nav (angka
       berwindow + ellipsis + prev/next). Status halaman disimpan di DOM
       (data-page), jadi tab switching tidak mengganggu state masing-masing. */
    function windowedRange(cur, total, side) {
        // Range angka halaman dengan ellipsis di kedua ujung.
        // side = jumlah halaman di sekitar current yang ditampilkan literal.
        var out = [], i;
        if (total <= 7) {
            for (i = 1; i <= total; i++) out.push(i);
            return out;
        }
        var left  = Math.max(2, cur - side);
        var right = Math.min(total - 1, cur + side);
        out.push(1);
        if (left > 2) out.push('…');
        for (i = left; i <= right; i++) out.push(i);
        if (right < total - 1) out.push('…');
        out.push(total);
        return out;
    }

    function renderPager(grid) {
        var items  = Array.prototype.slice.call(grid.querySelectorAll('.js-pager-item'));
        var nav    = grid.parentElement.querySelector('.js-pager-nav');
        if (!items.length || !nav) return;

        var per    = Math.max(1, parseInt(grid.getAttribute('data-per-page'), 10) || 6);
        var total  = Math.ceil(items.length / per);
        var page   = Math.min(parseInt(grid.getAttribute('data-page'), 10) || 1, total);
        if (page < 1) page = 1;
        grid.setAttribute('data-page', page);

        // Tampilkan hanya item pada halaman aktif.
        items.forEach(function (el, idx) {
            el.style.display = (idx >= (page - 1) * per && idx < page * per) ? '' : 'none';
        });

        // Cukup satu halaman → pager disembunyikan.
        if (total <= 1) { nav.hidden = true; nav.innerHTML = ''; return; }
        nav.hidden = false;

        var html = '';
        html += '<button type="button" class="info-pager-btn js-pager-prev" ' + (page === 1 ? 'disabled' : '') + ' aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>';
        windowedRange(page, total, 1).forEach(function (n) {
            if (n === '…') {
                html += '<span class="info-pager-ellipsis">…</span>';
            } else {
                html += '<button type="button" class="info-pager-btn js-pager-num' + (n === page ? ' is-active' : '') + '" data-page="' + n + '">' + n + '</button>';
            }
        });
        html += '<button type="button" class="info-pager-btn js-pager-next" ' + (page === total ? 'disabled' : '') + ' aria-label="Berikutnya"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>';
        nav.innerHTML = html;

        // Pindahkan panel ke posisi yang sama: scroll grid ke bawah navbar+tabbar.
        var sticky = grid.closest('main').querySelector('.info-tabbar');
        nav.querySelectorAll('.info-pager-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var delta = btn.classList.contains('js-pager-prev') ? -1
                          : btn.classList.contains('js-pager-next') ? 1 : 0;
                grid.setAttribute('data-page', delta ? Math.min(Math.max(page + delta, 1), total) : parseInt(btn.getAttribute('data-page'), 10));
                renderPager(grid);
                var top = grid.getBoundingClientRect().top + window.pageYOffset
                        - (sticky ? sticky.offsetHeight : 0) - 16;
                window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
            });
        });
    }

    document.querySelectorAll('.js-pager-grid').forEach(renderPager);
})();
</script>
@endpush
