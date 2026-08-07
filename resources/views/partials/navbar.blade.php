@php
    $currentRoute = Route::currentRouteName() ?? '';
    $daftarKomunitasNav = \Illuminate\Support\Facades\Config::get('komunitas.daftar', []);

    $isKomunitasZone = str_contains($currentRoute, 'komunitas');
    $isLaborOprecZone = in_array($currentRoute, ['laboratorium.pai', 'labor', 'open.recruitment', 'open.recruitment.thank-you', 'open.recruitment.submit']);
    $isPerpustakaanZone = ($currentRoute === 'perpustakaan');
@endphp

<header class="sticky top-0 z-50 bg-white/95 border-b border-slate-200 text-slate-900 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Brand Logo & Title -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group" title="Kembali ke Beranda">
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

                <!-- 1. Beranda (Hero Landing /) -->
                <a href="{{ route('landing') }}"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $currentRoute == 'landing' ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-house text-[11px]"></i>
                    <span>Beranda</span>
                </a>

                <!-- 2. Komunitas (sembunyikan di Labor/Oprec & Perpustakaan) -->
                @unless($isLaborOprecZone || $isPerpustakaanZone)
                <div class="relative" id="komunitasDropdownWrapper">
                    <button type="button" id="komunitasDropdownBtn"
                            aria-haspopup="true" aria-expanded="false" aria-controls="komunitasMegaMenu"
                            class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center space-x-1.5 {{ $isKomunitasZone ? 'bg-[#01795F] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-users text-[11px]"></i>
                        <span>Komunitas</span>
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" id="komunitasChevron"></i>
                    </button>

                    <!-- Dropdown Panel Komunitas -->
                    <div id="komunitasMegaMenu"
                         class="hidden absolute right-0 left-auto top-full mt-2 w-[92vw] sm:w-[600px] max-w-[600px] bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden origin-top-right transform transition-all">

                        <!-- Grid Item Komunitas (2 Kolom) -->
                        <div class="grid grid-cols-2 gap-x-6 gap-y-5 p-6">
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

                        <!-- Footer: "Lihat Semua" jadi tombol yang jelas -->
                        <a href="{{ route('komunitas', 'semua') }}"
                           class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#01795F]/5 hover:bg-[#01795F] text-[#01795F] hover:text-white text-xs font-bold border-t border-slate-100 transition-colors">
                            <span>Lihat Semua Komunitas</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
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
                <button id="mobile-menu-btn" type="button"
                        aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-menu"
                        class="p-2.5 rounded-lg text-slate-600 hover:text-[#01795F] hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#01795F] focus-visible:ring-offset-2 transition">
                    <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer + Backdrop Overlay -->
    <div id="mobile-menu-backdrop" class="hidden fixed top-16 sm:top-20 inset-x-0 bottom-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden transition-opacity duration-300" aria-hidden="true"></div>

    <div id="mobile-menu" class="hidden md:hidden relative z-50 border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-1 shadow-lg transition-all duration-300">
        <!-- Beranda -->
        <a href="{{ route('landing') }}"
           class="flex items-center px-3 py-3 rounded-lg text-sm font-semibold {{ $currentRoute == 'landing' ? 'bg-[#01795F] text-white' : 'text-slate-800 hover:bg-slate-100' }}">
            <i class="fa-solid fa-house w-5 text-center {{ $currentRoute == 'landing' ? 'text-white' : 'text-[#01795F]' }}"></i>
            <span class="ml-2">Beranda</span>
        </a>

        @unless($isLaborOprecZone || $isPerpustakaanZone)
        <!-- Komunitas (expandable submenu) -->
        <div>
            <button type="button" id="mobile-komunitas-btn"
                    aria-haspopup="true" aria-expanded="{{ $isKomunitasZone ? 'true' : 'false' }}" aria-controls="mobile-komunitas-submenu"
                    class="w-full flex items-center justify-between px-3 py-3 rounded-lg text-sm font-semibold {{ $isKomunitasZone ? 'bg-[#01795F] text-white' : 'text-slate-800 hover:bg-slate-100' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-users w-5 text-center {{ $isKomunitasZone ? 'text-white' : 'text-[#01795F]' }}"></i>
                    <span class="ml-2">Komunitas</span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isKomunitasZone ? 'rotate-180' : '' }}" id="mobile-komunitas-chevron"></i>
            </button>
            <div id="mobile-komunitas-submenu" class="mt-1 ml-5 pl-3 border-l-2 border-slate-100 space-y-0.5 {{ $isKomunitasZone ? '' : 'hidden' }}">
                <a href="{{ route('komunitas', 'semua') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-semibold {{ (request()->segment(2) === 'semua' || is_null(request()->segment(2))) && $isKomunitasZone ? 'bg-[#01795F]/10 text-[#01795F]' : 'text-slate-700 hover:bg-slate-50 hover:text-[#01795F]' }}">
                    <i class="fa-solid fa-grip w-4 text-center text-[#01795F]"></i>
                    <span class="ml-2">Lihat Semua Komunitas</span>
                </a>
                @foreach($daftarKomunitasNav as $navK)
                    <a href="{{ route('komunitas', $navK['slug']) }}"
                       class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-semibold {{ (request()->segment(2) === $navK['slug']) ? 'bg-[#01795F]/10 text-[#01795F]' : 'text-slate-700 hover:bg-slate-50 hover:text-[#01795F]' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center text-[#01795F]"></i>
                        <span class="ml-2">{{ $navK['nama'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endunless

        @unless($isKomunitasZone || $isPerpustakaanZone)
        <!-- Laboratorium PAI -->
        <a href="{{ route('laboratorium.pai') }}"
           class="flex items-center px-3 py-3 rounded-lg text-sm font-semibold {{ ($currentRoute == 'laboratorium.pai' || $currentRoute == 'labor') ? 'bg-[#01795F] text-white' : 'text-slate-800 hover:bg-slate-100' }}">
            <i class="fa-solid fa-flask w-5 text-center {{ ($currentRoute == 'laboratorium.pai' || $currentRoute == 'labor') ? 'text-white' : 'text-[#01795F]' }}"></i>
            <span class="ml-2">Laboratorium PAI</span>
        </a>
        @endunless

        @unless($isLaborOprecZone || $isKomunitasZone)
        <!-- Perpustakaan -->
        <a href="{{ route('perpustakaan') }}"
           class="flex items-center px-3 py-3 rounded-lg text-sm font-semibold {{ $isPerpustakaanZone ? 'bg-[#01795F] text-white' : 'text-slate-800 hover:bg-slate-100' }}">
            <i class="fa-solid fa-book w-5 text-center {{ $isPerpustakaanZone ? 'text-white' : 'text-[#01795F]' }}"></i>
            <span class="ml-2">Perpustakaan</span>
        </a>
        @endunless

        @unless($isKomunitasZone || $isPerpustakaanZone)
        <!-- Open Recruitment -->
        <a href="{{ route('open.recruitment') }}"
           class="flex items-center px-3 py-3 rounded-lg text-sm font-semibold {{ str_contains($currentRoute, 'open.recruitment') ? 'bg-[#01795F] text-white' : 'text-slate-800 hover:bg-slate-100' }}">
            <i class="fa-solid fa-user-plus w-5 text-center {{ str_contains($currentRoute, 'open.recruitment') ? 'text-white' : 'text-[#01795F]' }}"></i>
            <span class="ml-2">Open Recruitment</span>
        </a>
        @endunless

        <div class="pt-2 mt-2 border-t border-slate-100 space-y-1">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="flex items-center px-3 py-3 rounded-lg text-sm font-bold text-amber-800 bg-amber-50 border border-amber-200">
                        <i class="fa-solid fa-shield-halved w-5 text-center text-amber-700"></i>
                        <span class="ml-2">Admin Panel</span>
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-3 rounded-lg text-sm font-semibold text-slate-800 hover:bg-slate-100">
                    <x-community-avatar :user="Auth::user()" size="xs" />
                    <span class="ml-2">Profil Saya</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 mt-1 rounded-lg text-sm font-semibold bg-[#01795F] text-white hover:bg-[#3F704D] transition">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span class="ml-2">Masuk Akun</span>
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* ============ MOBILE DRAWER ============ */
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const backdrop = document.getElementById('mobile-menu-backdrop');
        const menuIcon = document.getElementById('menu-icon');

        function openMobileMenu() {
            menu.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-label', 'Tutup menu navigasi');
            menuIcon.classList.remove('fa-bars');
            menuIcon.classList.add('fa-xmark');
        }
        function closeMobileMenu() {
            menu.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('aria-label', 'Buka menu navigasi');
            menuIcon.classList.remove('fa-xmark');
            menuIcon.classList.add('fa-bars');
        }

        if (btn && menu) {
            btn.addEventListener('click', function() {
                menu.classList.contains('hidden') ? openMobileMenu() : closeMobileMenu();
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeMobileMenu);
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menu && !menu.classList.contains('hidden')) {
                closeMobileMenu();
            }
        });

        /* ============ MOBILE KOMUNITAS SUBMENU ============ */
        const mKomunitasBtn = document.getElementById('mobile-komunitas-btn');
        const mKomunitasSubmenu = document.getElementById('mobile-komunitas-submenu');
        const mKomunitasChevron = document.getElementById('mobile-komunitas-chevron');
        if (mKomunitasBtn && mKomunitasSubmenu) {
            mKomunitasBtn.addEventListener('click', function() {
                const isHidden = mKomunitasSubmenu.classList.contains('hidden');
                mKomunitasSubmenu.classList.toggle('hidden');
                mKomunitasChevron.classList.toggle('rotate-180', isHidden);
                mKomunitasBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });
        }

        /* ============ DESKTOP KOMUNITAS DROPDOWN ============ */
        const komunitasBtn = document.getElementById('komunitasDropdownBtn');
        const komunitasMenu = document.getElementById('komunitasMegaMenu');
        const komunitasChevron = document.getElementById('komunitasChevron');
        const komunitasWrapper = document.getElementById('komunitasDropdownWrapper');

        function closeDesktopDropdown() {
            if (!komunitasMenu) return;
            komunitasMenu.classList.add('hidden');
            komunitasChevron.classList.remove('rotate-180');
            komunitasBtn.setAttribute('aria-expanded', 'false');
        }

        if (komunitasBtn && komunitasMenu) {
            komunitasBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = komunitasMenu.classList.contains('hidden');
                komunitasMenu.classList.toggle('hidden');
                komunitasChevron.classList.toggle('rotate-180', isHidden);
                komunitasBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });

            document.addEventListener('click', function(e) {
                if (komunitasWrapper && !komunitasWrapper.contains(e.target)) {
                    closeDesktopDropdown();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDesktopDropdown();
                }
            });
        }
    });
</script>