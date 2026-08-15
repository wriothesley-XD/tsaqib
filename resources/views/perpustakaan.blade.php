@extends('layouts.master')

@php
    $pageTitle = 'Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi';
<<<<<<< Updated upstream
=======

    // Kategori filter (server-side). 'semua' = tanpa filter kategori.
    $bookCategories = [
        'semua'  => 'Semua Buku',
        'fiqih'  => 'Fiqih',
        'aqidah' => 'Aqidah',
        'ski'    => 'SKI',
        'hadits' => 'Hadits & Tafsir',
        'modul'  => 'Modul PAI',
    ];
  $category = request()->query('category', 'semua');
$q = request()->query('q', '');
$activeCat = in_array($category, array_keys($bookCategories), true) ? $category : 'semua';
>>>>>>> Stashed changes
@endphp

@push('styles')
<style>
    /* Carousel: sembunyikan scrollbar native (tetap bisa scroll/swipe) */
    .row-track{ scrollbar-width:none; -ms-overflow-style:none; }
    .row-track::-webkit-scrollbar{ display:none; }

    /* Offset anchor genre agar judul tidak nempel navbar saat lompat */
    .genre-section{ scroll-margin-top:6rem; }

    /* === Sidebar: link menu === */
    .menu-link{
        display:flex;align-items:center;gap:.6rem;
        padding:.6rem .8rem;border-radius:.65rem;
        font-size:.82rem;font-weight:600;
        color:rgba(247,245,239,.7);
        transition:background .15s ease,color .15s ease;
    }
    .menu-link:hover{ background:rgba(247,245,239,.05); color:var(--cream); }
    .menu-link.active{ background:rgba(1,121,95,.2); color:var(--cream); }

    /* === Sidebar: link genre (desktop) === */
    .genre-link{
        display:flex;align-items:center;justify-content:space-between;
        padding:.55rem .8rem;border-radius:.6rem;
        font-size:.82rem;color:rgba(247,245,239,.7);
        transition:background .15s ease,color .15s ease;
    }
    .genre-link:hover{ background:rgba(247,245,239,.05); color:var(--cream); }
    .genre-link.active{ background:rgba(1,121,95,.18); color:var(--gold); font-weight:700; }
    .genre-link .count{
        font-size:10px;padding:1px 7px;border-radius:999px;
        background:rgba(247,245,239,.08);color:rgba(247,245,239,.6);
    }
    .genre-link.active .count{ background:rgba(201,166,107,.2); color:var(--gold); }

    /* === Mobile: chip genre === */
    .genre-pill{
        background:rgba(247,245,239,.05);color:rgba(247,245,239,.75);
        border:1px solid rgba(247,245,239,.1);
    }
    .genre-pill:hover{ background:rgba(247,245,239,.1); color:var(--cream); }
    .genre-pill.active{ background:rgba(1,121,95,.28); color:var(--gold); border-color:rgba(201,166,107,.35); }

    /* === Tombol bookmark pada cover (state awal dari server, di-toggle JS) === */
    .bookmark-btn.is-off{ background:rgba(0,0,0,.45); color:rgba(255,255,255,.85); }
    .bookmark-btn.is-off:hover{ background:rgba(0,0,0,.72); }
    .bookmark-btn.is-collection.is-on{ background:var(--gold); color:var(--ink); }
    .bookmark-btn.is-saved.is-on{ background:#01795F; color:#fff; }

    /* === Panah carousel === */
    .row-arrow{
        position:absolute;top:50%;transform:translateY(-50%);z-index:5;
        width:2.1rem;height:2.1rem;display:flex;align-items:center;justify-content:center;
        border-radius:999px;background:rgba(16,20,15,.88);border:1px solid rgba(247,245,239,.14);
        color:rgba(247,245,239,.85);cursor:pointer;
        transition:opacity .15s ease,background .15s ease,color .15s ease;
    }
    .row-arrow:hover{ background:rgba(1,121,95,.95); color:#fff; }
    .row-prev{ left:-8px; }
    .row-next{ right:-8px; }
    .row-arrow-hidden{ opacity:0;pointer-events:none; }
    @media (max-width:1023px){ .row-arrow{ display:none; } } /* mobile: swipe pakai gesture */
</style>
@endpush

@section('content')
<main class="flex-1 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        {{-- Hero ringkas --}}
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
            <span class="eyebrow-pill eyebrow-pill-green"><i class="fa-solid fa-book-open text-[10px]"></i> Maktabah Digital Publik FSI</span>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight leading-tight mt-3">
                Perpustakaan Digital <span class="text-[var(--gold)]">PAI SMAN 1 Bukittinggi</span>
            </h1>
            <p class="text-white/55 text-xs sm:text-sm mt-2 leading-relaxed">
                Akses publik buku digital, modul PAI, materi Aqidah, Fiqih, SKI, dan Hadits tanpa perlu login.
            </p>
        </div>

        {{-- Dua kolom: sidebar sticky (lg+) + konten utama --}}
        <div class="lg:grid lg:grid-cols-[260px_minmax(0,1fr)] lg:gap-8 xl:gap-10">

            {{-- ============================ SIDEBAR (lg+) ============================ --}}
            <aside class="hidden lg:block">
                <div class="sticky top-24 space-y-5">

                    {{-- Kartu user / CTA masuk --}}
                    @auth
                        <div class="tsaqib-card p-4 flex items-center gap-3">
                            <x-community-avatar :user="Auth::user()" size="lg" />
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-[var(--cream)] truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-white/45 truncate">{{ Auth::user()->selected_community ?: 'Anggota TSAQIB' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="tsaqib-card p-4 text-center">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-2 text-white/40">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <p class="text-[11px] text-white/55 mb-3 leading-relaxed">
                                Masuk untuk menyimpan buku &amp; membangun koleksi pribadi Anda.
                            </p>
                            <a href="{{ route('login') }}" class="cta-primary block py-2.5 rounded-full text-xs font-bold text-white">Masuk</a>
                        </div>
                    @endauth

                    {{-- Menu utama --}}
                    <nav class="tsaqib-card p-2 space-y-0.5">
                        <a href="{{ route('perpustakaan') }}" class="menu-link {{ $mode === 'rows' ? 'active' : '' }}">
                            <i class="fa-solid fa-layer-group text-[var(--gold)] w-4 text-center text-xs"></i> Semua Genre
                        </a>
                        @auth
                            <a href="{{ route('perpustakaan', ['view' => 'collection']) }}" class="menu-link {{ $mode === 'collection' ? 'active' : '' }}">
                                <i class="fa-solid fa-bookmark text-[var(--gold)] w-4 text-center text-xs"></i> Koleksi Saya
                            </a>
                            <a href="{{ route('perpustakaan', ['view' => 'saved']) }}" class="menu-link {{ $mode === 'saved' ? 'active' : '' }}">
                                <i class="fa-solid fa-heart text-[var(--gold)] w-4 text-center text-xs"></i> Tersimpan
                            </a>
                        @endauth
                    </nav>

                    {{-- Daftar genre dari DB --}}
                    <div class="tsaqib-card p-3">
                        <p class="px-2 mb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">Genre</p>
                        @if($genreNav->isNotEmpty())
                            @foreach($genreNav as $g)
                                @php
                                    $gHref = $mode === 'rows' ? '#' . $g['slug'] : route('perpustakaan', ['category' => $g['key']]);
                                    $gActive = $mode === 'rows' ? false : ($mode === 'genre' && ($genreKey ?? null) === $g['key']);
                                @endphp
                                <a href="{{ $gHref }}" data-genre="{{ $g['key'] }}" class="genre-link {{ $gActive ? 'active' : '' }}">
                                    <span>{{ $g['label'] }}</span>
                                    <span class="count">{{ $g['total'] }}</span>
                                </a>
                            @endforeach
                        @else
                            <p class="px-2 py-3 text-[11px] text-white/40">Belum ada genre.</p>
                        @endif
                    </div>
                </div>
            </aside>

            {{-- ============================ KONTEN UTAMA ============================ --}}
            <div class="min-w-0 mt-2 lg:mt-0">

                {{-- Bar mobile (user ringkas + chip genre) --}}
                <div class="lg:hidden mb-5 space-y-3">
                    @auth
                        <div class="tsaqib-card p-3 flex items-center gap-3">
                            <x-community-avatar :user="Auth::user()" size="sm" />
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-xs text-[var(--cream)] truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('perpustakaan', ['view' => 'collection']) }}" class="text-[10px] font-semibold text-white/65 hover:text-[var(--gold)] whitespace-nowrap"><i class="fa-solid fa-bookmark"></i> Koleksi</a>
                            <a href="{{ route('perpustakaan', ['view' => 'saved']) }}" class="text-[10px] font-semibold text-white/65 hover:text-[var(--gold)] whitespace-nowrap"><i class="fa-solid fa-heart"></i> Tersimpan</a>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="cta-primary block text-center py-2.5 rounded-full text-xs font-bold text-white">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk untuk menyimpan buku
                        </a>
                    @endauth

                    @if($mode === 'rows' && $genreNav->isNotEmpty())
                        <div class="flex gap-2 overflow-x-auto pb-1" style="scrollbar-width:none;">
                            @foreach($genreNav as $g)
                                <a href="#{{ $g['slug'] }}" data-genre="{{ $g['key'] }}" class="genre-pill shrink-0 px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap transition">
                                    {{ $g['label'] }} <span class="opacity-60 ml-0.5">{{ $g['total'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Search box (preserve scope aktif) --}}
                <form method="GET" action="{{ route('perpustakaan') }}" class="relative mb-6" role="search">
                    @if($mode === 'genre' && !empty($genreKey))
                        <input type="hidden" name="category" value="{{ $genreKey }}">
                    @endif
                    @if(in_array($mode, ['collection', 'saved'], true))
                        <input type="hidden" name="view" value="{{ $mode }}">
                    @endif
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/40 text-sm pointer-events-none"></i>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari judul atau penulis buku..."
                           class="tsaqib-input w-full pl-11 pr-10 py-3 text-sm rounded-xl" aria-label="Cari buku">
                    @if(!empty($q))
                        @php
                            $clearQuery = array_filter([
                                'view' => in_array($mode, ['collection','saved'], true) ? $mode : null,
                                'category' => ($mode === 'genre') ? ($genreKey ?? null) : null,
                            ]);
                        @endphp
                        <a href="{{ route('perpustakaan', $clearQuery) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white" aria-label="Hapus pencarian">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>

                {{-- =================== MODE: carousel per-genre =================== --}}
                @if($mode === 'rows')
                    @if($genres->isNotEmpty())
                        <div class="space-y-10">
                            @foreach($genres as $g)
                                <section class="genre-section" id="{{ $g['slug'] }}" data-genre-section="{{ $g['key'] }}">
                                    <div class="flex items-end justify-between gap-3 mb-3">
                                        <div>
                                            <h2 class="font-display font-extrabold text-lg sm:text-xl text-[var(--cream)] tracking-tight">{{ $g['label'] }}</h2>
                                            <p class="text-[11px] text-white/40 mt-0.5">{{ $g['total'] }} buku</p>
                                        </div>
                                        <a href="{{ route('perpustakaan', ['category' => $g['key']]) }}"
                                           class="text-xs font-semibold text-[var(--gold)] hover:underline whitespace-nowrap inline-flex items-center gap-1">
                                            Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>

                                    <div class="relative" data-row>
                                        <div class="row-track flex gap-4 overflow-x-auto pb-2 -mx-1 px-1">
                                            @foreach($g['books'] as $book)
                                                <div class="w-36 sm:w-40 shrink-0">
                                                    <x-book-card :book="$book" :collection-ids="$collectionIds" :saved-ids="$savedIds" />
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="row-arrow row-prev" aria-label="Geser kiri"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                                        <button type="button" class="row-arrow row-next" aria-label="Geser kanan"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    @else
                        <div class="tsaqib-card-flat p-12 text-center">
                            <i class="fa-solid fa-book-bookmark text-3xl text-white/15 block mb-3"></i>
                            <p class="text-white/45 text-xs">Belum ada buku di perpustakaan.</p>
                        </div>
                    @endif

                {{-- ============== MODE: grid (genre / search / collection / saved) ============== --}}
                @else
                    @php
                        $gridHeading = [
                            'genre' => 'Genre: ' . ($genreLabel ?? ''),
                            'search' => 'Hasil pencarian: "' . ($q ?? '') . '"',
                            'collection' => 'Koleksi Saya',
                            'saved' => 'Tersimpan',
                        ][$mode] ?? 'Buku';
                        $gridSub = [
                            'genre' => 'Semua buku dalam genre ini.',
                            'collection' => 'Buku yang Anda kumpulkan ke koleksi pribadi.',
                            'saved' => 'Buku yang Anda tandai untuk dibaca nanti.',
                        ][$mode] ?? null;
                    @endphp

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h2 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight truncate">{{ $gridHeading }}</h2>
                            @if($gridSub)<p class="text-[11px] text-white/40 mt-0.5">{{ $gridSub }}</p>@endif
                        </div>
                        <a href="{{ route('perpustakaan') }}" class="text-xs text-white/50 hover:text-[var(--gold)] whitespace-nowrap inline-flex items-center gap-1 shrink-0">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i> Semua Genre
                        </a>
                    </div>

                    @if(!empty($books) && $books->isNotEmpty())
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                            @foreach($books as $book)
                                <x-book-card :book="$book" :collection-ids="$collectionIds" :saved-ids="$savedIds" />
                            @endforeach
                        </div>

                        @if($books->hasPages())
                            <div class="pt-6">{{ $books->links('partials.pagination') }}</div>
                        @endif
                    @else
                        <div class="tsaqib-card-flat p-12 text-center">
                            <i class="fa-solid fa-book-bookmark text-3xl text-white/15 block mb-3"></i>
                            <p class="text-white/45 text-xs">
                                @if($mode === 'search')
                                    Tidak ada buku yang cocok dengan pencarian "{{ $q }}".
                                @elseif(in_array($mode, ['collection','saved'], true))
                                    Belum ada buku di daftar ini. Simpan buku dari katalog untuk mengisinya.
                                @else
                                    Tidak ada buku pada kategori ini.
                                @endif
                            </p>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function () {
    var token = document.querySelector('meta[name="csrf-token"]');
    token = token ? token.getAttribute('content') : '';

    // Untuk fetch toggle; placeholder __ID__ diganti id buku.
    var toggleUrlTpl = "{{ route('perpustakaan.toggle', '__ID__') }}";
    var loginUrl = "{{ route('login') }}";

    /* ---------- Bookmark toggle (optimistic, sync semua instance buku+type) ---------- */
    document.querySelectorAll('.bookmark-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (btn.disabled) return;

            var bookId = btn.dataset.bookId;
            var type = btn.dataset.toggle;
            var wasOn = btn.classList.contains('is-on');

            btn.disabled = true;
            var matches = document.querySelectorAll(
                '.bookmark-btn[data-book-id="' + bookId + '"][data-toggle="' + type + '"]'
            );
            matches.forEach(function (b) {
                b.classList.toggle('is-on', !wasOn);
                b.classList.toggle('is-off', wasOn);
            });

            fetch(toggleUrlTpl.replace('__ID__', bookId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: type })
            }).then(function (res) {
                if (res.status === 401 || res.redirected) {
                    window.location.href = loginUrl;
                    return null;
                }
                if (!res.ok) throw new Error('http ' + res.status);
                return res.json();
            }).then(function (data) {
                if (!data) return;
                matches.forEach(function (b) {
                    b.classList.toggle('is-on', !!data.active);
                    b.classList.toggle('is-off', !data.active);
                });
            }).catch(function () {
                // revert saat gagal
                matches.forEach(function (b) {
                    b.classList.toggle('is-on', wasOn);
                    b.classList.toggle('is-off', !wasOn);
                });
            }).finally(function () {
                btn.disabled = false;
            });
        });
    });

    /* ---------- Panah carousel ---------- */
    document.querySelectorAll('[data-row]').forEach(function (row) {
        var track = row.querySelector('.row-track');
        var prev = row.querySelector('.row-prev');
        var next = row.querySelector('.row-next');
        if (!track) return;

        var step = function () { return Math.max(track.clientWidth * 0.85, 240); };
        if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
        if (next) next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });

        var sync = function () {
            if (prev) prev.classList.toggle('row-arrow-hidden', track.scrollLeft <= 4);
            if (next) next.classList.toggle('row-arrow-hidden', track.scrollLeft + track.clientWidth >= track.scrollWidth - 4);
        };
        track.addEventListener('scroll', sync, { passive: true });
        sync();
    });

    /* ---------- Highlight genre aktif saat scroll ---------- */
    var sections = document.querySelectorAll('[data-genre-section]');
    if ('IntersectionObserver' in window && sections.length) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var key = entry.target.dataset.genreSection;
                    document.querySelectorAll('[data-genre]').forEach(function (el) {
                        el.classList.toggle('active', el.dataset.genre === key);
                    });
                }
            });
        }, { rootMargin: '-25% 0px -65% 0px', threshold: 0 });
        sections.forEach(function (s) { io.observe(s); });
    }
})();
</script>
@endpush
