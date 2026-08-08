@php
    $pageTitle = 'Open Recruitment FSI - SmAN 1 Bukittinggi';
    $isRecruitmentOpen = \App\Models\Setting::getByKey('recruitment_open', '1') === '1';
    $oprecEyebrow = $isRecruitmentOpen ? 'Pendaftaran Sedang DIBUKA' : 'Pendaftaran Saat Ini DITUTUP';
    $oprecEyebrowIcon = $isRecruitmentOpen ? 'fa-solid fa-circle-check' : 'fa-solid fa-lock';
    $oprecEyebrowClass = $isRecruitmentOpen
        ? 'eyebrow-pill eyebrow-pill-green'
        : 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-red-500/15 border border-red-500/30 text-red-300 text-xs font-bold uppercase tracking-wider';
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased">

    {{-- Wrapper halaman: gradient full-tinggi + host silhouette di tepi bawah (scroll bersama konten) --}}
    <div class="skyline-page flex flex-col">

    {{-- Background skyline siluet — ganti prop `image` ke siluet khusus Open Recruitment saat tersedia --}}
    <x-islamic-skyline-background />

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="relative z-10 flex-1 max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-10 w-full">

        <!-- Header Title Banner -->
        <div class="max-w-3xl mx-auto">
            <x-page-header
                :eyebrow="$oprecEyebrow"
                :eyebrow-icon="$oprecEyebrowIcon"
                :eyebrow-class="$oprecEyebrowClass"
                title="Open Recruitment <span class='text-[var(--gold)]'>FSI SMAN 1 Bukittinggi</span>"
                subtitle="Pendaftaran anggota baru khusus siswa/i Kelas X SMAN 1 Bukittinggi untuk bergabung dalam keluarga besar TSAQIB.">
                @if($isRecruitmentOpen)
                    <x-slot:extra>
                        <a href="#form-pendaftaran" class="cta-primary inline-flex items-center gap-2 text-white font-label font-bold text-xs px-6 py-3 rounded-full">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            Daftar Sekarang
                        </a>
                    </x-slot:extra>
                @endif
            </x-page-header>
        </div>

        <!-- 1. INFORMASI FSI, SYARAT, & TIMELINE -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Informasi FSI</h3>
                <p class="text-xs text-white/60 leading-relaxed">
                    Forum Studi Islam TSAQIB memfasilitasi 13 bidang minat komunitas untuk membentuk karakter siswa Rabbani dan berakhlak mulia.
                </p>
            </div>

            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Persyaratan</h3>
                <ul class="space-y-1.5 text-xs text-white/60">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Siswa/i aktif Kelas X SMAN 1 Bukittinggi.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Berkomitmen mengikuti bimbingan FSI.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#3fd6b0] mt-0.5"></i>
                        <span>Mengisi data pendaftaran asli.</span>
                    </li>
                </ul>
            </div>

            <div class="tsaqib-card p-6">
                <div class="w-10 h-10 icon-chip flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-timeline"></i>
                </div>
                <h3 class="font-bold text-base text-[var(--cream)] mb-2">Timeline</h3>
                <ul class="space-y-1.5 text-xs text-white/60">
                    <li>• Pendaftaran Online Website</li>
                    <li>• Verifikasi Berkas Pengurus</li>
                    <li>• Welcoming & First Gathering</li>
                </ul>
            </div>

        </div>

        <!-- 2. FORM PENDAFTARAN (JIKA DIBUKA) -->
        <div id="form-pendaftaran" class="tsaqib-card p-6 sm:p-8">
            <h2 class="text-lg font-display font-bold text-[var(--cream)] mb-6 pb-4 border-b border-white/10 flex items-center space-x-2">
                <i class="fa-solid fa-file-pen text-[var(--gold)]"></i>
                <span>Formulir Pendaftaran Anggota Kelas X</span>
            </h2>

            @if(!$isRecruitmentOpen)
                <div class="p-6 rounded-xl bg-white/5 border border-white/10 text-center text-white/60 text-xs">
                    Mohon maaf, pendaftaran Open Recruitment FSI TSAQIB saat ini sedang ditutup. Silakan hubungi pengurus FSI untuk informasi lebih lanjut.
                </div>
            @else

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-xs font-medium">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('open.recruitment.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-white/80 uppercase mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}"
                               placeholder="Contoh: Muhammad Abdullah"
                               class="tsaqib-input w-full px-4 py-2.5 text-xs">
                    </div>

                    <div>
                        <label for="nama_panggilan" class="block text-xs font-bold text-white/80 uppercase mb-1.5">
                            Nama Panggilan <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_panggilan" id="nama_panggilan" required value="{{ old('nama_panggilan') }}"
                               placeholder="Contoh: Aam"
                               class="tsaqib-input w-full px-4 py-2.5 text-xs">
                    </div>

                    <div>
                        <label for="kelas" class="block text-xs font-bold text-white/80 uppercase mb-1.5">
                            Kelas (Khusus Kelas X) <span class="text-red-400">*</span>
                        </label>
                        <select name="kelas" id="kelas" required class="tsaqib-input w-full px-4 py-2.5 text-xs">
                            <option value="" disabled selected>-- Pilih Kelas X Anda --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="X.{{ $i }}" {{ old('kelas') == 'X.'.$i ? 'selected' : '' }}>
                                    Kelas X.{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="instagram_username" class="block text-xs font-bold text-white/80 uppercase mb-1.5">
                            Username Instagram <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-white/40 text-xs">@</span>
                            <input type="text" name="instagram_username" id="instagram_username" required value="{{ old('instagram_username') }}"
                                   placeholder="username_kamu"
                                   class="tsaqib-input w-full pl-8 pr-4 py-2.5 text-xs">
                        </div>
                    </div>

                    <div>
                        <label for="alasan_bergabung" class="block text-xs font-bold text-white/80 uppercase mb-1.5">
                            Alasan Bergabung <span class="text-red-400">*</span>
                        </label>
                        <textarea name="alasan_bergabung" id="alasan_bergabung" rows="3" required
                                  placeholder="Tuliskan motivasi Anda bergabung dengan FSI..."
                                  class="tsaqib-input w-full px-4 py-2.5 text-xs">{{ old('alasan_bergabung') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="cta-primary w-full py-3 rounded-xl text-white font-label font-bold text-xs">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pendaftaran
                        </button>
                    </div>

                </form>

            @endif
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    </div>{{-- end .skyline-page --}}

</body>
</html>
