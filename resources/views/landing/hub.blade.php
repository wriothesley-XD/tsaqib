<!-- resources/views/landing/hub.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hub Masjid FSI - TSAQIB SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap" rel="stylesheet">
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
<body class="bg-gradient-to-b from-[#DCEBF2] via-slate-50 to-slate-100 text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between p-4 sm:p-8">

    <!-- Top Header Navigation -->
    <header class="max-w-5xl mx-auto w-full flex items-center justify-between py-4">
        <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-centerjustify-center font-bold text-lg shadow-sm group-hover:bg-[#3F704D] transition duration-200 overflow-hidden">
                <img src="{{  asset('images/labor.png') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <span class="font-bold text-base sm:text-lg tracking-tight text-slate-900 block leading-none">
                    LABOR PAI DIGITAL
                </span>
                <span class="text-[10px] text-[#01795F] font-semibold tracking-wider uppercase block mt-0.5">FSI SMAN 1 Bukittinggi</span>
            </div>
        </a>

        <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-700 hover:text-[#01795F] bg-white/80 backdrop-blur border border-slate-200/80 px-4 py-2 rounded-full shadow-sm hover:shadow transition">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali ke Floating Island</span>
        </a>
    </header>

    <!-- Main Hub Content -->
    <main class="max-w-8xl mx-auto w-full my-auto py-8">
        <div class="text-center space-y-3 mb-10">
            <span class="inline-block px-3.5 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-bold uppercase tracking-wider">
                Gerbang Utama Masjid Floating Island
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Pusat Layanan & Aktivitas <span class="text-[#01795F]">LABOR PAI DIGITAL</span>
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm max-w-lg mx-auto leading-relaxed">
                Silakan pilih fasilitas atau jalur pendaftaran yang ingin Anda akses di bawah ini. Akses publik tanpa perlu login.
            </p>
        </div>

        <!-- 2 Big Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
            
            <!-- Card 1: Laboratorium PAI -->
            <a href="{{ route('laboratorium.pai') }}" 
               class="group relative bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-2xl hover:shadow-[#01795F]/15 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-flask"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[#01795F] uppercase tracking-widest mb-1">
                        Laboratorium & Riset PAI
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-[#01795F] transition-colors leading-tight">
                        Laboratorium PAI
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                        Pusat riset, materi pembelajaran Pendidikan Agama Islam, simulasi ibadah, dan ruang diskusi akademik siswa SMAN 1 Bukittinggi.
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#01795F]">
                    <span>Buka Laboratorium PAI</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

            <!-- Card 1: Laboratorium PAI -->
            <a href="{{ route('perpustakaan') }}" 
               class="group relative bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-2xl hover:shadow-[#01795F]/15 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-book"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[#01795F] uppercase tracking-widest mb-1">
                        PUSTAKA PAI DIGITAL
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-[#01795F] transition-colors leading-tight">
                        Pustaka PAI Digital
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                        Akses terhadap buku digital serta penguatan literasi Agama Islam untuk mendukung peningkatan pemahaman, pembelajaran, dan pengamalan nilai-nilai keislaman secara efektif.
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#01795F]">
                    <span>Buka Pusataka Digital</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

            <!-- Card 2: Open Recruitment -->
            <a href="{{ route('komunitas') }}" 
               class="group relative bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-2xl hover:shadow-[#01795F]/15 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[#01795F] uppercase tracking-widest mb-1">
                        Tsaqib FSI
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-[#01795F] transition-colors leading-tight">
                        Tsaqib FSI
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                        Ekstrakurikuler Keagamaan yang menjadi ruang tumbuh dalam menempa diri siswa dibidang ketakwaan, kejujuran, dan akhlakul karimah.
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#01795F]">
                    <span>Akses Tsaqib</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

            <!-- Card 2: Open Recruitment -->
            <a href="{{ route('open.recruitment') }}" 
               class="group relative bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-2xl hover:shadow-[#01795F]/15 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[#01795F] uppercase tracking-widest mb-1">
                        Pendaftaran Anggota Baru
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-[#01795F] transition-colors leading-tight">
                        Registrasi Pengunjung
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                        Pendaftaran terbuka anggota baru khusus siswa/i Kelas X SMAN 1 Bukittinggi. Bergabunglah bersama 13 bidang minat TSAQIB!
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#01795F]">
                    <span>Isi Form Pendaftaran</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-5xl mx-auto w-full text-center text-xs text-slate-500 py-4">
        &copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi
    </footer>

</body>
</html>
