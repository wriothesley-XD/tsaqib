{{-- resources/views/tsaqib/labor-pai.blade.php --}}
@php($pageTitle = 'Laboratorium PAI - FSI SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased">

    {{-- Wrapper halaman: gradient full-tinggi + host silhouette di tepi bawah (scroll bersama konten) --}}
    <div class="skyline-page flex flex-col">

    {{-- Background skyline siluet (muncul sekali di tepi bawah halaman) --}}
    <x-islamic-skyline-background />

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="relative z-10 flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Title Banner -->
        <x-page-header
            eyebrow="Fasilitas & Karakter Rabbani"
            eyebrow-icon="fa-solid fa-flask"
            title="Laboratorium PAI <span class='text-[var(--gold)]'>SMAN 1 Bukittinggi</span>"
            subtitle="Pusat riset, praktikum ibadah, dan pembinaan karakter Pendidikan Agama Islam SMAN 1 Bukittinggi." />

        <!-- 1. SEJARAH SINGKAT, VISI, & MISI -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Sejarah Singkat (1 col) -->
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Sejarah Singkat</h3>
                <p class="text-xs text-white/60 leading-relaxed">
                    Laboratorium Pendidikan Agama Islam (PAI) tidak sekadar hadir sebagai ruang fisik untuk kegiatan pembelajaran, tetapi menjadi pusat pembinaan karakter, penguatan akhlak mulia, pengembangan spiritual, serta pembiasaan nilai-nilai keislaman dalam kehidupan sehari-hari peserta didik.
                </p>
            </div>

            <!-- Visi (1 col) -->
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Visi dan Misi</h3>
                <p class="text-xs text-white/60 leading-relaxed font-medium">
                    Menjadi pusat praktikum keilmuan Islam dan laboratorium karakter siswa SMAN 1 Bukittinggi yang unggul, beriman, dan berakhlak mulia.
                </p>
                <ul class="space-y-1.5 text-xs text-white/60 mt-2">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Memfasilitasi modul praktikum ibadah siswa.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Mengembangkan media syiar & keilmuan Islam.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Membangun ukhuwah & kepemimpinan Rabbani.</span>
                    </li>
                </ul>
            </div>
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Legalitas dan Struktur Organisai</h3>
                <p class="text-xs text-white/60 leading-relaxed font-medium">
                    Surat Keputusan (SK) beserta rincian hak dan kewajiban masing-masing personel yang memuat pembagian tugas, tanggung jawab, wewenang, hak, serta kewajiban dalam melaksanakan pekerjaan secara efektif, terukur, dan sesuai ketentuan yang berlaku.
                </p>
            </div>
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Perencaaan dan Regulasi Operasional Labor PAI Digital</h3>
                <p class="text-xs text-white/60 leading-relaxed font-medium">
                    Perencanaan dan regulasi operasional Laboratorium PAI Digital mencakup penyusunan kebijakan, standar operasional prosedur, tata kelola layanan, pemanfaatan teknologi, pembagian tugas, serta mekanisme evaluasi untuk menjamin pelaksanaan kegiatan yang efektif, aman, dan berkelanjutan.                </p>
            </div>
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Pelaksanaan Pemanfaatan Labor PAI Digital</h3>
                <p class="text-xs text-white/60 leading-relaxed font-medium">
                    Pelaksanaan pemanfaatan Laboratorium PAI Digital diarahkan sebagai pusat pembelajaran dan pengembangan kompetensi yang dimanfaatkan oleh siswa, guru PAI, Kelompok Kerja Guru (KKG), serta Musyawarah Guru Mata Pelajaran (MGMP) PAI melalui berbagai layanan, sumber belajar digital, pelatihan, kolaborasi, dan inovasi pembelajaran berbasis teknologi.
                </p>
            </div>
            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Sarana Prasarana / Inventaris Aset</h3>
                <p class="text-xs text-white/60 leading-relaxed font-medium">
                    Menjadi pusat praktikum keilmuan Islam dan laboratorium karakter siswa SMAN 1 Bukittinggi yang unggul, beriman, dan berakhlak mulia.
                </p>
            </div>

        </div>

        <!-- 2. INFOGRAFIS STRUKTUR ORGANISASI -->
        <div class="tsaqib-card p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-[var(--cream)]">Infografis Struktur Organisasi</h2>
                    <p class="text-white/50 text-xs">Struktur Pembina Guru & Pengurus Siswa Laboratorium PAI & TSAQIB</p>
                </div>
            </div>

            <!-- INFOGRAPHIC TREE NODES -->
            <div class="space-y-6">
                <img src="{{ asset('images/struktur.webp') }}" alt="Struktur FSI TSAQIB" class="w-full rounded-xl border border-white/10 bg-white p-2" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('images/kepengurusan.webp') }}" alt="Kepengurusan FSI TSAQIB" class="w-full rounded-xl border border-white/10 bg-white p-2" loading="lazy" onerror="this.remove()">
            </div>
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    </div>{{-- end .skyline-page --}}

</body>
</html>
