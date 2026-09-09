@php
    $currentRoute = Route::currentRouteName() ?? '';

    $isKomunitasZone = str_contains($currentRoute, 'komunitas');
    $isLainnya       = str_contains($currentRoute, 'open.recruitment');
    $isLaborZone     = in_array($currentRoute, ['laboratorium.pai', 'labor', 'laboratorium.profil', 'laboratorium.modul', 'laboratorium.tugas']);

    // Menu utama
    $navBefore = [
        ['label' => 'Beranda', 'href' => route('landing'), 'active' => $currentRoute === 'landing'],
    ];
    $navAfter = [
        ['label' => 'Info', 'href' => route('info'), 'active' => $currentRoute === 'info' || $currentRoute === 'berita.show'],
    ];

    // Item sekunder di dropdown "Lainnya"
    $navLainnya = [
        ['label' => 'Open Recruitment', 'href' => route('open.recruitment'), 'icon' => 'fa-user-plus'],
        [
            'label'  => 'Saran & Masukan',
            'href'   => 'https://docs.google.com/forms/d/e/1FAIpQLScLDeCvGI17R7Z-NkckFV-N9Sm1Jfl8-eOEl20ZFVfFDeebgQ/viewform',
            'target' => '_blank',
            'rel'    => 'noopener noreferrer',
            'icon'   => 'fa-comment-dots',
        ],
    ];

    $link = fn ($active) => $active ? 'text-[var(--gold)]' : 'text-white/70 hover:text-white';
    $underline = fn ($active) => 'absolute left-0 bottom-0 h-[2px] bg-[var(--gold)] transition-all duration-300 '
        . ($active ? 'w-full' : 'w-0 group-hover:w-full');
    $navLinkClass = 'group relative inline-flex items-center pb-1 text-sm font-semibold tracking-tight transition-colors duration-200';

    $navIcon = [
        'Beranda'          => 'fa-house',
        'Laboratorium PAI' => 'fa-flask',
        'Perpustakaan'     => 'fa-book-open',
        'Komunitas'        => 'fa-users',
        'Info'             => 'fa-bullhorn',
        'Lainnya'          => 'fa-ellipsis',
    ];
@endphp

