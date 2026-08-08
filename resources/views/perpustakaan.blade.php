@extends('layouts.master')

@php
    $pageTitle = 'Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi';
    $books = \App\Models\Book::where('is_visible', true)->latest()->get();
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
                    <!-- SEARCH BAR -->
                    <div class="max-w-xl mx-auto">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-white/40 text-sm"></i>
                            <input type="text" id="library-search" onkeyup="filterBooks()" placeholder="Cari judul buku, penulis, atau kata kunci..."
                                   class="tsaqib-input w-full pl-11 pr-4 py-3 text-xs">
                        </div>
                    </div>
                </x-slot:extra>
            </x-page-header>

            <!-- CATEGORY FILTER TABS -->
            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-center space-x-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-white/10">
                <button onclick="filterCategory('semua', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-[#01795F] text-white transition whitespace-nowrap">Semua Buku</button>
                <button onclick="filterCategory('fiqih', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white/5 text-white/70 hover:bg-white/10 transition whitespace-nowrap">Fiqih</button>
                <button onclick="filterCategory('aqidah', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white/5 text-white/70 hover:bg-white/10 transition whitespace-nowrap">Aqidah</button>
                <button onclick="filterCategory('ski', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white/5 text-white/70 hover:bg-white/10 transition whitespace-nowrap">SKI</button>
                <button onclick="filterCategory('hadits', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white/5 text-white/70 hover:bg-white/10 transition whitespace-nowrap">Hadits & Tafsir</button>
                <button onclick="filterCategory('modul', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white/5 text-white/70 hover:bg-white/10 transition whitespace-nowrap">Modul PAI</button>
            </div>
        </div>

        <!-- DIGITAL BOOKS GRID (DATABASE DRIVEN) -->
        <div id="books-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @forelse($books as $book)
                <div class="book-card tsaqib-card p-4 flex flex-col justify-between group"
                     data-title="{{ strtolower($book->title) }}"
                     data-author="{{ strtolower($book->author) }}"
                     data-category="{{ strtolower($book->category ?? 'modul') }}">

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
                <!-- SAMPLE BOOKS FOR DEMO IF DB EMPTY -->
                @php
                    $sampleBooks = [

                    ];
                @endphp
                @foreach($sampleBooks as $sb)
                    <div class="book-card tsaqib-card p-4 flex flex-col justify-between group"
                         data-title="{{ strtolower($sb['title']) }}"
                         data-author="{{ strtolower($sb['author']) }}"
                         data-category="{{ strtolower($sb['cat']) }}">
                        <div>
                            <div class="w-full h-36 rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center p-3 relative overflow-hidden mb-3">
                                <i class="fa-solid fa-book-bookmark text-4xl text-[#3fd6b0] mb-2"></i>
                                <span class="text-[10px] font-bold text-[#3fd6b0] uppercase">{{ $sb['cat'] }}</span>
                            </div>
                            <span class="text-[9px] font-bold text-[var(--gold)] uppercase block mb-1">{{ $sb['cat'] }}</span>
                            <h3 class="font-bold text-sm text-[var(--cream)] group-hover:text-[var(--gold)] transition line-clamp-2 leading-snug">{{ $sb['title'] }}</h3>
                            <p class="text-xs text-white/50 mt-1">Penulis: {{ $sb['author'] }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/10 flex items-center space-x-2">
                            <a href="#" onclick="alert('Silakan unggah file PDF resmi di Admin Panel!'); return false;" class="flex-1 py-2 rounded-xl bg-[#01795F] text-white text-center font-semibold text-xs flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span>Baca PDF</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforelse

        </div>

    </main>
@endsection

@push('scripts')
    <script>
        function filterBooks() {
            const query = document.getElementById('library-search').value.toLowerCase();
            const cards = document.querySelectorAll('.book-card');
            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                const author = card.getAttribute('data-author');
                if (title.includes(query) || author.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter Category + ubah warna tombol (palet gelap)
        function filterCategory(cat, clickedBtn) {

            // 1. Logika ubah warna/tampilan tombol
            if (clickedBtn) {
                const buttons = document.querySelectorAll('.cat-btn');

                buttons.forEach(btn => {
                    btn.classList.remove('bg-[#01795F]', 'text-white');
                    btn.classList.add('bg-white/5', 'text-white/70', 'hover:bg-white/10');
                });

                clickedBtn.classList.remove('bg-white/5', 'text-white/70', 'hover:bg-white/10');
                clickedBtn.classList.add('bg-[#01795F]', 'text-white');
            }

            // 2. Logika memunculkan/menyembunyikan buku
            const cards = document.querySelectorAll('.book-card');
            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (cat === 'semua' || category === cat) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
@endpush
