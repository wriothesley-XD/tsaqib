@extends('layouts.master')

@php
    $pageTitle = 'Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi';
@endphp

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap');

    /* ============================================================
       Redesign visual-only: dark #0a0e17 + emas #c9a94d (hover
       #e8cd85), border emas 10-20% opacity, tanpa gradient/glow.
       Judul = Cinzel, isi = EB Garamond. Markup/logic tak berubah.
       ============================================================ */
    .pl-page{ background:#0a0e17; }
    .pl-page .font-display{ font-family:'Cinzel',serif; }
    .pl-serif{ font-family:'EB Garamond',Georgia,serif; }

    /* Kartu unggulan & thumbnail (rasio 16:9, border emas tipis) */
    .pl-card{ background:#11161f; border:1px solid rgba(201,169,77,.14); border-radius:.5rem; transition:border-color .2s ease; }
    .pl-card:hover{ border-color:rgba(201,169,77,.38); }
    .pl-thumb{ background:#11161f; border:1px solid rgba(201,169,77,.14); }

    /* Motif dekoratif tunggal: garis putus-putus emas */
    .pl-divider{ border:0; border-top:1px dashed rgba(201,169,77,.25); }

    /* Badge status modul (Aktif = emerald, Segera = gold) */
    .pl-badge{ display:inline-flex;align-items:center;gap:.3rem;padding:.15rem .6rem;border-radius:999px;
               font-family:'Plus Jakarta Sans',sans-serif;font-size:10px;font-weight:700;
               letter-spacing:.08em;text-transform:uppercase; }
    .pl-badge-aktif{ background:rgba(16,185,129,.1); color:#6ee7b7; }
    .pl-badge-segera{ background:rgba(201,169,77,.1); color:#e8cd85; }

    /* Tombol aksi — border emas tipis (bukan solid terang) */
    .pl-btn{ display:inline-flex;align-items:center;justify-content:center;gap:.45rem;
             padding:.5rem 1.05rem;border-radius:.5rem;border:1px solid rgba(201,169,77,.35);
             font-family:'Plus Jakarta Sans',sans-serif;font-size:11px;font-weight:700;
             letter-spacing:.07em;text-transform:uppercase;color:#c9a94d;cursor:pointer;
             transition:color .2s ease,border-color .2s ease,background .2s ease; }
    .pl-btn:hover{ color:#e8cd85;border-color:#e8cd85;background:rgba(201,169,77,.06); }
    .pl-btn-sm{ padding:.32rem .7rem;font-size:10px; }
    .pl-btn-icon{ width:2.1rem;height:2.1rem;padding:0; }

    /* List ringkas: pemisah antar baris = garis putus-putus emas */
    .pl-list > li + li{ border-top:1px dashed rgba(201,169,77,.14); }

    /* Search & eyebrow senada palet */
    .pl-page .tsaqib-input{ background:#11161f; border-color:rgba(201,169,77,.16); }
    .pl-page .tsaqib-input:focus{ border-color:rgba(201,169,77,.45); }
    .pl-page .eyebrow-pill{ background:rgba(201,169,77,.08); border-color:rgba(201,169,77,.22); color:#c9a94d; }

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
        color:rgba(214,211,209,.7);
        transition:background .15s ease,color .15s ease;
    }
    .menu-link:hover{ background:rgba(201,169,77,.07); color:#e7e5e4; }
    .menu-link.active{ background:rgba(201,169,77,.12); color:#f5f5f4; }

    /* === Sidebar: link genre (desktop) === */
    .genre-link{
        display:flex;align-items:center;justify-content:space-between;
        padding:.55rem .8rem;border-radius:.6rem;
        font-size:.82rem;color:rgba(214,211,209,.7);
        transition:background .15s ease,color .15s ease;
    }
    .genre-link:hover{ background:rgba(201,169,77,.07); color:#e7e5e4; }
    .genre-link.active{ background:rgba(201,169,77,.12); color:#e8cd85; font-weight:700; }
    .genre-link .count{
        font-size:10px;padding:1px 7px;border-radius:999px;
        background:rgba(245,245,244,.07);color:rgba(214,211,209,.55);
    }
    .genre-link.active .count{ background:rgba(201,169,77,.16); color:#e8cd85; }

    /* === Mobile: chip genre === */
    .genre-pill{
        background:rgba(245,245,244,.04);color:rgba(214,211,209,.75);
        border:1px solid rgba(201,169,77,.16);
    }
    .genre-pill:hover{ background:rgba(201,169,77,.1); color:#e7e5e4; }
    .genre-pill.active{ background:rgba(201,169,77,.14); color:#e8cd85; border-color:rgba(201,169,77,.4); }

    /* === Tombol bookmark pada cover (state awal dari server, di-toggle JS) === */
    .bookmark-btn.is-off{ background:rgba(10,14,23,.7); color:rgba(245,245,244,.8); }
    .bookmark-btn.is-off:hover{ background:rgba(10,14,23,.9); }
    .bookmark-btn.is-collection.is-on{ background:#c9a94d; color:#0a0e17; }
    .bookmark-btn.is-saved.is-on{ background:#10b981; color:#06281d; }

    /* === Panah carousel === */
    .row-arrow{
        position:absolute;top:50%;transform:translateY(-50%);z-index:5;
        width:2.1rem;height:2.1rem;display:flex;align-items:center;justify-content:center;
        border-radius:999px;background:rgba(17,22,31,.92);border:1px solid rgba(201,169,77,.2);
        color:rgba(245,245,244,.85);cursor:pointer;
        transition:opacity .15s ease,background .15s ease,color .15s ease;
    }
    .row-arrow:hover{ background:#c9a94d; color:#0a0e17; }
    .row-prev{ left:-8px; }
    .row-next{ right:-8px; }
    .row-arrow-hidden{ opacity:0;pointer-events:none; }
    @media (max-width:1023px){ .row-arrow{ display:none; } } /* mobile: swipe pakai gesture */

    @media (prefers-reduced-motion: reduce){
        .pl-card,.pl-btn,.menu-link,.genre-link,.genre-pill,.row-arrow{ transition:none; }
    }
</style>
@endpush

@section('content')
<main class="pl-page flex-1 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        {{-- Hero ringkas --}}
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
            <p class="font-display text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.35em] text-[#c9a94d]">
                TSAQIB · FSI SMAN 1 Bukittinggi
            </p>
            <span class="eyebrow-pill inline-flex mt-4"><i class="fa-solid fa-book-open text-[10px]"></i> Maktabah Digital Publik FSI</span>
            <h1 class="font-display font-bold text-2xl sm:text-3xl text-stone-100 tracking-tight leading-tight mt-3">
                Perpustakaan Digital <span class="text-[#c9a94d]">PAI SMAN 1 Bukittinggi</span>
            </h1>
            <p class="pl-serif text-stone-500 text-sm sm:text-base mt-2 leading-relaxed">
                Akses publik buku digital, modul PAI, materi Aqidah, Fiqih, SKI, dan Hadits tanpa perlu login.
            </p>
            <hr class="pl-divider w-40 mx-auto mt-6">
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

                    <div class="flex items-start justify-between gap-3 mb-5">
                        <div class="min-w-0">
                            <h2 class="font-display font-bold text-xl text-stone-100 tracking-tight truncate">{{ $gridHeading }}</h2>
                            @if($gridSub)<p class="pl-serif text-[13px] text-stone-500 mt-1">{{ $gridSub }}</p>@endif
                        </div>
                        <a href="{{ route('perpustakaan') }}" class="text-xs text-stone-500 hover:text-[#e8cd85] whitespace-nowrap inline-flex items-center gap-1.5 shrink-0 cursor-pointer transition-colors">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i> Semua Genre
                        </a>
                    </div>

                    @if(!empty($books) && $books->isNotEmpty())
                        @php
                            // Split visual-only (tanpa query baru): 2 koleksi teratas
                            // tampil sebagai "unggulan", sisanya jadi list ringkas.
                            $plItems    = $books->getCollection();
                            $plUnggulan = $plItems->take(2)->values();
                            $plSisa     = $plItems->slice(2)->values();
                        @endphp

                        {{-- ===== 2 Modul Unggulan: thumbnail 16:9 + judul + guru + deskripsi ===== --}}
                        <div class="grid sm:grid-cols-2 gap-5 mb-8">
                            @foreach($plUnggulan as $book)
                                @php
                                    $plCover = $book->cover_image ? asset('storage/' . $book->cover_image) : null;
                                    $plPdf   = $book->pdf_path ? asset('storage/' . $book->pdf_path) : null;
                                @endphp
                                <article class="pl-card overflow-hidden flex flex-col">
                                    {{-- Thumbnail 16:9 — klik membuka PDF via mekanisme lama (tab baru) --}}
                                    <div class="relative aspect-video overflow-hidden border-b pl-thumb" style="border-color:rgba(201,169,77,.14);">
                                        @if($plCover)
                                            <img src="{{ $plCover }}" alt="Thumbnail {{ $book->title }}" loading="lazy"
                                                 class="absolute inset-0 w-full h-full object-cover">
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i class="fa-solid fa-file-pdf text-3xl text-[#c9a94d]/30"></i>
                                            </div>
                                        @endif

                                        @if($plPdf)
                                            {{-- Penanda modul bisa dibaca sebagai PDF --}}
                                            <span class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-black/60 text-[#e8cd85] text-[10px] font-bold tracking-wide">
                                                <i class="fa-solid fa-file-pdf text-[9px]"></i> PDF
                                            </span>
                                        @endif

                                        @auth
                                            <div class="absolute top-2 right-2 flex gap-1.5">
                                                <button type="button"
                                                        class="bookmark-btn is-collection w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ in_array($book->id, $collectionIds, true) ? 'is-on' : 'is-off' }}"
                                                        data-toggle="collection" data-book-id="{{ $book->id }}"
                                                        aria-label="Tambah ke Koleksi" title="Koleksi Saya">
                                                    <i class="fa-solid fa-bookmark"></i>
                                                </button>
                                                <button type="button"
                                                        class="bookmark-btn is-saved w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ in_array($book->id, $savedIds, true) ? 'is-on' : 'is-off' }}"
                                                        data-toggle="saved" data-book-id="{{ $book->id }}"
                                                        aria-label="Simpan buku" title="Tersimpan">
                                                    <i class="fa-solid fa-heart"></i>
                                                </button>
                                            </div>
                                        @endauth
                                    </div>

                                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            @if($plPdf)
                                                <span class="pl-badge pl-badge-aktif"><i class="fa-solid fa-circle text-[5px]"></i> Aktif</span>
                                            @else
                                                <span class="pl-badge pl-badge-segera"><i class="fa-solid fa-circle text-[5px]"></i> Segera</span>
                                            @endif
                                            <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-stone-500 truncate">{{ ucfirst($book->category) }}</span>
                                        </div>
                                        <h3 class="font-display font-bold text-stone-100 text-base sm:text-lg leading-snug mt-2.5 line-clamp-2">{{ $book->title }}</h3>
                                        <p class="text-xs text-stone-500 mt-1 truncate">{{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
                                        <p class="pl-serif text-[13px] leading-relaxed text-stone-400 mt-2 line-clamp-2">{{ $book->description ?: 'Deskripsi modul menyusul.' }}</p>

                                        <div class="mt-auto pt-4 flex items-center gap-2">
                                            @if($plPdf)
                                                <a href="{{ $plPdf }}" target="_blank" rel="noopener" class="pl-btn cursor-pointer">
                                                    <i class="fa-solid fa-book-open text-[10px]"></i> Baca Modul
                                                </a>
                                                <a href="{{ $plPdf }}" download class="pl-btn pl-btn-icon" title="Unduh PDF" aria-label="Unduh PDF">
                                                    <i class="fa-solid fa-download text-[10px]"></i>
                                                </a>
                                            @else
                                                <span class="pl-btn opacity-50 pointer-events-none" aria-disabled="true">
                                                    <i class="fa-solid fa-file-pdf text-[10px]"></i> Belum Tersedia
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- ===== Sisa koleksi: list ringkas (thumb kecil + judul + guru + status) ===== --}}
                        @if($plSisa->isNotEmpty())
                            <ul class="pl-list list-none">
                                @foreach($plSisa as $book)
                                    @php
                                        $plCover = $book->cover_image ? asset('storage/' . $book->cover_image) : null;
                                        $plPdf   = $book->pdf_path ? asset('storage/' . $book->pdf_path) : null;
                                    @endphp
                                    <li class="flex items-center gap-3 sm:gap-4 py-3.5">
                                        @if($plPdf)
                                            <a href="{{ $plPdf }}" target="_blank" rel="noopener"
                                               class="relative w-24 sm:w-32 shrink-0 aspect-video rounded-lg overflow-hidden pl-thumb block group">
                                                @if($plCover)
                                                    <img src="{{ $plCover }}" alt="Thumbnail {{ $book->title }}" loading="lazy"
                                                         class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <span class="absolute inset-0 flex items-center justify-center"><i class="fa-solid fa-file-pdf text-lg text-[#c9a94d]/40"></i></span>
                                                @endif
                                                <span class="absolute bottom-1 right-1 inline-flex items-center justify-center w-[18px] h-[18px] rounded bg-black/60 text-[#e8cd85]">
                                                    <i class="fa-solid fa-file-pdf text-[8px]"></i>
                                                </span>
                                            </a>
                                        @else
                                            <div class="relative w-24 sm:w-32 shrink-0 aspect-video rounded-lg overflow-hidden pl-thumb">
                                                @if($plCover)
                                                    <img src="{{ $plCover }}" alt="Thumbnail {{ $book->title }}" loading="lazy"
                                                         class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <span class="absolute inset-0 flex items-center justify-center"><i class="fa-solid fa-file-pdf text-lg text-[#c9a94d]/40"></i></span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <h3 class="font-display font-bold text-stone-100 text-sm truncate min-w-0">{{ $book->title }}</h3>
                                                @if($plPdf)
                                                    <span class="pl-badge pl-badge-aktif shrink-0"><i class="fa-solid fa-circle text-[5px]"></i> Aktif</span>
                                                @else
                                                    <span class="pl-badge pl-badge-segera shrink-0"><i class="fa-solid fa-circle text-[5px]"></i> Segera</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-stone-500 mt-1 truncate">
                                                {{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }} · {{ ucfirst($book->category) }}
                                            </p>
                                        </div>

                                        @auth
                                            <div class="hidden sm:flex gap-1.5 shrink-0">
                                                <button type="button"
                                                        class="bookmark-btn is-collection w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ in_array($book->id, $collectionIds, true) ? 'is-on' : 'is-off' }}"
                                                        data-toggle="collection" data-book-id="{{ $book->id }}"
                                                        aria-label="Tambah ke Koleksi" title="Koleksi Saya">
                                                    <i class="fa-solid fa-bookmark"></i>
                                                </button>
                                                <button type="button"
                                                        class="bookmark-btn is-saved w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ in_array($book->id, $savedIds, true) ? 'is-on' : 'is-off' }}"
                                                        data-toggle="saved" data-book-id="{{ $book->id }}"
                                                        aria-label="Simpan buku" title="Tersimpan">
                                                    <i class="fa-solid fa-heart"></i>
                                                </button>
                                            </div>
                                        @endauth

                                        <div class="shrink-0">
                                            @if($plPdf)
                                                <a href="{{ $plPdf }}" target="_blank" rel="noopener" class="pl-btn pl-btn-sm cursor-pointer">
                                                    <i class="fa-solid fa-book-open text-[9px]"></i> Baca
                                                </a>
                                            @else
                                                <span class="pl-btn pl-btn-sm opacity-50 pointer-events-none" aria-disabled="true">Segera</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

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
    // Pakai path relatif (bukan route() absolut) agar fetch tidak pernah
    // menjadi mixed-content http:// saat halaman disajikan lewat https://
    // di belakang proxy TLS (route() mengikuti skema request, yang bisa
    // salah saat proxy belum di-trust).
    var toggleUrlTpl = "/perpustakaan/books/__ID__/toggle";
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
