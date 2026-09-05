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

    {{-- Flash global (sukses/error) — dipakai redirect RBAC, simpan berita, dll. --}}
    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
            @if(session('success'))
                <div class="rounded-xl border border-[#01795F]/40 bg-[#01795F]/15 px-4 py-2.5 text-xs font-semibold text-[#3fd6b0]">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mt-2 rounded-xl border border-red-500/40 bg-red-500/15 px-4 py-2.5 text-xs font-semibold text-red-300 {{ session('success') ? '' : 'first:mt-0' }}">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    @yield('content')

    @include('partials.site-footer')

    @stack('scripts')
</body>
</html>
