<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maktabah Digital - Pustaka Islami</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'islamic-dark': '#064e3b',   // Emerald 900
                        'islamic-main': '#047857',   // Emerald 600
                        'islamic-light': '#ecfdf5',  // Emerald 50
                        'islamic-gold': '#f59e0b',   // Amber 500
                        'bg-outer': '#f3f4f6'        // Abu-abu terang untuk luar container (seperti di screenshot)
                    }
                }
            }
        }
    </script>
</head>
<body>

    @include('partials.navbar')
    


                <!-- Search Bar (Tengah) -->
                <div class="flex-1 max-w-2xl mx-8 hidden md:block">
                    <div class="relative">
                        <input type="text" class="w-full bg-white text-gray-900 rounded-md pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-islamic-gold" placeholder="Cari Kitab, Penulis, Tafsir, atau Topik...">
                        <button class="absolute right-0 top-0 mt-2 mr-3 text-gray-500 hover:text-islamic-main">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="#" class="hidden md:block text-gray-200 hover:text-white text-sm font-medium">Masuk</a>
                    <a href="#" class="bg-islamic-gold hover:bg-yellow-500 text-islamic-dark px-4 py-2 rounded-md text-sm font-bold transition duration-150">Daftar</a>
                    <button class="md:hidden text-white"><i class="fa-solid fa-bars text-xl"></i></button>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER (Mendatar putih di tengah seperti Screenshot (808).jpg) -->
    <main class="max-w-7xl mx-auto bg-white min-h-screen shadow-sm border-x border-gray-200">
        
        <!-- WELCOME SECTION & FEATURES CARD -->
        <div class="px-8 py-10 border-b border-gray-100">
            <h1 class="text-2xl text-islamic-main font-semibold mb-6">Selamat Datang di Maktabah Digital</h1>
            
            <!-- Cards Container (Horizontal Scroll/Flex) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="border border-gray-200 rounded-lg p-5 flex items-center space-x-4 hover:shadow-md transition duration-200 hover:border-islamic-main group cursor-pointer">
                    <div class="text-4xl text-gray-400 group-hover:text-islamic-main transition">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Baca Kitab Gratis</h3>
                        <p class="text-sm text-gray-500 mt-1">Ribuan literatur Islam dan kitab kuning digital.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="border border-gray-200 rounded-lg p-5 flex items-center space-x-4 hover:shadow-md transition duration-200 hover:border-islamic-main group cursor-pointer">
                    <div class="text-4xl text-gray-400 group-hover:text-islamic-main transition">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Target Khatam</h3>
                        <p class="text-sm text-gray-500 mt-1">Atur jadwal membaca Al-Qur'an harian Anda.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="border border-gray-200 rounded-lg p-5 flex items-center space-x-4 hover:shadow-md transition duration-200 hover:border-islamic-main group cursor-pointer">
                    <div class="text-4xl text-gray-400 group-hover:text-islamic-main transition">
                        <i class="fa-solid fa-bookmark"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Simpan Favorit</h3>
                        <p class="text-sm text-gray-500 mt-1">Buat daftar bacaan buku sejarah & sirah nabawiyah.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRENDING BOOKS SECTION -->
        <div class="px-8 py-10 bg-gray-50/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl text-islamic-main font-semibold underline decoration-2 decoration-islamic-gold underline-offset-8">Kitab & Buku Populer</h2>
                <div class="flex space-x-2">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-full w-8 h-8 flex items-center justify-center"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="bg-islamic-main hover:bg-islamic-dark text-white rounded-full w-8 h-8 flex items-center justify-center"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Book Carousel (Flex overflow) -->
            <div class="flex space-x-6 overflow-x-auto pb-4 snap-x scrollbar-hide">
                <!-- Book Item 1 -->
                <div class="min-w-[160px] snap-start group">
                    <div class="w-full h-64 bg-emerald-800 rounded-md shadow-md flex items-center justify-center text-center p-4 border-2 border-transparent group-hover:border-islamic-gold transition relative overflow-hidden">
                        <!-- Placeholder gambar cover kitab -->
                        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                        <h3 class="text-islamic-gold font-serif font-bold z-10">Riyadhus<br>Shalihin</h3>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-gray-800 text-sm truncate">Riyadhus Shalihin</p>
                        <p class="text-xs text-gray-500">Imam Nawawi</p>
                    </div>
                </div>
                
                <!-- Book Item 2 -->
                <div class="min-w-[160px] snap-start group">
                    <div class="w-full h-64 bg-slate-800 rounded-md shadow-md flex items-center justify-center text-center p-4 border-2 border-transparent group-hover:border-islamic-gold transition relative overflow-hidden">
                         <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                        <h3 class="text-white font-serif font-bold z-10">Sirah<br>Nabawiyah</h3>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-gray-800 text-sm truncate">Sirah Nabawiyah</p>
                        <p class="text-xs text-gray-500">Shafiyurrahman</p>
                    </div>
                </div>

                <!-- Book Item 3 -->
                <div class="min-w-[160px] snap-start group">
                    <div class="w-full h-64 bg-amber-700 rounded-md shadow-md flex items-center justify-center text-center p-4 border-2 border-transparent group-hover:border-islamic-gold transition relative overflow-hidden">
                         <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                        <h3 class="text-white font-serif font-bold z-10">Al-Hikam</h3>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-gray-800 text-sm truncate">Al-Hikam</p>
                        <p class="text-xs text-gray-500">Ibn Atha'illah</p>
                    </div>
                </div>
                
                <!-- Book Item 4 -->
                <div class="min-w-[160px] snap-start group">
                    <div class="w-full h-64 bg-teal-900 rounded-md shadow-md flex items-center justify-center text-center p-4 border-2 border-transparent group-hover:border-islamic-gold transition relative overflow-hidden">
                         <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                        <h3 class="text-islamic-gold font-serif font-bold z-10">Fiqh<br>Sunnah</h3>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-gray-800 text-sm truncate">Fiqh Sunnah</p>
                        <p class="text-xs text-gray-500">Sayyid Sabiq</p>
                    </div>
                </div>
                
                <!-- Book Item 5 -->
                <div class="min-w-[160px] snap-start group">
                    <div class="w-full h-64 bg-amber-100 rounded-md shadow-md flex items-center justify-center text-center p-4 border-2 border-transparent group-hover:border-islamic-gold transition relative overflow-hidden">
                        <h3 class="text-gray-800 font-serif font-bold z-10 text-xl border-2 border-gray-800 p-2">القرآن</h3>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-gray-800 text-sm truncate">Mushaf Al-Qur'an</p>
                        <p class="text-xs text-gray-500">Kemenag RI</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>