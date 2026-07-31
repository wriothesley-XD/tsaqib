{{-- resources/views/partials/navbar.blade.php --}}
@php
    $currentRoute = Route::currentRouteName();
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 text-slate-900 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo & Title -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group" title="Kembali ke Floating Island Home">
                <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm group-hover:bg-[#3F704D] transition duration-200">
                    TS
                </div>
                <div>
                    <span class="font-bold text-base sm:text-lg tracking-tight text-slate-900 block leading-none">
                        TSAQIB
                    </span>
                    <span class="text-[10px] text-[#01795F] font-semibold tracking-wider uppercase block mt-0.5">FSI SMAN 1 Bukittinggi</span>
                </div>
            </a>

            <!-- Desktop 6 Navigation Items -->
            <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">

                <!-- 1. Beranda (Kembali ke Home Floating Island /) -->
                <a href="{{ route('landing') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'landing' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-house text-[11px]"></i>
                    <span>Beranda</span>
                </a>

                <!-- 2. Komunitas (Feed Utama TSAQIB) -->
                <a href="{{ route('komunitas') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ str_contains($currentRoute, 'komunitas') ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-users text-[11px]"></i>
                    <span>Komunitas</span>
                </a>

                <!-- 3. Laboratorium PAI -->
                <a href="{{ route('laboratorium.pai') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'laboratorium.pai' || $currentRoute == 'labor' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-flask text-[11px]"></i>
                    <span>Laboratorium PAI</span>
                </a>

                <!-- 4. Open Recruitment -->
                <a href="{{ route('open.recruitment') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ str_contains($currentRoute, 'open.recruitment') ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                    <span>Open Recruitment</span>
                </a>

                <!-- 5. Perpustakaan -->
                <a href="{{ route('perpustakaan') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'perpustakaan' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-book text-[11px]"></i>
                    <span>Perpustakaan</span>
                </a>

                <!-- 6. Profil User / Auth Link -->
                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="ml-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 hover:bg-[#3F704D] hover:text-white transition duration-150 flex items-center space-x-1.5 border border-slate-200">
                        <i class="fa-solid fa-circle-user text-sm"></i>
                        <span>Profil</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="ml-2 px-4 py-2 rounded-lg text-xs font-semibold bg-[#01795F] text-white hover:bg-[#3F704D] transition duration-150 shadow-sm flex items-center space-x-1.5">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Masuk</span>
                    </a>
                @endauth

            </nav>

            <!-- Mobile Menu Toggle Button -->
            <div class="flex items-center md:hidden">
                <button id="mobile-menu-btn" type="button" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg" id="menu-icon"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-1.5 shadow-lg">
        <a href="{{ route('landing') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-house text-[#01795F] mr-2"></i>Beranda (Home)
        </a>
        <a href="{{ route('komunitas') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-users text-[#01795F] mr-2"></i>Komunitas
        </a>
        <a href="{{ route('laboratorium.pai') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-flask text-[#01795F] mr-2"></i>Laboratorium PAI
        </a>
        <a href="{{ route('open.recruitment') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-user-plus text-[#01795F] mr-2"></i>Open Recruitment
        </a>
        <a href="{{ route('perpustakaan') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-book text-[#01795F] mr-2"></i>Perpustakaan
        </a>
        @auth
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
                <i class="fa-solid fa-circle-user text-[#01795F] mr-2"></i>Profil Saya
            </a>
        @else
            <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg text-center text-xs font-semibold bg-[#01795F] text-white">
                <i class="fa-solid fa-right-to-bracket mr-1.5"></i>Masuk Akun
            </a>
        @endauth
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>