<header class="sticky top-0 z-[70] bg-[#0D2818]/95 backdrop-blur-md border-b border-white/10 text-[var(--cream)] transition-all">
    {{-- Reading progress bar --}}
    <div id="reading-progress" class="reading-progress-bar"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 xl:h-20">

            {{-- ===== BRAND (kiri) ===== --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group shrink-0" title="Kembali ke Beranda">
                <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt="TSAQIB Logo" class="h-9 sm:h-10 w-auto object-contain shrink-0 group-hover:scale-105 transition-transform duration-200">
                <span class="leading-none">
                    <span class="font-display font-extrabold text-base sm:text-lg tracking-tight text-[var(--cream)] block">TSAQIB</span>
                    <span class="text-[9px] sm:text-[10px] text-[var(--gold)] font-bold tracking-[0.14em] uppercase block mt-0.5">FSI SMAN 1 Bukittinggi</span>
                </span>
            </a>

            {{-- ===== NAV TENGAH (≥ xl) ===== --}}
            <nav class="hidden xl:flex items-center gap-7 lg:gap-8 font-label whitespace-nowrap">
                @foreach($navBefore as $item)
                    <a href="{{ $item['href'] }}" class="{{ $navLinkClass }} {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                        @if($item['active']) <span class="nav-bead"></span> @endif
                    </a>
                @endforeach

                {{-- Laboratorium PAI dropdown --}}
                <div class="relative inline-flex items-center">
                    <button type="button" id="labor-toggle"
                            class="{{ $navLinkClass }} gap-1.5 {{ $link($isLaborZone) }}"
                            aria-haspopup="true" aria-expanded="false" aria-controls="labor-menu">
                        <span>Laboratorium PAI</span>
                        <i class="fa-solid fa-chevron-down text-[8px] leading-none opacity-70 transition-transform duration-200"></i>
                        <span class="{{ $underline($isLaborZone) }}"></span>
                        @if($isLaborZone) <span class="nav-bead"></span> @endif
                    </button>
                    <div id="labor-menu" class="absolute left-0 top-full pt-2.5 opacity-0 invisible z-[60] transition-all duration-200">
                        <div class="min-w-[220px] rounded-xl border border-white/10 bg-[#143520] shadow-2xl overflow-hidden py-2 backdrop-blur-md">
                            <a href="{{ route('laboratorium.pai') }}" class="labor-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/75 hover:text-[var(--gold)] hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-building-columns text-[10px] text-[var(--gold)]/70 w-4"></i>
                                Ikhtisar Laboratorium
                            </a>
                            <a href="{{ route('laboratorium.profil') }}" class="labor-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/75 hover:text-[var(--gold)] hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-user-tie text-[10px] text-[var(--gold)]/70 w-4"></i>
                                Profil &amp; Guru
                            </a>
                            <a href="{{ route('laboratorium.modul') }}" class="labor-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/75 hover:text-[var(--gold)] hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-book-open text-[10px] text-[var(--gold)]/70 w-4"></i>
                                Modul Pembelajaran
                            </a>
                            <a href="{{ route('laboratorium.tugas') }}" class="labor-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/75 hover:text-[var(--gold)] hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-clipboard-check text-[10px] text-[var(--gold)]/70 w-4"></i>
                                Tugas Siswa
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('perpustakaan') }}" class="{{ $navLinkClass }} {{ $link($currentRoute === 'perpustakaan') }}">
                    Perpustakaan
                    <span class="{{ $underline($currentRoute === 'perpustakaan') }}"></span>
                    @if($currentRoute === 'perpustakaan') <span class="nav-bead"></span> @endif
                </a>

                <a href="{{ route('komunitas', 'semua') }}" class="{{ $navLinkClass }} {{ $link($isKomunitasZone) }}">
                    Komunitas
                    <span class="{{ $underline($isKomunitasZone) }}"></span>
                    @if($isKomunitasZone) <span class="nav-bead"></span> @endif
                </a>

                @foreach($navAfter as $item)
                    <a href="{{ $item['href'] }}" class="{{ $navLinkClass }} {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                        @if($item['active']) <span class="nav-bead"></span> @endif
                    </a>
                @endforeach

                {{-- Lainnya dropdown --}}
                <div class="relative inline-flex items-center">
                    <button type="button" id="lainnya-toggle"
                            class="{{ $navLinkClass }} gap-1.5 {{ $link($isLainnya) }}"
                            aria-haspopup="true" aria-expanded="false" aria-controls="lainnya-menu">
                        <span>Lainnya</span>
                        <i class="fa-solid fa-chevron-down text-[8px] leading-none opacity-70 transition-transform duration-200"></i>
                        <span class="{{ $underline($isLainnya) }}"></span>
                    </button>
                    <div id="lainnya-menu" class="absolute left-0 top-full pt-2.5 opacity-0 invisible z-[60] transition-all duration-200">
                        <div class="min-w-[210px] rounded-xl border border-white/10 bg-[#143520] shadow-2xl overflow-hidden py-2 backdrop-blur-md">
                            @foreach($navLainnya as $item)
                                @php
                                    $itemAttrs = '';
                                    if (!empty($item['target'])) $itemAttrs .= ' target="'.$item['target'].'"';
                                    if (!empty($item['rel']))    $itemAttrs .= ' rel="'.$item['rel'].'"';
                                @endphp
                                <a href="{{ $item['href'] }}"{!! $itemAttrs !!}
                                   class="lainnya-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/75 hover:text-[var(--gold)] hover:bg-white/5 transition-colors">
                                    <i class="fa-solid {{ $item['icon'] ?? 'fa-arrow-right' }} text-[10px] text-[var(--gold)]/70 w-4"></i>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </nav>

            {{-- ===== KANAN: Search & Auth (≥ xl) & Hamburger (< xl) ===== --}}
            <div class="flex items-center gap-2.5 sm:gap-4 shrink-0">
                {{-- Command Palette Trigger (Desktop/Tablet) --}}
                <button type="button" data-open-cmd
                        class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 hover:border-[var(--gold)]/40 text-white/55 hover:text-white text-xs transition cursor-pointer"
                        title="Pencarian Cepat (Ctrl+K)">
                    <i class="fa-solid fa-magnifying-glass text-[10px] text-[var(--gold)]"></i>
                    <span class="text-[11px] font-medium hidden md:inline">Cari fitur...</span>
                    <span class="cmd-kbd text-[10px] py-0 px-1.5 h-4 ml-0.5">⌘K</span>
                </button>

                {{-- Desktop auth buttons --}}
                <div class="hidden xl:flex items-center gap-4">
                    <span class="w-px h-5 bg-white/10" aria-hidden="true"></span>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.index') }}"
                               class="text-xs uppercase tracking-[0.16em] font-bold text-amber-300 hover:text-amber-200 transition-colors duration-200">
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" title="Profil"
                           class="rounded-full p-0.5 ring-1 ring-white/20 hover:ring-[var(--gold)] transition duration-200">
                            <x-community-avatar :user="Auth::user()" size="xs" />
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide text-[var(--gold)] border border-[var(--gold)]/50 hover:bg-[var(--gold)]/10 hover:border-[var(--gold)] transition-colors duration-200">
                            <i class="fa-solid fa-right-to-bracket mr-1.5 text-[10px]"></i> Masuk
                        </a>
                    @endauth
                </div>

                {{-- Mobile: Search icon, user avatar, hamburger --}}
                <div class="flex xl:hidden items-center gap-2">
                    <button type="button" data-open-cmd
                            class="w-10 h-10 flex sm:hidden items-center justify-center text-white/70 hover:text-[var(--gold)] bg-white/5 border border-white/10 hover:border-[var(--gold)]/40 rounded-xl transition cursor-pointer"
                            aria-label="Pencarian cepat">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>

                    @auth
                        <a href="{{ route('profile.edit') }}" class="rounded-full p-0.5 ring-1 ring-white/20">
                            <x-community-avatar :user="Auth::user()" size="xs" />
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold text-[var(--gold)] border border-[var(--gold)]/40 hover:bg-[var(--gold)]/10">
                            Masuk
                        </a>
                    @endauth

                    {{-- Accessible 44x44 Hamburger Button --}}
                    <button id="mobile-menu-btn" type="button"
                            aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-menu"
                            class="w-10 h-10 flex items-center justify-center text-[var(--cream)] hover:text-white bg-white/5 border border-white/10 hover:border-[var(--gold)]/40 rounded-xl transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--gold)]">
                        <i class="fa-solid fa-bars text-base" id="menu-icon"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</header>

