@extends('layouts.master')

@php
    $pageTitle = 'Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi';

    // Kategori filter (server-side). 'semua' = tanpa filter kategori.
    $bookCategories = [
        'semua'  => 'Semua Buku',
        'fiqih'  => 'Fiqih',
        'aqidah' => 'Aqidah',
        'ski'    => 'SKI',
        'hadits' => 'Hadits & Tafsir',
        'modul'  => 'Modul PAI',
    ];
    $activeCat = in_array($category, array_keys($bookCategories), true) ? $category : 'semua';
@endphp

@section('content')
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 w-full">

        <!-- Top Header & Search Bar -->
        <div class="tsaqib-card p-6 sm:p-8">
            <x-page-header
                eyebrow="Maktabah Digital Publik FSI"
                eyebrow-icon="fa-solid fa-book-open"
                title="Perpustakaan Digital <span class='text-[var(--gold)]'>PAI SMAN 1 Bukittinggi</span>"
                subtitle="Akses publik buku digital, modul PAI, materi Aqidah, Fiqih, SKI, dan Hadits SMAN 1 Bukittinggi tanpa perlu login.">
                <x-slot:extra>
                    <!-- SEARCH BAR (server-side GET). Hidden category agar pencarian
                         tidak lupa kategori yang sedang aktif. Submit -> reset ke page 1. -->
                    <form method="GET" action="{{ route('perpustakaan') }}" class="max-w-xl mx-auto" role="search">
                        <input type="hidden" name="category" value="{{ $activeCat }}">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-white/40 text-sm pointer-events-none"></i>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul buku, penulis, atau kata kunci..."
                                   class="tsaqib-input w-full pl-11 pr-4 py-3 text-xs" aria-label="Cari buku">
                        </div>
                    </form>
                </x-slot:extra>
            </x-page-header>

            <!-- CATEGORY FILTER TABS (server-side via ?category=, pertahankan ?q=) -->
            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-center space-x-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-white/10">
                @foreach($bookCategories as $key => $label)
                    @php
                        $catQuery = ['category' => $key];
                        if ($q !== '') {
                            $catQuery['q'] = $q;
                        }
                    @endphp
                    <a href="{{ route('perpustakaan', $catQuery) }}"
                       class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $activeCat === $key ? 'bg-[#01795F] text-white' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- DIGITAL BOOKS GRID (DATABASE DRIVEN, paginated) -->
        <div id="books-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @forelse($books as $book)
                <div class="tsaqib-card p-4 flex flex-col justify-between group">

                    <div>
                        <!-- Cover PDF / Placeholder -->
                        <div class="w-full h-36 rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center p-3 relative overflow-hidden mb-3 group-hover:border-[#01795F] transition">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center font-bold text-xl mb-2">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <span class="text-[10px] font-bold text-[#3fd6b0] uppercase tracking-wider">Modul Digital</span>
                            @endif
                        </div>

                        <!-- Book Title & Author -->
                        <span class="text-[9px] font-bold text-[var(--gold)] uppercase tracking-wider block mb-1">
                            {{ $book->category ?? 'Modul PAI' }}
                        </span>
                        <h3 class="font-bold text-sm text-[var(--cream)] group-hover:text-[var(--gold)] transition line-clamp-2 leading-snug">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-white/50 mt-1">Penulis: {{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
                    </div>

                    <!-- Read & Download Action Buttons -->
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center space-x-2">
                        @if($book->pdf_path)
                            <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank"
                               class="flex-1 py-2 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-center font-semibold text-xs transition shadow-sm flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span>Baca PDF</span>
                            </a>

                            <a href="{{ asset('storage/' . $book->pdf_path) }}" download
                               class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white/80 text-xs font-semibold transition border border-white/15" title="Unduh File">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        @else
                            <button onclick="alert('File PDF belum diunggah oleh admin.')" class="w-full py-2 rounded-xl bg-white/5 text-white/40 text-xs font-semibold">
                                PDF Belum Tersedia
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-full tsaqib-card-flat p-10 text-center">
                    <i class="fa-solid fa-book-bookmark text-3xl text-white/15 block mb-3"></i>
                    <p class="text-white/45 text-xs">
                        Tidak ada buku yang cocok dengan pencarian atau kategori ini.
                    </p>
                </div>
            @endforelse

        </div>

        {{-- Pagination controls (themed) --}}
        @if ($books->hasPages())
            <div class="pt-2">
                {{ $books->links('partials.pagination') }}
            </div>
        @endif

    </main>
@endsection
