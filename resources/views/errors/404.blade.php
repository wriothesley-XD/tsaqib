{{-- 404 ramah bertema TSAQIB — dipakai otomatis Laravel untuk semua 404,
     termasuk slug berita/dokumentasi yang tidak ditemukan. --}}
@extends('layouts.master')

@php($pageTitle = 'Halaman Tidak Ditemukan - TSAQIB SMAN 1 Bukittinggi')

@section('content')
<main class="flex-1 w-full flex items-center justify-center px-4 py-16">
    <div class="tsaqib-card-flat max-w-md w-full p-10 text-center">
        <i class="fa-solid fa-compass text-4xl text-white/15 block mb-4"></i>
        <p class="font-display font-extrabold text-5xl tracking-tight text-[var(--gold)]">404</p>
        <h1 class="font-display font-bold text-lg text-[var(--cream)] mt-3">Halaman tidak ditemukan</h1>
        <p class="text-white/50 text-xs mt-2 leading-relaxed">
            Konten yang kamu cari mungkin sudah dihapus, belum terbit, atau alamatnya salah.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2.5 mt-7">
            <a href="{{ route('info') }}"
               class="cta-primary inline-flex items-center justify-center gap-1.5 w-full sm:w-auto text-white font-bold text-xs px-5 py-2.5 rounded-full">
                <i class="fa-solid fa-bullhorn text-[10px]"></i> Kembali ke Berita
            </a>
            <a href="{{ route('landing') }}"
               class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto text-[11px] font-semibold text-white/60 hover:text-[var(--cream)] px-5 py-2.5 rounded-full border border-white/10 hover:border-white/25 transition">
                <i class="fa-solid fa-house text-[10px]"></i> Ke Beranda
            </a>
        </div>
    </div>
</main>
@endsection