{{-- ===== MOBILE DRAWER (< xl) ===== --}}
<div id="mobile-menu-backdrop" class="hidden fixed inset-0 z-40 bg-black/70 backdrop-blur-sm xl:hidden transition-opacity"></div>

<div id="mobile-menu" class="hidden fixed top-16 inset-x-0 z-50 xl:hidden border-t border-white/10 bg-[#0D2818]/98 px-5 py-4 space-y-1 shadow-2xl max-h-[calc(100vh-4.5rem)] overflow-y-auto antialiased">
    @foreach($navBefore as $item)
        <a href="{{ $item['href'] }}" class="mnav-item {{ $item['active'] ? 'is-active' : '' }}">
            <span class="mnav-ikon"><i class="fa-solid {{ $navIcon[$item['label']] ?? 'fa-angle-right' }}"></i></span>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach

    {{-- Laboratorium PAI (mobile) --}}
    <div>
        <button type="button" data-mobile-labor-toggle class="mnav-item w-full" aria-expanded="false" aria-controls="mobile-labor">
            <span class="mnav-ikon"><i class="fa-solid fa-flask"></i></span>
            <span>Laboratorium PAI</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-white/45 transition-transform duration-200 ml-auto"></i>
        </button>
        <div id="mobile-labor" class="hidden pl-4 border-l border-white/15 ml-6 mb-1 mt-0.5 space-y-0.5">
            <a href="{{ route('laboratorium.pai') }}" class="block py-2 px-2 text-xs text-white/75 hover:text-[var(--gold)] transition-colors rounded-lg">
                <i class="fa-solid fa-building-columns text-[10px] mr-2 text-[var(--gold)]/70"></i>Ikhtisar Laboratorium
            </a>
            <a href="{{ route('laboratorium.profil') }}" class="block py-2 px-2 text-xs text-white/75 hover:text-[var(--gold)] transition-colors rounded-lg">
                <i class="fa-solid fa-user-tie text-[10px] mr-2 text-[var(--gold)]/70"></i>Profil &amp; Guru
            </a>
            <a href="{{ route('laboratorium.modul') }}" class="block py-2 px-2 text-xs text-white/75 hover:text-[var(--gold)] transition-colors rounded-lg">
                <i class="fa-solid fa-book-open text-[10px] mr-2 text-[var(--gold)]/70"></i>Modul Pembelajaran
            </a>
            <a href="{{ route('laboratorium.tugas') }}" class="block py-2 px-2 text-xs text-white/75 hover:text-[var(--gold)] transition-colors rounded-lg">
                <i class="fa-solid fa-clipboard-check text-[10px] mr-2 text-[var(--gold)]/70"></i>Tugas Siswa
            </a>
        </div>
    </div>

    <a href="{{ route('perpustakaan') }}" class="mnav-item {{ $currentRoute === 'perpustakaan' ? 'is-active' : '' }}">
        <span class="mnav-ikon"><i class="fa-solid fa-book-open"></i></span>
        <span>Perpustakaan</span>
    </a>

    <a href="{{ route('komunitas', 'semua') }}" class="mnav-item {{ $isKomunitasZone ? 'is-active' : '' }}">
        <span class="mnav-ikon"><i class="fa-solid fa-users"></i></span>
        <span>Komunitas</span>
    </a>

    @foreach($navAfter as $item)
        <a href="{{ $item['href'] }}" class="mnav-item {{ $item['active'] ? 'is-active' : '' }}">
            <span class="mnav-ikon"><i class="fa-solid {{ $navIcon[$item['label']] ?? 'fa-angle-right' }}"></i></span>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach

    {{-- Lainnya (mobile) --}}
    <div>
        <button type="button" data-mobile-lainnya-toggle class="mnav-item w-full" aria-expanded="false" aria-controls="mobile-lainnya">
            <span class="mnav-ikon"><i class="fa-solid fa-ellipsis"></i></span>
            <span>Lainnya</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-white/45 transition-transform duration-200 ml-auto"></i>
        </button>
        <div id="mobile-lainnya" class="hidden pl-4 border-l border-white/15 ml-6 mb-1 mt-0.5 space-y-0.5">
            @foreach($navLainnya as $item)
                @php
                    $itemAttrs = '';
                    if (!empty($item['target'])) $itemAttrs .= ' target="'.$item['target'].'"';
                    if (!empty($item['rel']))    $itemAttrs .= ' rel="'.$item['rel'].'"';
                @endphp
                <a href="{{ $item['href'] }}"{!! $itemAttrs !!} class="block py-2 px-2 text-xs text-white/75 hover:text-[var(--gold)] transition-colors rounded-lg">
                    <i class="fa-solid {{ $item['icon'] ?? 'fa-arrow-right' }} text-[10px] mr-2 text-[var(--gold)]/70"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Mobile auth & social --}}
    <div class="pt-3 mt-3 border-t border-white/10 space-y-3">
        <div class="flex items-center justify-between px-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-white/40">Media Sosial</span>
            <div class="flex items-center gap-4">
                <a href="https://www.instagram.com/fsi.smansa_landbouw?igsh=MXVzMzd5Nms0eDZpNQ==" target="_blank" rel="noopener" class="text-white/60 hover:text-[var(--gold)] transition-colors">
                    <i class="fa-brands fa-instagram text-lg"></i>
                </a>
                <a href="https://www.facebook.com/share/1BJMFJvK5k/" target="_blank" rel="noopener" class="text-white/60 hover:text-[var(--gold)] transition-colors">
                    <i class="fa-brands fa-facebook text-lg"></i>
                </a>
                <a href="https://ytfsi.carrd.co" target="_blank" rel="noopener" class="text-white/60 hover:text-[var(--gold)] transition-colors">
                    <i class="fa-brands fa-youtube text-lg"></i>
                </a>
            </div>
        </div>

        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.index') }}" class="mnav-item text-amber-300">
                    <span class="mnav-ikon" style="background:rgba(252,191,73,.15); color:rgb(252,211,77);"><i class="fa-solid fa-shield-halved"></i></span>
                    <span>Admin Panel</span>
                </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="mnav-item">
                <x-community-avatar :user="Auth::user()" size="xs" />
                <span>Profil Saya</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-primary w-full py-3">
                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk ke TSAQIB
            </a>
        @endauth
    </div>
