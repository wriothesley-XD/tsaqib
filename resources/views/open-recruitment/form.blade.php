@php
    $pageTitle = 'Open Recruitment FSI - SMAN 1 Bukittinggi';
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
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Header Title Banner -->
        <div class="max-w-3xl mx-auto text-center">
            <x-page-header
                :eyebrow="$oprecEyebrow"
                :eyebrow-icon="$oprecEyebrowIcon"
                :eyebrow-class="$oprecEyebrowClass"
                title="Open Recruitment <span class='text-[var(--gold)]'>FSI SMAN 1 Bukittinggi</span>"
                subtitle="Pendaftaran anggota baru khusus siswa/i Kelas X SMAN 1 Bukittinggi untuk bergabung dalam keluarga besar TSAQIB.">
                @if($isRecruitmentOpen)
                    <x-slot:extra>
                        <a href="#form-pendaftaran" class="btn-gold text-xs px-6 py-3 shadow-lg">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>Isi Formulir Pendaftaran</span>
                        </a>
                    </x-slot:extra>
                @endif
            </x-page-header>
        </div>

        <!-- 1. INFORMASI FSI, SYARAT, & TIMELINE -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="tsaqib-card p-6 flex flex-col justify-between hover:border-[var(--gold)]/40 transition-all duration-300">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#01795F]/15 border border-[#01795F]/30 text-[#3fd6b0] flex items-center justify-center text-lg mb-4 shadow-sm">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-[var(--cream)] mb-2">Tentang FSI TSAQIB</h3>
                    <p class="text-xs text-white/60 leading-relaxed">
                        Forum Studi Islam memfasilitasi 7 bidang minat komunitas untuk membentuk karakter siswa Rabbani yang cerdas, unggul, dan berakhlak mulia di SMAN 1 Bukittinggi.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/5 flex items-center gap-2 text-[11px] text-[var(--gold)]">
                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                    <span>Wadah Pembinaan &amp; Prestasi</span>
                </div>
            </div>

            <div class="tsaqib-card p-6 flex flex-col justify-between hover:border-[var(--gold)]/40 transition-all duration-300">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[var(--gold)]/15 border border-[var(--gold)]/30 text-[var(--gold)] flex items-center justify-center text-lg mb-4 shadow-sm">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-[var(--cream)] mb-2">Persyaratan Peserta</h3>
                    <ul class="space-y-2 text-xs text-white/65">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-[#3fd6b0] text-[11px] mt-0.5 shrink-0"></i>
                            <span>Siswa/i aktif Kelas X SMAN 1 Bukittinggi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-[#3fd6b0] text-[11px] mt-0.5 shrink-0"></i>
                            <span>Memiliki komitmen belajar &amp; berorganisasi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-[#3fd6b0] text-[11px] mt-0.5 shrink-0"></i>
                            <span>Mengisi formulir dengan data asli.</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-white/5 flex items-center gap-2 text-[11px] text-[#3fd6b0]">
                    <i class="fa-solid fa-id-card text-[10px]"></i>
                    <span>Khusus Angkatan Baru (Fase E)</span>
                </div>
            </div>

            <div class="tsaqib-card p-6 flex flex-col justify-between hover:border-[var(--gold)]/40 transition-all duration-300">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/15 text-[var(--cream)] flex items-center justify-center text-lg mb-4 shadow-sm">
                        <i class="fa-solid fa-timeline"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-[var(--cream)] mb-2">Alur &amp; Jadwal</h3>
                    <ul class="space-y-2 text-xs text-white/65">
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-[var(--gold)]/20 text-[var(--gold)] text-[9px] font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                            <span>Pendaftaran online via website resmi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-[var(--gold)]/20 text-[var(--gold)] text-[9px] font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                            <span>Verifikasi data dan kontak oleh pengurus.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-[var(--gold)]/20 text-[var(--gold)] text-[9px] font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                            <span>Welcoming party &amp; first gathering.</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-white/5 flex items-center gap-2 text-[11px] text-white/50">
                    <i class="fa-regular fa-clock text-[10px]"></i>
                    <span>Periode Semester Ganjil</span>
                </div>
            </div>

        </div>

        <!-- 2. FORM PENDAFTARAN -->
        <div id="form-pendaftaran" class="tsaqib-card p-6 sm:p-10 max-w-3xl mx-auto rounded-3xl relative overflow-hidden">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-10 h-10 rounded-xl bg-[var(--gold)]/15 text-[var(--gold)] flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-display font-bold text-[var(--cream)]">
                        Formulir Pendaftaran Anggota Baru
                    </h2>
                    <p class="text-white/50 text-xs mt-0.5">Lengkapi data diri kamu secara lengkap dan akurat di bawah ini.</p>
                </div>
            </div>

            @if(!$isRecruitmentOpen)
                <div class="p-8 rounded-2xl bg-white/5 border border-white/10 text-center text-white/60 text-xs">
                    <i class="fa-solid fa-lock text-3xl text-white/20 block mb-3"></i>
                    <p class="font-bold text-white/80 text-sm">Pendaftaran Sedang Ditutup</p>
                    <p class="mt-1">Mohon maaf, pendaftaran Open Recruitment FSI TSAQIB saat ini sedang ditutup. Pantau pengumuman lebih lanjut di laman warta atau media sosial FSI.</p>
                </div>
            @else

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-300 text-xs font-medium">
                        <div class="flex items-center gap-2 mb-2 font-bold text-red-200">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Mohon periksa kembali isian berikut:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('open.recruitment.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}"
                               placeholder="Contoh: Muhammad Abdullah"
                               class="tsaqib-input w-full px-4 py-3 text-xs sm:text-sm rounded-xl">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_panggilan" class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                                Nama Panggilan <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="nama_panggilan" id="nama_panggilan" required value="{{ old('nama_panggilan') }}"
                                   placeholder="Contoh: Aam"
                                   class="tsaqib-input w-full px-4 py-3 text-xs sm:text-sm rounded-xl">
                        </div>

                        <div>
                            <label for="kelas" class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                                Kelas (Khusus Kelas X) <span class="text-red-400">*</span>
                            </label>
                            <select name="kelas" id="kelas" required class="tsaqib-input w-full px-4 py-3 text-xs sm:text-sm rounded-xl">
                                <option value="" disabled selected>-- Pilih Rombel Kelas X --</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="X.{{ $i }}" {{ old('kelas') == 'X.'.$i ? 'selected' : '' }}>
                                        Kelas X.{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="instagram_username" class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                            Username Instagram <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40 text-xs">@</span>
                            <input type="text" name="instagram_username" id="instagram_username" required value="{{ old('instagram_username') }}"
                                   placeholder="username_kamu"
                                   class="tsaqib-input w-full pl-9 pr-4 py-3 text-xs sm:text-sm rounded-xl">
                        </div>
                    </div>

                    <div>
                        <label for="alasan_bergabung" class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                            Alasan Bergabung &amp; Motivasi <span class="text-red-400">*</span>
                        </label>
                        <textarea name="alasan_bergabung" id="alasan_bergabung" rows="4" required
                                  placeholder="Tuliskan motivasi kamu bergabung dengan keluarga besar FSI TSAQIB..."
                                  class="tsaqib-input w-full px-4 py-3 text-xs sm:text-sm rounded-xl leading-relaxed">{{ old('alasan_bergabung') }}</textarea>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn-gold w-full py-3.5 text-xs sm:text-sm font-bold shadow-xl">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            <span>Kirim Formulir Pendaftaran</span>
                        </button>
                        <p class="text-center text-[11px] text-white/40 mt-3">
                            <i class="fa-solid fa-shield-halved text-[#3fd6b0] mr-1"></i>
                            Data pendaftaran dijaga kerahasiaannya oleh Pengurus FSI SMAN 1 Bukittinggi.
                        </p>
                    </div>

                </form>

            @endif
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
