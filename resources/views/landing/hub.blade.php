{{-- resources/views/landing/hub.blade.php --}}
@php($pageTitle = 'Hub Masjid FSI - TSAQIB SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <!-- Main Hub Content -->
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-12 sm:py-16">
        <div class="mb-10">
            <x-page-header
                eyebrow="Gerbang Utama Masjid TSAQIB"
                eyebrow-icon="fa-solid fa-mosque"
                title="Pusat Layanan & Aktivitas <span class='text-[var(--gold)]'>FSI TSAQIB</span>"
                subtitle="Silakan pilih fasilitas atau jalur pendaftaran yang ingin Anda akses di bawah ini. Akses publik tanpa perlu login." />
        </div>

        <!-- 2 Big Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">

            <!-- Card 1: Laboratorium PAI -->
            <a href="{{ route('laboratorium.pai') }}"
               class="group relative tsaqib-card rounded-3xl p-6 sm:p-8 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-flask"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[var(--gold)] uppercase tracking-widest mb-1">
                        Laboratorium & Riset PAI
                    </span>
                    <h2 class="text-xl sm:text-2xl font-display font-bold text-[var(--cream)] group-hover:text-[var(--gold)] transition-colors leading-tight">
                        Laboratorium PAI
                    </h2>
                    <p class="text-xs sm:text-sm text-white/60 mt-2.5 leading-relaxed">
                        Pusat riset, materi pembelajaran Pendidikan Agama Islam, simulasi ibadah, dan ruang diskusi akademik siswa SMAN 1 Bukittinggi.
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-bold text-[var(--gold)]">
                    <span>Buka Laboratorium PAI</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

            <!-- Card 2: Open Recruitment -->
            <a href="{{ route('open.recruitment') }}"
               class="group relative tsaqib-card rounded-3xl p-6 sm:p-8 hover:border-[#01795F] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-2xl font-bold mb-5 group-hover:bg-[#01795F] group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>

                    <span class="inline-block text-[10px] font-extrabold text-[var(--gold)] uppercase tracking-widest mb-1">
                        Pendaftaran Anggota Baru
                    </span>
                    <h2 class="text-xl sm:text-2xl font-display font-bold text-[var(--cream)] group-hover:text-[var(--gold)] transition-colors leading-tight">
                        Open Recruitment
                    </h2>
                    <p class="text-xs sm:text-sm text-white/60 mt-2.5 leading-relaxed">
                        Pendaftaran terbuka anggota baru khusus siswa/i Kelas X SMAN 1 Bukittinggi. Bergabunglah bersama 13 bidang minat TSAQIB!
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-bold text-[var(--gold)]">
                    <span>Isi Form Pendaftaran</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

        </div>
    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
