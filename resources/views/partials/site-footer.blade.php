{{--
    resources/views/partials/site-footer.blade.php
    ==================================================
    Footer bersama untuk semua halaman publik TSAQIB.
    Kiri/atas: teks hak cipta. Kanan/bawah: barisan logo instansi pendukung.
    Semua logo ada di public/assets/logo-instansi/ — kalau file belum ada,
    <img>-nya otomatis hilang (onerror) tanpa merusak layout.
--}}
<footer class="relative z-10 border-t border-white/10 mt-auto">
    <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 py-6 flex flex-col lg:flex-row items-center justify-between gap-5">

        <p class="text-white/70 text-[11px] font-label text-center lg:text-left order-2 lg:order-1">
            &copy; {{ date('Y') }} TSAQIB &middot; Forum Studi Islam SMAN 1 Bukittinggi
        </p>

        <div class="flex items-center gap-4 sm:gap-6 order-1 lg:order-2 bg-white/[.05] border border-white/10 rounded-2xl px-5 py-3 sm:px-6 sm:py-3.5">
            <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Kementerian Agama" title="Kementerian Agama" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Tut Wuri Handayani" title="Tut Wuri Handayani" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Pemerintah Provinsi Sumatera Barat" title="Pemerintah Provinsi Sumatera Barat" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="SMAN 1 Bukittinggi" title="SMAN 1 Bukittinggi" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            {{-- Logo FSI = gambar statik (bukan link). --}}
            <img src="{{ asset('assets/logo-instansi/fsi.webp') }}" alt="Forum Studi Islam" title="Forum Studi Islam" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
            {{-- Logo Liivo = pintu tersembunyi ke halaman Credits (unlisted, tak ada di nav).
                 Lingkaran gelap + cincin emas setengah-transparan; satu-satunya logo yang
                 bisa diklik di barisan ini. Diletakkan terakhir untuk menutup barisan. --}}
            <a href="{{ route('credits') }}" title="Credits — Tim Pembuat"
               class="group flex items-center justify-center h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-black/90 border border-[rgba(201,166,107,0.45)] hover:border-[rgba(201,166,107,0.9)] hover:-translate-y-0.5 transition-all duration-200"
               aria-label="Lihat halaman Credits">
                <img src="{{ asset('assets/logo-instansi/Liivo.png') }}" alt="Liivo" title="Credits — Tim Pembuat" class="h-full w-full object-contain p-1 opacity-95 group-hover:opacity-100 transition" onerror="this.remove()">
            </a>
        </div>

    </div>
</footer>
