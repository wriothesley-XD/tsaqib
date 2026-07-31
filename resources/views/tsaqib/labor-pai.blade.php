<!-- resources/views/tsaqib/labor-pai.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorium PAI - FSI SMAN 1 Bukittinggi</title>
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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar (6 Items) -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Title Banner -->
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider mb-2">
                Fasilitas & Karakter Rabbani
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                Laboratorium PAI <span class="text-[#01795F]">SMAN 1 Bukittinggi</span>
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-2">
                Pusat riset, praktikum ibadah, dan pembinaan karakter Pendidikan Agama Islam SMAN 1 Bukittinggi.
            </p>
        </div>

        <!-- 1. SEJARAH SINGKAT, VISI, & MISI -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Sejarah Singkat (1 col) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Sejarah Singkat</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Laboratorium PAI SMAN 1 Bukittinggi dikembangkan sebagai wadah praktikum ibadah praktis (tata cara jenazah, khutbah, fiqih shalat), seni Islam, dan integrasi sains-Al-Qur'an sejak berdiri hingga dikelola bersama pengurus FSI TSAQIB.
                </p>
            </div>

            <!-- Visi (1 col) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Visi Utama</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Menjadi pusat praktikum keilmuan Islam dan laboratorium karakter siswa SMAN 1 Bukittinggi yang unggul, beriman, dan berakhlak mulia.
                </p>
            </div>

            <!-- Misi (1 col) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Misi Utama</h3>
                <ul class="space-y-1.5 text-xs text-slate-600">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Memfasilitasi modul praktikum ibadah siswa.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Mengembangkan media syiar & keilmuan Islam.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Membangun ukhuwah & kepemimpinan Rabbani.</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- 2. INFOGRAFIS STRUKTUR ORGANISASI -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Infografis Struktur Organisasi</h2>
                    <p class="text-slate-500 text-xs">Struktur Pembina Guru & Pengurus Siswa Laboratorium PAI & TSAQIB</p>
                </div>
            </div>

            <!-- INFOGRAPHIC TREE NODES -->
            <div class="space-y-6">

                <!-- Node 1: Pembina -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center max-w-sm mx-auto shadow-sm">
                    <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-widest block mb-1">Pengarah Utama</span>
                    <h4 class="font-bold text-sm text-slate-900">Guru Pembina PAI SMAN 1 Bukittinggi</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Pembimbing Kepengurusan & Keilmuan</p>
                </div>

                <!-- Connector Line -->
                <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                <!-- Node 2: Ketua & Wakil -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl mx-auto">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-bold text-[#01795F] uppercase block mb-1">Ketua Umum</span>
                        <h4 class="font-bold text-sm text-slate-900">Ketua FSI TSAQIB</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Koordinator Utama Siswa</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-bold text-[#01795F] uppercase block mb-1">Wakil Ketua</span>
                        <h4 class="font-bold text-sm text-slate-900">Wakil Ketua FSI</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pendamping Operasional</p>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                <!-- Node 3: Sekretaris & Bendahara -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl mx-auto">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-bold text-[#3F704D] uppercase block mb-1">Sekretaris</span>
                        <h4 class="font-bold text-sm text-slate-900">Sekretaris Utama</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Administrasi & Surat Keputusan</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-bold text-[#3F704D] uppercase block mb-1">Bendahara</span>
                        <h4 class="font-bold text-sm text-slate-900">Bendahara Utama</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pengelolaan Keuangan & Kas</p>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="w-0.5 h-6 bg-slate-300 mx-auto"></div>

                <!-- Node 4: Koordinator Divisi (13 Komunitas) -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center max-w-2xl mx-auto">
                    <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-widest block mb-1">Pelaksana Operasional</span>
                    <h4 class="font-bold text-sm text-slate-900">Koordinator Divisi 13 Komunitas</h4>
                    <p class="text-xs text-slate-500 mt-0.5">(Tahfidz, Syiar, Nasyid, Kaligrafi, Panahan, Kemuslimahan, Media, Lughah, Hadrah, Literasi, Baksos, Entrepreneur, Science)</p>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
