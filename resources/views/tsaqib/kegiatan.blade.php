<!-- resources/views/tsaqib/kegiatan.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Informasi Kegiatan - FSI SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Title Banner -->
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-extrabold uppercase tracking-widest mb-3">
                <i class="fa-solid fa-newspaper"></i>
                <span>Feed Artikel & Berita Kegiatan</span>
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Informasi Kegiatan <span class="bg-gradient-to-r from-amber-400 to-amber-200 bg-clip-text text-transparent">FSI TSAQIB</span>
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2">
                Kajian, Mabit, Lomba, Bakti Sosial, Pengumuman, dan Dokumentasi Kegiatan FSI SMAN 1 Bukittinggi.
            </p>
        </div>

        <!-- Article Feed Category Pills -->
        <div class="flex items-center justify-center space-x-2 overflow-x-auto pb-2 scrollbar-none">
            <button class="px-4 py-2 rounded-full text-xs font-bold bg-amber-500 text-slate-950 shadow-md">Semua Postingan</button>
            <button class="px-4 py-2 rounded-full text-xs font-bold bg-slate-900 border border-slate-800 text-slate-300 hover:text-white">Kajian & Mentoring</button>
            <button class="px-4 py-2 rounded-full text-xs font-bold bg-slate-900 border border-slate-800 text-slate-300 hover:text-white">Mabit & Gathering</button>
            <button class="px-4 py-2 rounded-full text-xs font-bold bg-slate-900 border border-slate-800 text-slate-300 hover:text-white">Bakti Sosial</button>
            <button class="px-4 py-2 rounded-full text-xs font-bold bg-slate-900 border border-slate-800 text-slate-300 hover:text-white">Pengumuman</button>
        </div>

        <!-- ARTICLE FEED GRID (Blog Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Post 1: Kajian -->
            <article class="bg-slate-900/90 border border-slate-800 rounded-3xl overflow-hidden backdrop-blur-md shadow-2xl hover:border-amber-500/50 transition duration-300 flex flex-col">
                <div class="h-44 bg-emerald-950 relative flex items-center justify-center p-4">
                    <i class="fa-solid fa-bullhorn text-5xl text-emerald-400/40"></i>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Kajian</span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 mb-2">
                            <span><i class="fa-regular fa-calendar mr-1"></i>Setiap Jumat</span>
                            <span>•</span>
                            <span><i class="fa-regular fa-clock mr-1"></i>13:30 WIB</span>
                        </div>
                        <h3 class="font-bold text-white text-base leading-snug mb-2">Kajian Senja Rutin Pekanan & Mentoring Karakter</h3>
                        <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                            Kajian rutin mingguan membahas fiqih pemuda, tazkiyatun nufs, dan kepemimpinan di Mushalla SMAN 1 Bukittinggi.
                        </p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-amber-400 uppercase">Pengurus Dakwah</span>
                        <span class="text-xs font-semibold text-slate-400">Selengkapnya &rarr;</span>
                    </div>
                </div>
            </article>

            <!-- Post 2: Mabit & Night Of Faith -->
            <article class="bg-slate-900/90 border border-slate-800 rounded-3xl overflow-hidden backdrop-blur-md shadow-2xl hover:border-amber-500/50 transition duration-300 flex flex-col">
                <div class="h-44 bg-teal-950 relative flex items-center justify-center p-4">
                    <i class="fa-solid fa-moon text-5xl text-teal-400/40"></i>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-500/20 text-teal-300 border border-teal-400/30">Mabit</span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 mb-2">
                            <span><i class="fa-regular fa-calendar mr-1"></i>Akhir Bulan</span>
                            <span>•</span>
                            <span><i class="fa-regular fa-moon mr-1"></i>Malam Sabtu</span>
                        </div>
                        <h3 class="font-bold text-white text-base leading-snug mb-2">Malam Bina Iman dan Taqwa (MABIT) Siswa</h3>
                        <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                            Kegiatan menginap di sekolah diisi dengan qiyamul lail, muhasabah, muhadatsah Al-Qur'an, dan shalat subuh berjamaah.
                        </p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-amber-400 uppercase">Sie Acara</span>
                        <span class="text-xs font-semibold text-slate-400">Selengkapnya &rarr;</span>
                    </div>
                </div>
            </article>

            <!-- Post 3: Bakti Sosial -->
            <article class="bg-slate-900/90 border border-slate-800 rounded-3xl overflow-hidden backdrop-blur-md shadow-2xl hover:border-amber-500/50 transition duration-300 flex flex-col">
                <div class="h-44 bg-amber-950 relative flex items-center justify-center p-4">
                    <i class="fa-solid fa-hand-holding-heart text-5xl text-amber-400/40"></i>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-400/30">Bakti Sosial</span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 mb-2">
                            <span><i class="fa-regular fa-calendar mr-1"></i>Jumat Berkah</span>
                            <span>•</span>
                            <span><i class="fa-solid fa-location-dot mr-1"></i>Bukittinggi</span>
                        </div>
                        <h3 class="font-bold text-white text-base leading-snug mb-2">Safari Masjid & Penyaluran Berkah Ramadan</h3>
                        <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                            Aksi sosial penyaluran bantuan paket sembako warga kurang mampu dan pembersihan fasilitas masjid se-Bukittinggi.
                        </p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-amber-400 uppercase">FSI Peduli</span>
                        <span class="text-xs font-semibold text-slate-400">Selengkapnya &rarr;</span>
                    </div>
                </div>
            </article>

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
