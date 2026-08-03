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
        $books = \App\Models\Book::where('is_visible', true)->latest()->get();
        $tugas = \App\Models\Tugas::latest()->get();
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

    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 w-full">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-xs font-semibold rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

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

                <div class="pt-2 max-w-xl mx-auto">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" id="library-search" onkeyup="filterBooks()" placeholder="Cari judul buku, penulis, atau kata kunci..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-xs text-slate-900 focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F] shadow-inner">
                    </div>
                </div>
            </div>

            <!-- TAB UTAMA: Buku vs Tugas Siswa -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-center space-x-2 overflow-x-auto pb-2">
                <button onclick="showBooksView()" id="tab-buku"
                        class="main-tab-btn px-4 py-2 rounded-xl text-xs font-semibold bg-[#01795F] text-white transition whitespace-nowrap">
                    <i class="fa-solid fa-book mr-1"></i> Buku Digital
                </button>
                <button onclick="showTugasView()" id="tab-tugas"
                        class="main-tab-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">
                    <i class="fa-solid fa-file-pen mr-1"></i> Tugas Siswa
                </button>
            </div>

            <!-- CATEGORY FILTER TABS (khusus buku) -->
            <div id="book-category-tabs" class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center space-x-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-200">
                <button onclick="filterCategory('semua', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-[#01795F] text-white transition whitespace-nowrap">Semua Buku</button>
                <button onclick="filterCategory('fiqih', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Fiqih</button>
                <button onclick="filterCategory('aqidah', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Aqidah</button>
                <button onclick="filterCategory('ski', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">SKI</button>
                <button onclick="filterCategory('hadits', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Hadits & Tafsir</button>
                <button onclick="filterCategory('modul', this)" class="cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">Modul PAI</button>
            </div>
        </div>

        <!-- ======================= -->
        <!-- VIEW: BUKU DIGITAL      -->
        <!-- ======================= -->
        <div id="view-buku">
            <div id="books-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @forelse($books as $book)
                    <div class="book-card bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group"
                         data-title="{{ strtolower($book->title) }}"
                         data-author="{{ strtolower($book->author) }}"
                         data-category="{{ strtolower($book->category ?? 'modul') }}">

                        <div>
                            <div class="w-full h-36 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center p-3 relative overflow-hidden mb-3 group-hover:border-[#01795F] transition">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-xl mb-2">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-wider">Modul Digital</span>
                                @endif
                            </div>

                            <span class="text-[9px] font-bold text-[#01795F] uppercase tracking-wider block mb-1">
                                {{ $book->category ?? 'Modul PAI' }}
                            </span>
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-[#01795F] transition line-clamp-2 leading-snug">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Penulis: {{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                            @if($book->pdf_path)
                                <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank"
                                   class="flex-1 py-2 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-center font-semibold text-xs transition shadow-sm flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                    <span>Baca PDF</span>
                                </a>

                                <a href="{{ asset('storage/' . $book->pdf_path) }}" download
                                   class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition border border-slate-200" title="Unduh File">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            @else
                                <button onclick="alert('File PDF belum diunggah oleh admin.')" class="w-full py-2 rounded-xl bg-slate-100 text-slate-400 text-xs font-semibold">
                                    PDF Belum Tersedia
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
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
                                <div class="w-full h-36 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center p-3 relative overflow-hidden mb-3">
                                    <i class="fa-solid fa-book-bookmark text-4xl text-[#01795F] mb-2"></i>
                                    <span class="text-[10px] font-bold text-[#01795F] uppercase">{{ $sb['cat'] }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-[#01795F] uppercase block mb-1">{{ $sb['cat'] }}</span>
                                <h3 class="font-bold text-sm text-slate-900 group-hover:text-[#01795F] transition line-clamp-2 leading-snug">{{ $sb['title'] }}</h3>
                                <p class="text-xs text-slate-500 mt-1">Penulis: {{ $sb['author'] }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                                <a href="#" onclick="alert('Silakan unggah file PDF resmi di Admin Panel!'); return false;" class="flex-1 py-2 rounded-xl bg-[#01795F] text-white text-center font-semibold text-xs flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                    <span>Baca PDF</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforelse

            </div>
        </div>

        <!-- ======================= -->
        <!-- VIEW: TUGAS SISWA       -->
        <!-- ======================= -->
        <div id="view-tugas" class="hidden">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider mb-2">
                            Ruang Kumpul Tugas
                        </span>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Kumpulan Tugas <span class="text-[#01795F]">Siswa</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1">
                            Unggah dan lihat tugas PAI berdasarkan jenjang kelas.
                        </p>
                    </div>

                    <button onclick="document.getElementById('upload-tugas-modal').classList.remove('hidden')"
                            class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-xs font-semibold transition shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-upload text-[11px]"></i>
                        <span>Upload Tugas</span>
                    </button>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <label for="filter-kelas" class="text-xs font-semibold text-slate-700">Jenjang Kelas:</label>
                    <select id="filter-kelas" onchange="filterTugasByKelas(this.value)"
                            class="text-xs font-semibold border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F]">
                        <option value="semua">Semua Kelas</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>

                <div id="tugas-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    @forelse($tugas as $item)
                        <div class="tugas-card bg-slate-50 border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between"
                             data-kelas="{{ $item->kelas }}">
                            <div>
                                <span class="text-[9px] font-bold text-[#01795F] uppercase tracking-wider block mb-1">
                                    Kelas {{ $item->kelas }}
                                </span>
                                <h3 class="font-bold text-sm text-slate-900 line-clamp-2 leading-snug">
                                    {{ $item->judul }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Oleh: {{ $item->nama_siswa }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200 flex items-center space-x-2">
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank"
                                   class="flex-1 py-2 rounded-xl bg-white border border-slate-200 hover:border-[#01795F] text-slate-700 text-center font-semibold text-xs transition flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                    <span>Lihat</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 col-span-full text-center py-8">Belum ada tugas yang diunggah.</p>
                    @endforelse

                </div>
            </div>
        </div>

    </main>

    <!-- MODAL UPLOAD TUGAS -->
    <div id="upload-tugas-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg relative">
            <button onclick="document.getElementById('upload-tugas-modal').classList.add('hidden')"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h3 class="text-lg font-bold text-slate-900 mb-4">Upload Tugas Siswa</h3>

            <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Siswa</label>
                    <input type="text" name="nama_siswa" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas</label>
                    <select name="kelas" required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F]">
                        <option value="">Pilih Kelas</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Tugas</label>
                    <input type="text" name="judul" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#01795F] focus:ring-1 focus:ring-[#01795F]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">File Tugas (PDF/DOC)</label>
                    <input type="file" name="file_tugas" accept=".pdf,.doc,.docx" required
                           class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#01795F]/10 file:text-[#01795F] file:text-xs file:font-semibold">
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs transition">
                    Kirim Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        // Toggle antara view Buku dan view Tugas Siswa
        function showBooksView() {
            document.getElementById('view-buku').classList.remove('hidden');
            document.getElementById('view-tugas').classList.add('hidden');
            document.getElementById('book-category-tabs').classList.remove('hidden');

            document.getElementById('tab-buku').classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
            document.getElementById('tab-buku').classList.add('bg-[#01795F]', 'text-white');

            document.getElementById('tab-tugas').classList.remove('bg-[#01795F]', 'text-white');
            document.getElementById('tab-tugas').classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
        }

        function showTugasView() {
            document.getElementById('view-tugas').classList.remove('hidden');
            document.getElementById('view-buku').classList.add('hidden');
            document.getElementById('book-category-tabs').classList.add('hidden');

            document.getElementById('tab-tugas').classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
            document.getElementById('tab-tugas').classList.add('bg-[#01795F]', 'text-white');

            document.getElementById('tab-buku').classList.remove('bg-[#01795F]', 'text-white');
            document.getElementById('tab-buku').classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
        }

        function filterBooks() {
            const query = document.getElementById('library-search').value.toLowerCase();
            const cards = document.querySelectorAll('.book-card');
            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                const author = card.getAttribute('data-author');
                card.style.display = (title.includes(query) || author.includes(query)) ? 'flex' : 'none';
            });
        }

        function filterCategory(cat, clickedBtn) {
            if (clickedBtn) {
                const buttons = document.querySelectorAll('.cat-btn');
                buttons.forEach(btn => {
                    btn.classList.remove('bg-[#01795F]', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
                });
                clickedBtn.classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
                clickedBtn.classList.add('bg-[#01795F]', 'text-white');
            }

            const cards = document.querySelectorAll('.book-card');
            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                card.style.display = (cat === 'semua' || category === cat) ? 'flex' : 'none';
            });
        }

        function filterTugasByKelas(kelas) {
            const cards = document.querySelectorAll('.tugas-card');
            cards.forEach(card => {
                const cardKelas = card.getAttribute('data-kelas');
                card.style.display = (kelas === 'semua' || cardKelas === kelas) ? 'flex' : 'none';
            });
        }
    </script>

</body>
</html>