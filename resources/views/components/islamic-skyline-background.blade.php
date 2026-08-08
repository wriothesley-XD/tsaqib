@props(['image' => 'assets/background/skyline.png'])

{{--
    Siluet skyline Islamik (efek harrypotter.com). Merender SATU strip
    .skyline-silhouette yang ditaruh di dalam wrapper .skyline-page.
    Siluet muncul SEKALI di tepi bawah halaman & scroll bersama konten
    (absolute, bukan fixed).

    Pemakaian (gambar default: skyline.png):
        <x-islamic-skyline-background />
    Siluet berbeda per halaman:
        <x-islamic-skyline-background image="assets/background/skyline-oprec.png" />

    CSS (.skyline-page & .skyline-silhouette) ada di partials/theme-head.blade.php.
--}}
<div class="skyline-silhouette" aria-hidden="true" style="background-image:url('{{ asset($image) }}');"></div>
