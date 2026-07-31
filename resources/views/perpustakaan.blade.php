<!-- resources/views/perpustakaan.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital FSI - TSAQIB SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $books = \App\Models\Book::latest()->get();
    @endphp

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar (6 Items) -->
    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 w-full">

        <!-- Top Header & Search Bar -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="max-w-3xl mx-auto text-center space-y-3">
                <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider">
                    Maktabah Digital Publik FSI
                </span>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                    Perpustakaan Digital <span class="text-[#01795F]">TSAQIB</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-600">
                    Akses publik buku digital, modul PAI, materi Aqidah, Fiqih, SKI, dan Hadits SMAN 1 Bukittinggi tanpa perlu login.
                </p>

                <!-- SEARCH BAR -->
                <div class="pt-2 max-w-xl mx-auto">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" id="library-search" onkeyup="filterBooks()" placeholder="Cari judul buku, penulis, atau kata kunci..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-xs text-slate-900 focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F] shadow-inner">
                    </div>
                </div>
            </div>

            <!-- CATEGORY FILTER TABS -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-center space-x-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-200">
                <button onclick="filterCategory('semua')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-[#01795F] text-white transition whitespace-nowrap">Semua Buku</button>
                <button onclick="filterCategory('fiqih')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Fiqih</button>
                <button onclick="filterCategory('aqidah')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Aqidah</button>
                <button onclick="filterCategory('ski')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">SKI</button>
                <button onclick="filterCategory('hadits')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Hadits & Tafsir</button>
                <button onclick="filterCategory('modul')" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Modul PAI</button>
            </div>
        </div>

        <!-- DIGITAL BOOKS GRID -->
        <div id="books-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            
            @forelse($books as $book)
                <div class="book-card bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group"
                     data-title="{{ strtolower($book->title) }}"
                     data-author="{{ strtolower($book->author) }}"
                     data-category="{{ strtolower($book->category ?? 'modul') }}">
                    
                    <div>
                        <!-- Cover PDF / Placeholder -->
                        <div class="w-full h-48 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center p-4 relative overflow-hidden mb-3 group-hover:border-[#01795F] transition">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-xl mb-2">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-wider">Modul Digital</span>
                            @endif
                        </div>

                        <!-- Book Title & Author -->
                        <span class="text-[9px] font-bold text-[#01795F] uppercase tracking-wider block mb-1">
                            {{ $book->category ?? 'Modul PAI' }}
                        </span>
                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-[#01795F] transition line-clamp-2 leading-snug">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Penulis: {{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
                    </div>

                    <!-- Read & Download Action Buttons -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                        <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank"
                           class="flex-1 py-2 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-center font-semibold text-xs transition shadow-sm flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-eye text-[11px]"></i>
                            <span>Baca PDF</span>
                        </a>

                        <a href="{{ asset('storage/' . $book->pdf_path) }}" download
                           class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition border border-slate-200" title="Unduh File">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>

                </div>
            @empty
                <!-- SAMPLE BOOKS FOR DEMO IF DB EMPTY -->
                @php
                    $sampleBooks = [
                        ['title' => 'Buku Panduan Fiqih Shalat Lanjutan', 'author' => 'Tim PAI SMAN 1 Bukittinggi', 'cat' => 'fiqih'],
                        ['title' => 'Ringkasan Aqidah & Akhlak Rabbani', 'author' => 'Ust. Pembina TSAQIB', 'cat' => 'aqidah'],
                        ['title' => 'Sejarah Kebudayaan Islam Masa Khulafaur Rasyidin', 'author' => 'Majelis SKI FSI', 'cat' => 'ski'],
                        ['title' => 'Modul Praktikum Ibadah Kelas X', 'author' => 'Laboratorium PAI', 'cat' => 'modul'],
                    ];
                @endphp
                @foreach($sampleBooks as $sb)
                    <div class="book-card bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group"
                         data-title="{{ strtolower($sb['title']) }}"
                         data-author="{{ strtolower($sb['author']) }}"
                         data-category="{{ strtolower($sb['cat']) }}">
                        <div>
                            <div class="w-full h-48 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center p-4 relative overflow-hidden mb-3">
                                <i class="fa-solid fa-book-bookmark text-4xl text-[#01795F] mb-2"></i>
                                <span class="text-[10px] font-bold text-[#01795F] uppercase">{{ $sb['cat'] }}</span>
                            </div>
                            <span class="text-[9px] font-bold text-[#01795F] uppercase block mb-1">{{ $sb['cat'] }}</span>
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-[#01795F] transition line-clamp-2 leading-snug">{{ $sb['title'] }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Penulis: {{ $sb['author'] }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                            <a href="#" onclick="alert('Silakan upload file PDF resmi di Admin Panel!'); return false;" class="flex-1 py-2 rounded-xl bg-[#01795F] text-white text-center font-semibold text-xs">
                                <i class="fa-solid fa-eye text-[11px] mr-1"></i>Baca PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforelse

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

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

        function filterCategory(cat) {
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

</body>
</html>