</div>

<style>
    /* Dropdown transitions */
    #lainnya-menu, #labor-menu {
        transform-origin: top left;
        transform: translateY(-6px) scale(0.97);
        transition: opacity 0.18s ease-out, transform 0.18s ease-out, visibility 0.18s ease-out;
    }
    #lainnya-menu.is-open, #labor-menu.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    #labor-toggle[aria-expanded="true"] i, #lainnya-toggle[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    /* Mobile drawer items */
    #mobile-menu .mnav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-height: 44px;
        padding: 0.55rem 0.75rem;
        border-radius: 0.65rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: rgba(247, 245, 239, 0.85);
        transition: background 0.15s ease, color 0.15s ease;
    }
    #mobile-menu .mnav-item:hover {
        background: rgba(247, 245, 239, 0.06);
        color: var(--cream);
    }
    #mobile-menu .mnav-item.is-active {
        background: rgba(1, 121, 95, 0.2);
        color: var(--gold);
        border: 1px solid rgba(201, 166, 107, 0.25);
    }
    #mobile-menu .mnav-ikon {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        background: rgba(247, 245, 239, 0.06);
        color: var(--gold);
    }
    #mobile-menu .mnav-item.is-active .mnav-ikon {
        background: rgba(201, 166, 107, 0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn      = document.getElementById('mobile-menu-btn');
        const menu     = document.getElementById('mobile-menu');
        const backdrop = document.getElementById('mobile-menu-backdrop');
        const menuIcon = document.getElementById('menu-icon');

        function openMenu() {
            menu.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            btn.setAttribute('aria-expanded', 'true');
            menuIcon.classList.replace('fa-bars', 'fa-xmark');
        }
        function closeMenu() {
            menu.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            btn.setAttribute('aria-expanded', 'false');
            menuIcon.classList.replace('fa-xmark', 'fa-bars');
        }

        if (btn && menu) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                menu.classList.contains('hidden') ? openMenu() : closeMenu();
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeMenu);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu && !menu.classList.contains('hidden')) closeMenu();
        });

        // Mobile sub-toggles
        const setupMobileToggle = (toggleSelector, panelId) => {
            const toggle = document.querySelector(toggleSelector);
            const panel  = document.getElementById(panelId);
            if (toggle && panel) {
                const chevron = toggle.querySelector('.fa-chevron-down');
                toggle.addEventListener('click', () => {
                    const open = !panel.classList.contains('hidden');
                    panel.classList.toggle('hidden', open);
                    toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
                    if (chevron) chevron.classList.toggle('rotate-180', !open);
                });
            }
        };
        setupMobileToggle('[data-mobile-labor-toggle]', 'mobile-labor');
        setupMobileToggle('[data-mobile-lainnya-toggle]', 'mobile-lainnya');

        // Desktop click toggles
        const setupDropdown = (btnId, menuId) => {
            const dBtn  = document.getElementById(btnId);
            const dMenu = document.getElementById(menuId);
            if (!dBtn || !dMenu) return;

            const open = () => { dMenu.classList.add('is-open'); dBtn.setAttribute('aria-expanded', 'true'); };
            const close = () => { dMenu.classList.remove('is-open'); dBtn.setAttribute('aria-expanded', 'false'); };

            dBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dBtn.getAttribute('aria-expanded') === 'true' ? close() : open();
            });
            document.addEventListener('click', (e) => {
                if (dBtn.getAttribute('aria-expanded') === 'true' && !dMenu.contains(e.target) && !dBtn.contains(e.target)) {
                    close();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && dBtn.getAttribute('aria-expanded') === 'true') close();
            });
        };
        setupDropdown('labor-toggle', 'labor-menu');
        setupDropdown('lainnya-toggle', 'lainnya-menu');
    });
</script>
