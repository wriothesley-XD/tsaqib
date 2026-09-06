{{-- Form verifikasi siswa 2 pintu (blueprint RBAC poin 3).
     Pintu A: NISN di whitelist → langsung terverifikasi.
     Pintu B: NISN luar whitelist + upload foto KTS → approval admin. --}}
@extends('layouts.master')

@php($pageTitle = 'Verifikasi Siswa - TSAQIB SMAN 1 Bukittinggi')

@section('content')
<main class="flex-1 w-full">
    <div class="max-w-lg mx-auto px-4 sm:px-6 py-10 sm:py-14">

        <div class="text-center mb-8">
            <span class="eyebrow-pill eyebrow-pill-green"><i class="fa-solid fa-id-card text-[10px]"></i> Verifikasi Siswa</span>
            <h1 class="font-display font-extrabold text-2xl text-[var(--cream)] tracking-tight mt-3">
                Verifikasi <span class="text-[var(--gold)]">Akun Siswa</span>
            </h1>
            <p class="text-white/50 text-xs mt-2 leading-relaxed">
                Masukkan NISN kamu. Kalau terdaftar di sekolah, akun langsung terverifikasi.
                Kalau tidak, upload foto KTS untuk diverifikasi manual oleh admin.
            </p>
        </div>

        <div class="tsaqib-card p-6 sm:p-8">
            @if(auth()->user()->is_verified_student)
                <div class="text-center py-4">
                    <i class="fa-solid fa-circle-check text-3xl text-[#3fd6b0] block mb-3"></i>
                    <p class="text-sm font-bold text-[var(--cream)]">Akun kamu sudah terverifikasi sebagai siswa.</p>
                </div>
            @else
                <form action="{{ route('verifikasi.siswa') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">NISN atau Nomor Induk Siswa (NIS) *</label>
                        <input type="text" name="nisn" required inputmode="numeric" pattern="[0-9]{4,20}"
                               class="tsaqib-input w-full px-3 py-2 text-xs" placeholder="Contoh: 0118703733 atau 22455">
                        <p class="text-[10px] text-white/40 mt-1">Cukup masukkan salah satu (NISN atau NIS buku induk sekolah).</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kelas</label>
                        <select name="kelas" class="tsaqib-input w-full px-3 py-2 text-xs">
                            <option value="">— pilih —</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Foto KTS (jika NISN tidak ditemukan)</label>
                        <input type="file" name="kts_photo" accept=".jpg,.jpeg,.png,.webp"
                               class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                        <p class="text-[10px] text-white/35 mt-1">jpg/png/webp, max 5MB. Data hanya dipakai untuk verifikasi.</p>
                    </div>
                    <button type="submit" class="cta-primary w-full inline-flex items-center justify-center gap-1.5 text-white font-bold text-xs px-5 py-2.5 rounded-full">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i> Kirim Verifikasi
                    </button>
                </form>
            @endif
        </div>

    </div>
</main>
@endsection
