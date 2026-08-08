{{--
    resources/views/layouts/master.blade.php
    =========================================
    Layout shell utama tema gelap TSAQIB. Halaman publik memakainya dengan
    extends layouts.master lalu mengisi section content.

    Menyediakan: head (lewat partials.theme-head), navbar sticky global,
    yield content, footer global, plus slot stack scripts & stack head-scripts.

    PENTING: ini BUKAN layouts/app.blade.php — file itu stub Breeze yang
    dipakai komponen x-app-layout untuk halaman /daftar & dashboard. Jangan ditimpa.

    Navbar = sticky, BUKAN fixed. Sticky sudah memesan tempatnya sendiri di
    flow, jadi konten di bawahnya nggak ketimpa dan nggak butuh padding-top.
--}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    @stack('head-scripts')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    @include('partials.navbar')

    @yield('content')

    @include('partials.site-footer')

    @stack('scripts')
</body>
</html>
