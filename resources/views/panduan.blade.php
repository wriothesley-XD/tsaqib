{{-- resources/views/panduan.blade.php — Halaman "Panduan Penggunaan TSAQIB" --}}
@php($pageTitle = 'Panduan Penggunaan TSAQIB - Portal Digitalisasi PAI & Media Dakwah SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col" style="background-color: var(--green-s0);">

    @include('partials.navbar')

    <main class="flex-1">

        {{-- ===== HERO ===== --}}
        <section class="relative border-b border-white/10 overflow-hidden" style="background-color: var(--green-s1);">
            <div class="section-glow" style="--glow-x: 50%; --glow-y: 40%;"></div>
            <div class="pat-islami opacity-15"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-16 text-center space-y-4">
                <span class="eyebrow-pill eyebrow-pill-gold">
                    <i class="fa-solid fa-circle-question text-[10px]"></i>
                    Panduan
                </span>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[var(--cream)] tracking-tight">
                    Panduan Penggunaan TSAQIB
                </h1>
                <p class="text-white/70 text-sm sm:text-base leading-relaxed max-w-xl mx-auto">
                    Panduan singkat untuk siswa dan guru: menjelajahi fitur utama, bergabung dengan komunitas, hingga cara mendapatkan bantuan.
                </p>
            </div>
        </section>

        {{-- ===== 1. SELAMAT DATANG ===== --}}
        <section class="relative py-16 border-b border-white/10" style="background-color: var(--green-s0);">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-4">
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight">
                    1. Selamat Datang di Tsaqib
                </h2>
                <p class="text-white/70 text-sm sm:text-base leading-relaxed">
                    TSAQIB adalah portal digitalisasi Pendidikan Agama Islam dan media dakwah yang dikelola Forum Studi Islam SMAN 1 Bukittinggi. Melalui tiga pilar utamanya — <strong class="text-white">Belajar</strong>, <strong class="text-white">Berkarya</strong>, dan <strong class="text-white">Berkomunitas</strong> — TSAQIB membantu siswa mempraktikkan ibadah, mengakses materi kurikulum, menerbitkan karya tulis, serta menyalurkan minat dan bakat positif bersama circle komunitas.
                </p>
            </div>
        </section>

        {{-- ===== 2. MENJELAJAHI FITUR UTAMA ===== --}}
        <section class="relative py-16 border-b border-white/10" style="background-color: var(--green-s1);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight text-center">
                    2. Menjelajahi Fitur Utama
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                    <div class="tsaqib-card p-6 sm:p-7 flex flex-col">
                        <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold mb-4">
                            <i class="fa-solid fa-flask"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">Laboratorium PAI</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed flex-1">
                            Buka menu <strong class="text-white">Laboratorium PAI</strong> di navbar. Halaman ikhtisar dan profil guru bisa diakses langsung; <strong class="text-white">Modul Pembelajaran</strong> (kelas X&ndash;XII) dan <strong class="text-white">Tugas Siswa</strong> terbuka setelah masuk sebagai siswa terverifikasi atau guru.
                        </p>
                        <a href="{{ route('laboratorium.pai') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[var(--gold)] hover:text-amber-200 transition-colors cursor-pointer">
                            Buka Laboratorium <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="tsaqib-card p-6 sm:p-7 flex flex-col">
                        <span class="w-12 h-12 rounded-2xl bg-[var(--gold)]/20 border border-[var(--gold)]/35 flex items-center justify-center text-[var(--gold)] text-xl font-bold mb-4">
                            <i class="fa-solid fa-book-open"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">Perpustakaan Digital</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed flex-1">
                            Koleksi buku islami dan buletin dakwah dapat dibaca tanpa perlu masuk akun. Pilih menu <strong class="text-white">Perpustakaan</strong> di navbar, lalu telusuri koleksi berdasarkan kategori yang tersedia.
                        </p>
                        <a href="{{ route('perpustakaan') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[var(--gold)] hover:text-amber-200 transition-colors cursor-pointer">
                            Buka Perpustakaan <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="tsaqib-card p-6 sm:p-7 flex flex-col">
                        <span class="w-12 h-12 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/35 flex items-center justify-center text-[#3fd6b0] text-xl font-bold mb-4">
                            <i class="fa-solid fa-users"></i>
                        </span>
                        <h3 class="font-display font-extrabold text-xl text-[var(--cream)] tracking-tight">Komunitas</h3>
                        <p class="text-xs sm:text-sm text-white/70 mt-2 leading-relaxed flex-1">
                            Menu <strong class="text-white">Komunitas</strong> memuat postingan dari 7 circle (Tahfidz, The Young Stars, Grow Up, Blitzsport, Gofam, Leora, MuShou). Telusuri tanpa akun; untuk berinteraksi dan mengunggah karya, masuk terlebih dahulu.
                        </p>
                        <a href="{{ route('komunitas', 'semua') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[var(--gold)] hover:text-amber-200 transition-colors cursor-pointer">
                            Jelajahi Komunitas <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== 3. CARA BERGABUNG DENGAN KOMUNITAS ===== --}}
        <section class="relative py-16 border-b border-white/10 overflow-hidden" style="background-color: var(--green-s0);">
            <div class="section-glow" style="--glow-x: 15%; --glow-y: 50%;"></div>
            <div class="pat-islami opacity-15"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight">
                    3. Cara Bergabung dengan Komunitas
                </h2>

                <ol class="space-y-4 text-sm sm:text-base text-white/70 leading-relaxed">
                    <li class="flex gap-4">
                        <span class="shrink-0 w-8 h-8 rounded-full bg-[var(--gold)]/15 border border-[var(--gold)]/35 text-[var(--gold)] font-display font-bold text-sm flex items-center justify-center">1</span>
                        <span><strong class="text-white">Daftar akun.</strong> Klik tombol <em>Masuk</em> di kanan atas navbar, lalu pilih opsi pendaftaran dan lengkapi data sesuai identitas siswa SMAN 1 Bukittinggi.</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-8 h-8 rounded-full bg-[var(--gold)]/15 border border-[var(--gold)]/35 text-[var(--gold)] font-display font-bold text-sm flex items-center justify-center">2</span>
                        <span><strong class="text-white">Pilih karakter komunitas.</strong> Setelah mendaftar, kamu akan diarahkan ke halaman pemilihan komunitas berbentuk carousel. Pilih satu circle yang paling sesuai minatmu &mdash; langkah ini wajib sebelum masuk ke fitur komunitas.</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-8 h-8 rounded-full bg-[var(--gold)]/15 border border-[var(--gold)]/35 text-[var(--gold)] font-display font-bold text-sm flex items-center justify-center">3</span>
                        <span><strong class="text-white">Berinteraksi.</strong> Unggah karya, beri voting pada postingan, dan ikuti aktivitas circle. Komunitasmu bisa diganti kapan saja lewat <em>Edit Profil</em>.</span>
                    </li>
                </ol>
            </div>
        </section>

        {{-- ===== 4. FAQ ===== --}}
        <section class="relative py-16 border-b border-white/10" style="background-color: var(--green-s1);">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight">
                    4. Pertanyaan yang Sering Diajukan (FAQ)
                </h2>

                <div class="space-y-3">
                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Apakah saya harus punya akun untuk menikmati TSAQIB?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Tidak untuk sebagian besar fitur. Ikhtisar Laboratorium, Perpustakaan Digital, berita di halaman Info, dan melihat postingan Komunitas bisa diakses tanpa akun. Akun diperlukan untuk mengunggah karya, memberi voting, mengakses Modul &amp; Tugas, serta mengikuti komunitas.
                        </p>
                    </details>

                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Bagaimana cara mengunggah karya di Komunitas?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Masuk ke akunmu, buka menu <strong class="text-white">Komunitas</strong>, lalu gunakan tombol buat postingan. Isi judul dan deskripsi, lampirkan media pendukung, kemudian kirim. Postingan akan tampil di circle komunitasmu dan bisa divoting pengguna lain.
                        </p>
                    </details>

                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Bagaimana cara berganti komunitas (role karakter)?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Buka halaman <strong class="text-white">Profil</strong> lewat avatar di navbar, pilih <em>Edit Profil</em>, lalu pilih komunitas baru pada bagian pemilihan komunitas dan simpan perubahan. Tidak perlu mendaftar ulang.
                        </p>
                    </details>

                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Kenapa saya diminta memilih komunitas setelah mendaftar?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Pemilihan komunitas menentukan karakter dan feed aktivitasmu di TSAQIB. Sistem mengarahkan setiap akun baru ke halaman pemilihan ini sekali di awal; setelahnya komunitas tetap bisa diganti lewat Edit Profil.
                        </p>
                    </details>

                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Mengapa Modul &amp; Tugas Laboratorium belum bisa dibuka?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Modul Pembelajaran dan Tugas Siswa hanya terbuka bagi siswa terverifikasi dan guru. Jika akunmu belum terverifikasi, hubungi guru PAI atau pengurus Forum Studi Islam untuk proses verifikasi.
                        </p>
                    </details>

                    <details class="tsaqib-card group px-5 py-4 cursor-pointer">
                        <summary class="flex items-center justify-between gap-4 text-sm sm:text-base font-semibold text-[var(--cream)] [&::-webkit-details-marker]:hidden">
                            Saya menemukan kesalahan pada konten, ke siapa melapor?
                            <i class="fa-solid fa-chevron-down text-[10px] text-[var(--gold)] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="mt-3 text-xs sm:text-sm text-white/70 leading-relaxed">
                            Sampaikan melalui formulir <a href="https://docs.google.com/forms/d/e/1FAIpQLScLDeCvGI17R7Z-NkckFV-N9Sm1Jfl8-eOEl20ZFVfFDeebgQ/viewform" target="_blank" rel="noopener noreferrer" class="text-[var(--gold)] font-semibold hover:text-amber-200 transition-colors cursor-pointer">Saran &amp; Masukan</a> &mdash; tautan yang sama juga tersedia di menu <em>Lainnya</em> pada navbar.
                        </p>
                    </details>
                </div>
            </div>
        </section>

        {{-- ===== 5. BUTUH BANTUAN? ===== --}}
        <section class="relative py-16 overflow-hidden" style="background-color: var(--green-s0);">
            <div class="section-glow" style="--glow-x: 85%; --glow-y: 50%;"></div>
            <div class="pat-islami opacity-15"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight">
                    5. Butuh Bantuan?
                </h2>
                <p class="text-white/70 text-sm sm:text-base leading-relaxed">
                    Panduan ini belum menjawab pertanyaanmu? Sampaikan pertanyaan, saran, atau laporan melalui formulir resmi berikut. Tim pengurus akan menindaklanjuti secepatnya.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-1">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLScLDeCvGI17R7Z-NkckFV-N9Sm1Jfl8-eOEl20ZFVfFDeebgQ/viewform"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-bold text-[#0D2818] bg-[var(--gold)] hover:bg-amber-300 transition-colors cursor-pointer">
                        <i class="fa-solid fa-comment-dots"></i> Saran &amp; Masukan
                    </a>
                    <a href="{{ route('komunitas', 'semua') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-bold text-[var(--cream)] border border-white/20 hover:border-[var(--gold)]/60 hover:bg-white/5 transition-colors cursor-pointer">
                        <i class="fa-solid fa-users"></i> Tanya di Komunitas
                    </a>
                </div>
            </div>
        </section>

    </main>

    @include('partials.site-footer')

</body>
</html>
