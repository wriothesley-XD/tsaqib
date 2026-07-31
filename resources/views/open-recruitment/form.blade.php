<!-- resources/views/open-recruitment/form.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Recruitment FSI - SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $isRecruitmentOpen = \App\Models\Setting::getByKey('recruitment_open', '1') === '1';
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

    <!-- Unified TSAQIB Navbar (6 Items) -->
    @include('partials.navbar')

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-10 w-full">

        <!-- Header Title Banner -->
        <div class="text-center max-w-3xl mx-auto">
            @if($isRecruitmentOpen)
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-[#01795F] text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-circle-check mr-1.5"></i>Pendaftaran Sedang DIBUKA
                </span>
            @else
                <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-lock mr-1.5"></i>Pendaftaran Saat Ini DITUTUP
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                Open Recruitment <span class="text-[#01795F]">FSI SMAN 1 Bukittinggi</span>
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-2 leading-relaxed">
                Pendaftaran anggota baru khusus siswa/i Kelas X SMAN 1 Bukittinggi untuk bergabung dalam keluarga besar TSAQIB.
            </p>

            @if($isRecruitmentOpen)
                <div class="mt-6">
                    <a href="#form-pendaftaran" class="px-6 py-3 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                        Daftar Sekarang &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- 1. INFORMASI FSI, SYARAT, & TIMELINE -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Informasi FSI</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Forum Studi Islam TSAQIB memfasilitasi 13 bidang minat komunitas untuk membentuk karakter siswa Rabbani dan berakhlak mulia.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Persyaratan</h3>
                <ul class="space-y-1.5 text-xs text-slate-600">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Siswa/i aktif Kelas X SMAN 1 Bukittinggi.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Berkomitmen mengikuti bimbingan FSI.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-[#01795F] mt-0.5"></i>
                        <span>Mengisi data pendaftaran asli.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-timeline"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">Timeline</h3>
                <ul class="space-y-1.5 text-xs text-slate-600">
                    <li>• Pendaftaran Online Website</li>
                    <li>• Verifikasi Berkas Pengurus</li>
                    <li>• Welcoming & First Gathering</li>
                </ul>
            </div>

        </div>

        <!-- 2. FORM PENDAFTARAN (JIKA DIBUKA) -->
        <div id="form-pendaftaran" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-file-pen text-[#01795F]"></i>
                <span>Formulir Pendaftaran Anggota Kelas X</span>
            </h2>

            @if(!$isRecruitmentOpen)
                <div class="p-6 rounded-xl bg-slate-100 border border-slate-200 text-center text-slate-600 text-xs">
                    Mohon maaf, pendaftaran Open Recruitment FSI TSAQIB saat ini sedang ditutup. Silakan hubungi pengurus FSI untuk informasi lebih lanjut.
                </div>
            @else

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
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
                        <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}"
                               placeholder="Contoh: Muhammad Abdullah"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                    </div>

                    <div>
                        <label for="nama_panggilan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nama Panggilan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_panggilan" id="nama_panggilan" required value="{{ old('nama_panggilan') }}"
                               placeholder="Contoh: Aam"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                    </div>

                    <div>
                        <label for="kelas" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kelas (Khusus Kelas X) <span class="text-red-500">*</span>
                        </label>
                        <select name="kelas" id="kelas" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                            <option value="" disabled selected>-- Pilih Kelas X Anda --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="X.{{ $i }}" {{ old('kelas') == 'X.'.$i ? 'selected' : '' }}>
                                    Kelas X.{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="instagram_username" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Username Instagram <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400 text-xs">@</span>
                            <input type="text" name="instagram_username" id="instagram_username" required value="{{ old('instagram_username') }}"
                                   placeholder="username_kamu"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                        </div>
                    </div>

                    <div>
                        <label for="alasan_bergabung" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Alasan Bergabung <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_bergabung" id="alasan_bergabung" rows="3" required
                                  placeholder="Tuliskan motivasi Anda bergabung dengan FSI..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">{{ old('alasan_bergabung') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pendaftaran
                        </button>
                    </div>

                </form>

            @endif
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
