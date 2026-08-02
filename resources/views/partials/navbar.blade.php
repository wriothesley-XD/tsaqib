@php
    $currentRoute = Route::currentRouteName() ?? '';
    $daftarKomunitasNav = \Illuminate\Support\Facades\Config::get('komunitas.daftar', []);

    $isKomunitasZone = str_contains($currentRoute, 'komunitas');
    $isLaborOprecZone = in_array($currentRoute, ['laboratorium.pai', 'labor', 'open.recruitment', 'open.recruitment.thank-you', 'open.recruitment.submit']);
    $isPerpustakaanZone = ($currentRoute === 'perpustakaan');
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 text-slate-900 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo & Title -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group" title="Kembali ke Home Floating Island">
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

            <!-- Desktop Navigation Items -->
            <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">

                <!-- 1. Beranda (Home Floating Island /) -->
                <a href="{{ route('landing') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'landing' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-house text-[11px]"></i>
                    <span>Beranda</span>
                </a>

                <!-- 2. Komunitas (sembunyikan di Labor/Oprec & Perpustakaan) -->
                @unless($isLaborOprecZone || $isPerpustakaanZone)
                <div class="relative" id="komunitasDropdownWrapper">
                    <button type="button" id="komunitasDropdownBtn"
                            class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $isKomunitasZone ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-users text-[11px]"></i>
                        <span>Komunitas</span>
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" id="komunitasChevron"></i>
                    </button>

                    <!-- Dropdown Panel Komunitas -->
                    <div id="komunitasMegaMenu"
                         class="hidden absolute left-0 top-full mt-2 w-[600px] bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-6 overflow-hidden origin-top-left transform transition-all">
                        
                        <!-- Header Dropdown: "Lihat Semua ->" -->
                        <div class="flex justify-end mb-5">
                            <a href="{{ route('komunitas', 'semua') }}" class="text-[#01795F] hover:text-[#015e4a] text-xs font-bold flex items-center transition-colors">
                                Lihat Semua &rarr;
                            </a>
                        </div>

                        <!-- Grid Item Komunitas (2 Kolom) -->
                        <div class="grid grid-cols-2 gap-x-6 gap-y-5">
                            @foreach($daftarKomunitasNav as $navK)
                                <a href="{{ route('komunitas', $navK['slug']) }}" class="flex items-start space-x-3 group p-2 -m-2 rounded-xl hover:bg-slate-50 transition-colors">
                                    <!-- Icon Box -->
                                    <div class="shrink-0 w-10 h-10 rounded-lg bg-[#01795F]/10 flex items-center justify-center text-[#01795F] group-hover:bg-[#01795F] group-hover:text-white transition-colors">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <!-- Deskripsi -->
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-0.5 group-hover:text-[#01795F] transition-colors">
                                            {{ $navK['nama'] }}
                                        </h4>
                                        <p class="text-[10px] text-slate-500 leading-snug">
                                            {{ $navK['deskripsi_singkat'] ?? '' }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endunless

                <!-- 3. Laboratorium PAI (sembunyikan di Komunitas & Perpustakaan) -->
                @unless($isKomunitasZone || $isPerpustakaanZone)
                <a href="{{ route('laboratorium.pai') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'laboratorium.pai' || $currentRoute == 'labor' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-flask text-[11px]"></i>
                    <span>Laboratorium PAI</span>
                </a>
                @endunless

                <!-- 4. Perpustakaan (sembunyikan di Labor/Oprec & Komunitas) -->
                @unless($isLaborOprecZone || $isKomunitasZone)
                <a href="{{ route('perpustakaan') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $isPerpustakaanZone ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-book text-[11px]"></i>
                    <span>Perpustakaan</span>
                </a>
                @endunless

                <!-- 5. Open Recruitment (sembunyikan di Komunitas & Perpustakaan) -->
                @unless($isKomunitasZone || $isPerpustakaanZone)
                <a href="{{ route('open.recruitment') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ str_contains($currentRoute, 'open.recruitment') ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                    <span>Open Recruitment</span>
                </a>
                @endunless

                <!-- 6. ADMIN PANEL LINK (KHUSUS ROLE ADMIN) -->
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.index') }}"
                           class="px-3.5 py-2 rounded-lg text-xs font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 transition duration-150 flex items-center space-x-1.5 border border-amber-300">
                            <i class="fa-solid fa-shield-halved text-amber-700"></i>
                            <span>Admin Panel</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                       class="ml-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 hover:bg-[#3F704D] hover:text-white transition duration-150 flex items-center space-x-2 border border-slate-200">
                        <x-community-avatar :user="Auth::user()" size="xs" />
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
        @unless($isLaborOprecZone || $isPerpustakaanZone)
        <a href="{{ route('komunitas') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-users text-[#01795F] mr-2"></i>Komunitas
        </a>
        @endunless
        @unless($isKomunitasZone || $isPerpustakaanZone)
        <a href="{{ route('laboratorium.pai') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-flask text-[#01795F] mr-2"></i>Laboratorium PAI
        </a>
        @endunless
        @unless($isLaborOprecZone || $isKomunitasZone)
        <a href="{{ route('perpustakaan') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-book text-[#01795F] mr-2"></i>Perpustakaan
        </a>
        @endunless
        @unless($isKomunitasZone || $isPerpustakaanZone)
        <a href="{{ route('open.recruitment') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-800 hover:bg-slate-100">
            <i class="fa-solid fa-user-plus text-[#01795F] mr-2"></i>Open Recruitment
        </a>
        @endunless
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold text-amber-800 bg-amber-50">
                    <i class="fa-solid fa-shield-halved text-amber-700 mr-2"></i>Admin Panel
                </a>
            @endif
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

        // Dropdown menu untuk "Komunitas"
        const komunitasBtn = document.getElementById('komunitasDropdownBtn');
        const komunitasMenu = document.getElementById('komunitasMegaMenu');
        const komunitasChevron = document.getElementById('komunitasChevron');
        const komunitasWrapper = document.getElementById('komunitasDropdownWrapper');

        if (komunitasBtn && komunitasMenu) {
            komunitasBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = komunitasMenu.classList.contains('hidden');
                komunitasMenu.classList.toggle('hidden');
                komunitasChevron.classList.toggle('rotate-180', isHidden);
            });

            // Tutup dropdown saat klik di luar area
            document.addEventListener('click', function(e) {
                if (komunitasWrapper && !komunitasWrapper.contains(e.target)) {
                    komunitasMenu.classList.add('hidden');
                    komunitasChevron.classList.remove('rotate-180');
                }
            });

            // Tutup dropdown saat tekan Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    komunitasMenu.classList.add('hidden');
                    komunitasChevron.classList.remove('rotate-180');
                }
            });
        }
    });
</script>