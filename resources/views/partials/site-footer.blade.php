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
            <img src="{{ asset('assets/logo-instansi/fsi.webp') }}" alt="Forum Studi Islam" title="Forum Studi Islam" class="h-8 w-8 sm:h-10 sm:w-10 object-contain opacity-90 hover:opacity-100 transition" onerror="this.remove()">
        </div>

    </div>
</footer>
