@extends('layouts.master')

@php
    $pageTitle = 'Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi';
@endphp

@push('styles')
<style>
    /* Perpustakaan Custom Styling aligned with TSAQIB theme */
    .row-track { scrollbar-width: none; -ms-overflow-style: none; }
    .row-track::-webkit-scrollbar { display: none; }
    .genre-section { scroll-margin-top: 6.5rem; }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.75rem;
        border-radius: 0.65rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: rgba(247, 245, 239, 0.7);
        transition: all 0.15s ease;
    }
    .menu-link:hover { background: rgba(247, 245, 239, 0.06); color: var(--cream); }
    .menu-link.active { background: rgba(1, 121, 95, 0.2); color: var(--gold); border-left: 2px solid var(--gold); }

    .genre-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        border-radius: 0.6rem;
        font-size: 0.8125rem;
        color: rgba(247, 245, 239, 0.7);
        transition: all 0.15s ease;
    }
    .genre-link:hover { background: rgba(247, 245, 239, 0.06); color: var(--cream); }
    .genre-link.active { background: rgba(201, 166, 107, 0.15); color: var(--gold); font-weight: 700; border-left: 2px solid var(--gold); }
    .genre-link .count {
        font-size: 10px;
        padding: 1px 7px;
        border-radius: 999px;
        background: rgba(247, 245, 239, 0.08);
        color: rgba(247, 245, 239, 0.5);
    }
    .genre-link.active .count { background: rgba(201, 166, 107, 0.2); color: var(--gold); }

    .genre-pill {
        background: rgba(247, 245, 239, 0.05);
        color: rgba(247, 245, 239, 0.75);
        border: 1px solid rgba(247, 245, 239, 0.12);
    }
    .genre-pill:hover { background: rgba(247, 245, 239, 0.1); color: var(--cream); }
    .genre-pill.active { background: rgba(1, 121, 95, 0.25); color: var(--gold); border-color: rgba(201, 166, 107, 0.4); }

    /* Carousel navigation arrows */
    .row-arrow {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        z-index: 10;
        width: 2.25rem;
        height: 2.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: rgba(13, 40, 24, 0.92);
        border: 1px solid rgba(201, 166, 107, 0.35);
        color: var(--cream);
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    .row-arrow:hover { background: var(--gold); color: var(--green-s0); border-color: var(--gold); }
    .row-prev { left: -10px; }
    .row-next { right: -10px; }
    .row-arrow-hidden { opacity: 0; pointer-events: none; }
    @media (max-width: 1023px) { .row-arrow { display: none; } }
</style>
@endpush

@section('content')
<main class="flex-1 w-full relative">
    <div class="pat-islami"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10 space-y-8">

        {{-- Hero Header --}}
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="eyebrow-pill eyebrow-pill-green">
                <i class="fa-solid fa-book-open text-[10px]"></i> Maktabah Digital Publik FSI
            </span>
            <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-[var(--cream)] tracking-tight">
                Perpustakaan Digital <span class="text-[var(--gold)]">PAI SMAN 1 Bukittinggi</span>
            </h1>
            <p class="text-white/65 text-xs sm:text-sm leading-relaxed">
                Akses terbuka katalog buku digital, modul PAI kurikulum resmi, risalah Fikih, SKI, dan Hadits tanpa kewajiban login.
            </p>
        </div>

        {{-- Dua Kolom: Sidebar Sticky (lg+) + Konten Utama --}}
        <div class="lg:grid lg:grid-cols-[250px_minmax(0,1fr)] lg:gap-8 xl:gap-10 items-start">

            {{-- ============================ SIDEBAR (lg+) ============================ --}}
            <aside class="hidden lg:block sticky top-24 space-y-4">

                {{-- User Info / CTA Masuk --}}
                @auth
                    <div class="tsaqib-card p-4 flex items-center gap-3">
                        <x-community-avatar :user="Auth::user()" size="md" />
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-xs text-[var(--cream)] truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-[var(--gold)] truncate">{{ Auth::user()->selected_community ?: 'Anggota TSAQIB' }}</p>
                        </div>
                    </div>
                @else
                    <div class="tsaqib-card p-5 text-center space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-[var(--gold)] text-sm">
                            <i class="fa-solid fa-bookmark"></i>
                        </div>
                        <p class="text-xs text-white/70 leading-snug">
                            Masuk untuk menandai buku &amp; membangun koleksi pribadi.
                        </p>
                        <a href="{{ route('login') }}" class="btn-primary w-full py-2 text-xs mt-2">Masuk</a>
                    </div>
                @endauth

                {{-- Menu Navigasi --}}
                <nav class="tsaqib-card p-2 space-y-0.5">
                    <a href="{{ route('perpustakaan') }}" class="menu-link {{ $mode === 'rows' ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group text-[var(--gold)] w-4 text-center text-xs"></i>
                        <span>Semua Genre</span>
                    </a>
                    @auth
                        <a href="{{ route('perpustakaan', ['view' => 'collection']) }}" class="menu-link {{ $mode === 'collection' ? 'active' : '' }}">
                            <i class="fa-solid fa-bookmark text-[var(--gold)] w-4 text-center text-xs"></i>
                            <span>Koleksi Saya</span>
                        </a>
                        <a href="{{ route('perpustakaan', ['view' => 'saved']) }}" class="menu-link {{ $mode === 'saved' ? 'active' : '' }}">
                            <i class="fa-solid fa-heart text-[var(--gold)] w-4 text-center text-xs"></i>
                            <span>Tersimpan</span>
                        </a>
                    @endauth
                </nav>

                {{-- Daftar Genre --}}
                <div class="tsaqib-card p-3">
                    <p class="px-2 pt-1 pb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">Daftar Kategori</p>
                    @if($genreNav->isNotEmpty())
                        <div class="space-y-0.5">
                            @foreach($genreNav as $g)
                                @php
                                    $gHref = $mode === 'rows' ? '#' . $g['slug'] : route('perpustakaan', ['category' => $g['key']]);
                                    $gActive = $mode === 'rows' ? false : ($mode === 'genre' && ($genreKey ?? null) === $g['key']);
                                @endphp
                                <a href="{{ $gHref }}" data-genre="{{ $g['key'] }}" class="genre-link {{ $gActive ? 'active' : '' }}">
                                    <span class="truncate">{{ $g['label'] }}</span>
                                    <span class="count">{{ $g['total'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="px-2 py-2 text-xs text-white/40">Belum ada kategori.</p>
                    @endif
                </div>

            </aside>

            {{-- ============================ KONTEN UTAMA ============================ --}}
            <div class="min-w-0 space-y-6">

                {{-- Mobile user bar & chips (< lg) --}}
                <div class="lg:hidden space-y-3">
                    @auth
                        <div class="tsaqib-card p-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <x-community-avatar :user="Auth::user()" size="xs" />
                                <p class="font-bold text-xs text-[var(--cream)] truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('perpustakaan', ['view' => 'collection']) }}" class="text-[10px] font-bold text-[var(--gold)] px-2.5 py-1 rounded-lg bg-white/5 border border-white/10">
                                    <i class="fa-solid fa-bookmark mr-1"></i>Koleksi
                                </a>
                                <a href="{{ route('perpustakaan', ['view' => 'saved']) }}" class="text-[10px] font-bold text-[var(--gold)] px-2.5 py-1 rounded-lg bg-white/5 border border-white/10">
                                    <i class="fa-solid fa-heart mr-1"></i>Tersimpan
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary w-full py-2.5 text-xs text-center">
                            <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk untuk menyimpan buku
                        </a>
                    @endauth

                    @if($mode === 'rows' && $genreNav->isNotEmpty())
                        <div class="flex gap-2 overflow-x-auto pb-1" style="scrollbar-width: none;">
                            @foreach($genreNav as $g)
                                <a href="#{{ $g['slug'] }}" data-genre="{{ $g['key'] }}" class="genre-pill shrink-0 px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap">
                                    {{ $g['label'] }} <span class="opacity-60 ml-0.5">{{ $g['total'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Search Box --}}
                <form action="{{ route('perpustakaan') }}" method="GET" class="relative flex-1 input-glow-group">
                    @if($mode === 'genre' && !empty($genreKey))
                        <input type="hidden" name="category" value="{{ $genreKey }}">
                    @endif
                    @if(in_array($mode, ['collection', 'saved'], true))
                        <input type="hidden" name="view" value="{{ $mode }}">
                    @endif
                    <i class="fa-solid fa-magnifying-glass input-icon absolute left-4 top-1/2 -translate-y-1/2 text-white/40 text-xs pointer-events-none"></i>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari judul buku, penulis, atau kata kunci..."
                           class="tsaqib-input w-full pl-10 pr-10 py-2.5 text-xs rounded-xl" aria-label="Cari buku">
                    @if(!empty($q))
                        @php
                            $clearQuery = array_filter([
                                'view' => in_array($mode, ['collection','saved'], true) ? $mode : null,
                                'category' => ($mode === 'genre') ? ($genreKey ?? null) : null,
                            ]);
                        @endphp
                        <a href="{{ route('perpustakaan', $clearQuery) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/40 hover:text-white" aria-label="Hapus pencarian">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </a>
                    @endif
                </form>

                {{-- =================== MODE: CAROUSEL PER GENRE =================== --}}
                @if($mode === 'rows')
                    @if($genres->isNotEmpty())
                        <div class="space-y-10">
                            @foreach($genres as $g)
                                <section class="genre-section" id="{{ $g['slug'] }}" data-genre-section="{{ $g['key'] }}">
                                    <div class="flex items-end justify-between gap-3 mb-3.5 pb-2 border-b border-white/5">
                                        <div>
                                            <h2 class="font-display font-bold text-lg sm:text-xl text-[var(--cream)] tracking-tight">{{ $g['label'] }}</h2>
                                            <p class="text-[11px] text-white/45 mt-0.5">{{ $g['total'] }} buku tersedia</p>
                                        </div>
                                        <a href="{{ route('perpustakaan', ['category' => $g['key']]) }}"
                                           class="text-xs font-bold text-[var(--gold)] hover:underline inline-flex items-center gap-1">
                                            Lihat Semua <i class="fa-solid fa-arrow-right text-[9px]"></i>
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
                        <div class="tsaqib-card-flat p-12 text-center text-white/40 text-xs">
                            <i class="fa-solid fa-book-bookmark text-3xl text-white/20 block mb-2"></i>
                            <p>Belum ada buku dalam katalog perpustakaan.</p>
                        </div>
                    @endif

                {{-- =================== MODE: GRID / SEARCH / KOLEKSI =================== --}}
                @else
                    @php
                        $gridHeading = [
                            'genre' => 'Kategori: ' . ($genreLabel ?? ''),
                            'search' => 'Hasil Pencarian: "' . ($q ?? '') . '"',
                            'collection' => 'Koleksi Saya',
                            'saved' => 'Buku Tersimpan',
                        ][$mode] ?? 'Daftar Buku';
                        $gridSub = [
                            'genre' => 'Semua modul dan kitab dalam kategori ini.',
                            'collection' => 'Buku yang telah Anda tambahkan ke koleksi belajar pribadi.',
                            'saved' => 'Daftar buku yang Anda tandai untuk dibaca nanti.',
                        ][$mode] ?? null;
                    @endphp

                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-white/10">
                        <div>
                            <h2 class="font-display font-bold text-xl text-[var(--cream)] tracking-tight">{{ $gridHeading }}</h2>
                            @if($gridSub)<p class="text-xs text-white/50 mt-1">{{ $gridSub }}</p>@endif
                        </div>
                        <a href="{{ route('perpustakaan') }}" class="btn-outline py-1.5 px-3 text-xs shrink-0">
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
                        <div class="tsaqib-card-flat p-12 text-center text-white/40 text-xs">
                            <i class="fa-solid fa-book-bookmark text-3xl text-white/20 block mb-2"></i>
                            <p>
                                @if($mode === 'search')
                                    Tidak ada buku yang cocok dengan kata kunci "{{ $q }}".
                                @elseif(in_array($mode, ['collection','saved'], true))
                                    Daftar ini masih kosong. Telusuri katalog untuk menambahkan buku.
                                @else
                                    Belum ada buku pada kategori ini.
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
    var toggleUrlTpl = "/perpustakaan/books/__ID__/toggle";
    var loginUrl = "{{ route('login') }}";

    // Bookmark button click handler
    document.querySelectorAll('.bookmark-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (btn.disabled) return;

            var bookId = btn.dataset.bookId;
            var type = btn.dataset.toggle;
            var wasOn = btn.classList.contains('is-on');

            btn.disabled = true;
            var matches = document.querySelectorAll('.bookmark-btn[data-book-id="' + bookId + '"][data-toggle="' + type + '"]');
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
                if (!res.ok) throw new Error();
                return res.json();
            }).then(function (data) {
                if (!data) return;
                matches.forEach(function (b) {
                    b.classList.toggle('is-on', !!data.active);
                    b.classList.toggle('is-off', !data.active);
                });
            }).catch(function () {
                matches.forEach(function (b) {
                    b.classList.toggle('is-on', wasOn);
                    b.classList.toggle('is-off', !wasOn);
                });
            }).finally(function () {
                btn.disabled = false;
            });
        });
    });

    // Row track horizontal scrolling arrows
    document.querySelectorAll('[data-row]').forEach(function (row) {
        var track = row.querySelector('.row-track');
        var prev = row.querySelector('.row-prev');
        var next = row.querySelector('.row-next');
        if (!track) return;

        var step = function () { return Math.max(track.clientWidth * 0.8, 240); };
        if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
        if (next) next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });

        var sync = function () {
            if (prev) prev.classList.toggle('row-arrow-hidden', track.scrollLeft <= 4);
            if (next) next.classList.toggle('row-arrow-hidden', track.scrollLeft + track.clientWidth >= track.scrollWidth - 4);
        };
        track.addEventListener('scroll', sync, { passive: true });
        sync();
    });

    // Genre scroll highlight
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
