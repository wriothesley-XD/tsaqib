@php
    $currentRoute = Route::currentRouteName() ?? '';

    $isKomunitasZone = str_contains($currentRoute, 'komunitas');
    $isLainnya       = str_contains($currentRoute, 'open.recruitment');

    // Menu utama (sentence case). Komunitas disisipkan di antara $navBefore & $navAfter.
    $navBefore = [
        ['label' => 'Beranda',          'href' => route('landing'),          'active' => $currentRoute === 'landing'],
        ['label' => 'Laboratorium PAI', 'href' => route('laboratorium.pai'), 'active' => in_array($currentRoute, ['laboratorium.pai', 'labor'])],
        ['label' => 'Perpustakaan',     'href' => route('perpustakaan'),     'active' => $currentRoute === 'perpustakaan'],
    ];
    $navAfter = [
        ['label' => 'Info', 'href' => route('info'), 'active' => $currentRoute === 'info' || $currentRoute === 'berita.show'],
    ];
    // Item sekunder yang dirapikan ke dropdown "Lainnya".
    $navLainnya = [
        ['label' => 'Open Recruitment', 'href' => route('open.recruitment')],
        [
            'label'  => 'Saran & Masukan',
            'href'   => 'https://docs.google.com/forms/d/e/1FAIpQLScLDeCvGI17R7Z-NkckFV-N9Sm1Jfl8-eOEl20ZFVfFDeebgQ/viewform',
            // Link eksternal (Google Form) → tab baru, aman dibuka via noopener.
            'target' => '_blank',
            'rel'    => 'noopener noreferrer',
            'icon'   => 'fa-message',
        ],
        [
            // Halaman tim pengembang (/credits) — internal link.
            'label' => 'Tim Kami',
            'href'  => route('credits'),
            'icon'  => 'fa-user-group',
        ],
    ];

    // Palet minimalis: tenang saat non-aktif, emas saat aktif. Indikator aktif = garis bawah emas.
    $link      = fn ($active) => $active ? 'text-[var(--gold)]' : 'text-white/55 hover:text-white';
    $underline = fn ($active) => 'absolute left-0 bottom-0 h-[2px] bg-[var(--gold)] transition-all duration-300 '
        . ($active ? 'w-full' : 'w-0 group-hover:w-full');
    // Base kelas link: sentence case. inline-flex+items-center dipakai SEMUA item
    // (link & trigger dropdown) agar box-model & baseline identik → sejajar & rapi.
    $navLinkClass = 'group relative inline-flex items-center pb-1 text-sm font-semibold tracking-tight transition-colors duration-200';

    // Ikon per item menu mobile (reuse ikon yang sudah dipakai di halaman terkait).
    $navIcon = [
        'Beranda'          => 'fa-house',
        'Laboratorium PAI' => 'fa-flask',
        'Perpustakaan'     => 'fa-book-open',
        'Komunitas'        => 'fa-users',
        'Info'             => 'fa-bullhorn',
        'Lainnya'          => 'fa-ellipsis',
    ];

    // Catatan: route 'beranda' (/beranda) hanya redirect ke komunitas, bukan home.
@endphp

{{-- Token tema gelap (.brand-mark, .cta-primary, CSS vars, font-display/label) di-supply
    tiap halaman via @include('partials.theme-head'). Navbar ini sticky (memesan tempat
    sendiri) -> konten di bawahnya nggak ketimpa, nggak butuh padding-top. --}}

<header class="sticky top-0 z-[70] bg-[#10140F] border-b border-white/10 text-[var(--cream)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 xl:h-20 gap-4">

            {{-- ===== BRAND (kiri) ===== --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group shrink-0" title="Kembali ke Beranda">
                <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt="TSAQIB Logo" class="h-10 sm:h-11 w-auto object-contain shrink-0 group-hover:scale-105 transition-transform">
                <span class="leading-none hidden sm:block">
                    <span class="font-display font-extrabold text-base tracking-tight text-[var(--cream)] block">TSAQIB</span>
                    <span class="text-[9px] text-[var(--gold)] font-bold tracking-[0.14em] uppercase block mt-1">FSI SMAN 1 Bukittinggi</span>
                </span>
            </a>

            {{-- ===== NAV TENGAH (≥ xl) — terpusat via flex-1 justify-center, BUKAN absolute ===== --}}
            <nav class="hidden xl:flex flex-1 justify-center items-center gap-8 font-label whitespace-nowrap">

                @foreach($navBefore as $item)
                    <a href="{{ $item['href'] }}"
                       class="{{ $navLinkClass }} {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                    </a>
                @endforeach

                {{-- Komunitas — link langsung ke feed gabungan semua komunitas (bukan dropdown) --}}
                <a href="{{ route('komunitas', 'semua') }}"
                   class="{{ $navLinkClass }} {{ $link($isKomunitasZone) }}">
                    Komunitas
                    <span class="{{ $underline($isKomunitasZone) }}"></span>
                </a>

                @foreach($navAfter as $item)
                    <a href="{{ $item['href'] }}"
                       class="{{ $navLinkClass }} {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                    </a>
                @endforeach

                {{-- Lainnya — dropdown berisi menu sekunder (Open Recruitment, dst.).
                     Click-to-toggle (bukan hover) biar konsisten di mouse, touch, & keyboard.
                     Tampil/sembunyi diatur via JS (#lainnya-toggle / #lainnya-menu di <script> bawah). --}}
                <div class="relative inline-flex items-center">
                    <button type="button" id="lainnya-toggle"
                            class="{{ $navLinkClass }} gap-1.5 {{ $link($isLainnya) }}"
                            aria-haspopup="true" aria-expanded="false" aria-controls="lainnya-menu">
                        <span>Lainnya</span>
                        <i class="fa-solid fa-chevron-down text-[8px] leading-none opacity-70 transition-transform duration-200"></i>
                        <span class="{{ $underline($isLainnya) }}"></span>
                    </button>
                    <div id="lainnya-menu"
                         class="absolute left-0 top-full pt-2.5 opacity-0 invisible z-[60]">
                        <div class="min-w-[210px] rounded-xl border border-white/10 bg-[#161a14] ring-1 ring-black/50 shadow-[0_24px_60px_-15px_rgba(0,0,0,0.8)] overflow-hidden py-2">
                            @foreach($navLainnya as $item)
                                @php
                                    // Ikon opsional per-item (default: panah 'go to' lama);
                                    // atribut target/rel opsional untuk link eksternal.
                                    $itemIcon  = $item['icon'] ?? 'fa-arrow-right';
                                    $itemAttrs = '';
                                    if (!empty($item['target'])) $itemAttrs .= ' target="'.$item['target'].'"';
                                    if (!empty($item['rel']))    $itemAttrs .= ' rel="'.$item['rel'].'"';
                                @endphp
                                <a href="{{ $item['href'] }}"{{ $itemAttrs }}
                                   class="lainnya-item flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-white/70 hover:text-[var(--gold)] hover:bg-white/5">
                                    <i class="fa-solid {{ $itemIcon }} text-[9px] text-white/30"></i>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </nav>

            {{-- ===== KANAN: Auth (≥ xl) + Hamburger (< xl) ===== --}}
            <div class="flex items-center gap-4 shrink-0">

                <div class="hidden xl:flex items-center gap-5">
                    {{-- Social media (selalu tampil; tema minimalis — brighten on hover) --}}
                    <div class="flex items-center gap-3">
                        <a href="https://www.instagram.com/fsi.smansa_landbouw?igsh=MXVzMzd5Nms0eDZpNQ==" target="_blank" rel="noopener" aria-label="TSAQIB di Instagram" class="text-white/55 hover:text-white transition-colors duration-200">
                            <i class="fa-brands fa-instagram text-base"></i>
                        </a>
                        <a href="https://www.facebook.com/share/1BJMFJvK5k/" target="_blank" rel="noopener" aria-label="TSAQIB di Facebook" class="text-white/55 hover:text-white transition-colors duration-200">
                            <i class="fa-brands fa-facebook text-base"></i>
                        </a>
                        <a href="https://ytfsi.carrd.co" target="_blank" rel="noopener" aria-label="TSAQIB di YouTube" class="text-white/55 hover:text-white transition-colors duration-200">
                            <i class="fa-brands fa-youtube text-base"></i>
                        </a>
                    </div>

                    {{-- Pembatas tipis sebelum area Admin/Akun --}}
                    <span class="w-px h-5 bg-white/10" aria-hidden="true"></span>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.index') }}"
                               class="text-xs uppercase tracking-[0.16em] font-semibold text-amber-300/85 hover:text-amber-200 transition-colors duration-200">
                                Admin
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" title="Profil"
                           class="rounded-full p-0.5 ring-1 ring-white/15 hover:ring-[var(--gold)] transition duration-200">
                            <x-community-avatar :user="Auth::user()" size="xs" />
                        </a>
                    @else
                        {{-- CTA utama: satu-satunya elemen solid green di navbar --}}
                        <a href="{{ route('login') }}"
                           class="cta-primary inline-flex items-center px-5 py-2.5 rounded-full text-xs font-bold tracking-wide text-white">
                            Masuk
                        </a>
                    @endauth
                </div>

                <button id="mobile-menu-btn" type="button"
                        aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-menu"
                        class="xl:hidden p-2 -mr-2 text-[var(--cream)] hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#01795F] rounded-full transition">
                    <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>
        </div>
    </div>
</header>

{{--
    ===== MOBILE DRAWER (< xl) =====
    Sengaja ditarik KELUAR dari <header>. Header memakai backdrop-blur-md
    (backdrop-filter), dan ancestor ber-backdrop-filter memaksa seluruh
    subtree ter-rasterize pada layer terpisah sekaligus menonaktifkan
    subpixel font rendering -> teks link menu tampak blur/lembek.
    Di luar header, container teks bukan lagi descendant backdrop-filter,
    posisi memakai fixed relatif viewport, dan teks kembali tajam.
--}}
<div id="mobile-menu-backdrop" class="hidden fixed top-16 xl:top-20 inset-x-0 bottom-0 z-40 bg-black/50 backdrop-blur-sm xl:hidden"></div>

<div id="mobile-menu" class="hidden fixed top-16 xl:top-20 inset-x-0 z-50 xl:hidden border-t border-white/5 bg-[#10140F] px-5 py-3 space-y-0.5 shadow-2xl max-h-[calc(100vh-4rem)] overflow-y-auto antialiased">

        {{-- Daftar menu utama (ikon + tap target ≥44px). --}}
        @foreach($navBefore as $item)
            <a href="{{ $item['href'] }}"
               class="mnav-item {{ $item['active'] ? 'is-active' : '' }}">
                <span class="mnav-ikon"><i class="fa-solid {{ $navIcon[$item['label']] ?? 'fa-angle-right' }}"></i></span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        {{-- Komunitas — link langsung ke feed gabungan semua komunitas (bukan dropdown). --}}
        <a href="{{ route('komunitas', 'semua') }}"
           class="mnav-item {{ $isKomunitasZone ? 'is-active' : '' }}">
            <span class="mnav-ikon"><i class="fa-solid {{ $navIcon['Komunitas'] }}"></i></span>
            <span>Komunitas</span>
        </a>

        @foreach($navAfter as $item)
            <a href="{{ $item['href'] }}"
               class="mnav-item {{ $item['active'] ? 'is-active' : '' }}">
                <span class="mnav-ikon"><i class="fa-solid {{ $navIcon[$item['label']] ?? 'fa-angle-right' }}"></i></span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        {{-- Lainnya (mobile) — toggle ekspandable berisi menu sekunder. --}}
        <div>
            <button type="button" data-mobile-lainnya-toggle
                    class="mnav-item w-full"
                    aria-expanded="false" aria-controls="mobile-lainnya">
                <span class="mnav-ikon"><i class="fa-solid {{ $navIcon['Lainnya'] }}"></i></span>
                <span>Lainnya</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-white/45 transition-transform duration-200 ml-auto"></i>
            </button>
            <div id="mobile-lainnya" class="hidden pl-4 border-l border-white/10 ml-6 mb-1 mt-0.5 space-y-0.5">
                @foreach($navLainnya as $item)
                    @php
                        $itemAttrs = '';
                        if (!empty($item['target'])) $itemAttrs .= ' target="'.$item['target'].'"';
                        if (!empty($item['rel']))    $itemAttrs .= ' rel="'.$item['rel'].'"';
                    @endphp
                    <a href="{{ $item['href'] }}"{{ $itemAttrs }}
                       class="block py-2.5 px-2 text-sm text-white/65 hover:text-[var(--gold)] transition-colors duration-200 rounded-lg">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ===== Seksi terpisah: Ikuti Kami + Masuk (divider di atas) ===== --}}
        <div class="pt-3 mt-3 border-t border-white/10 space-y-3">
            {{-- Social media (mobile) --}}
            <div>
                <p class="px-1 mb-2 text-[10px] font-semibold uppercase tracking-[0.16em] text-white/40">Ikuti Kami</p>
                <div class="flex items-center gap-5 px-1">
                    <a href="https://www.instagram.com/fsi.smansa_landbouw?igsh=MXVzMzd5Nms0eDZpNQ==" target="_blank" rel="noopener" aria-label="TSAQIB di Instagram" class="text-white/70 hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-instagram text-xl"></i>
                    </a>
                    <a href="https://www.facebook.com/share/1BJMFJvK5k/" target="_blank" rel="noopener" aria-label="TSAQIB di Facebook" class="text-white/70 hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-facebook text-xl"></i>
                    </a>
                    <a href="https://ytfsi.carrd.co" target="_blank" rel="noopener" aria-label="TSAQIB di YouTube" class="text-white/70 hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>

            {{-- Area Auth (mobile) --}}
            <div class="space-y-1">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.index') }}" class="mnav-item">
                            <span class="mnav-ikon" style="background:rgba(252,191,73,.15); color:rgb(252,211,77);"><i class="fa-solid fa-shield-halved"></i></span>
                            <span class="text-amber-300/90">Admin Panel</span>
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="mnav-item">
                        <x-community-avatar :user="Auth::user()" size="xs" />
                        <span>Profil Saya</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="cta-primary flex justify-center items-center gap-2 w-full px-4 py-3 rounded-full text-sm font-bold text-white">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>

<style>
    /* ===== "Lainnya" dropdown (desktop) — animasi panel + stagger item =====
       Visibilitas & animasi panel diatur di sini (bukan lewat kelas Tailwind)
       supaya transition selalu hadir di setiap halaman, baik yang pakai Tailwind
       CDN (Beranda) maupun app.css ter-compile. JS hanya toggle .is-open +
       aria-expanded. opacity-0/invisible di markup menjaga tidak ada FOUC saat
       halaman pertama dimuat. */
    #lainnya-menu{
        transform-origin: top left;
        transform: translateY(-8px) scale(.96);
        transition: opacity .18s ease-out, transform .18s ease-out, visibility .18s ease-out;
    }
    #lainnya-menu.is-open{
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    /* Chevron berputar halus mengikuti state (driven by aria-expanded). */
    #lainnya-toggle i{ transition: transform .2s ease-out; }
    #lainnya-toggle[aria-expanded="true"] i{ transform: rotate(180deg); }
    /* Stagger: tiap item muncul bergantian (fade + slide kanan) saat panel dibuka. */
    #lainnya-menu .lainnya-item{
        opacity: 0;
        transform: translateX(-6px);
        transition: opacity .16s ease-out, transform .16s ease-out, color .15s ease, background-color .15s ease;
    }
    #lainnya-menu.is-open .lainnya-item{ opacity: 1; transform: translateX(0); }
    #lainnya-menu.is-open .lainnya-item:nth-child(1){ transition-delay: .05s; }
    #lainnya-menu.is-open .lainnya-item:nth-child(2){ transition-delay: .09s; }
    #lainnya-menu.is-open .lainnya-item:nth-child(3){ transition-delay: .13s; }
    #lainnya-menu.is-open .lainnya-item:nth-child(4){ transition-delay: .17s; }
    @media (prefers-reduced-motion: reduce){
        #lainnya-menu, #lainnya-menu .lainnya-item{
            transition: none !important;
            transform: none !important;
        }
    }

    /* ===== Mobile nav items (#mobile-menu) — visual: ikon + tap target ≥44px =====
       Hanya mempengaruhi menu mobile; menu desktop (≥ xl) tak tersentuh. */
    #mobile-menu .mnav-item{
        display:flex; align-items:center; gap:.85rem;
        min-height:44px;                 /* tap target aksesibilitas */
        padding:.6rem .75rem;
        border-radius:.7rem;
        font-size:.9rem; font-weight:600;
        color:rgba(247,245,239,.82);
        transition:background .15s ease, color .15s ease;
    }
    #mobile-menu .mnav-item:hover{ background:rgba(247,245,239,.06); color:var(--cream); }
    #mobile-menu .mnav-item.is-active{ background:rgba(1,121,95,.18); color:var(--gold); }
    #mobile-menu .mnav-item .mnav-ikon{
        width:32px; height:32px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        border-radius:.6rem; font-size:.8rem;
        background:rgba(247,245,239,.06); color:var(--gold);
    }
    #mobile-menu .mnav-item.is-active .mnav-ikon{ background:rgba(201,166,107,.18); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        /* ============ MOBILE DRAWER TOGGLE ============ */
        const btn      = document.getElementById('mobile-menu-btn');
        const menu     = document.getElementById('mobile-menu');
        const backdrop = document.getElementById('mobile-menu-backdrop');
        const menuIcon = document.getElementById('menu-icon');

        function openMenu() {
            menu.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-label', 'Tutup menu navigasi');
            menuIcon.classList.replace('fa-bars', 'fa-xmark');
        }
        function closeMenu() {
            menu.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('aria-label', 'Buka menu navigasi');
            menuIcon.classList.replace('fa-xmark', 'fa-bars');
        }

        if (btn && menu) {
            btn.addEventListener('click', () => menu.classList.contains('hidden') ? openMenu() : closeMenu());
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeMenu);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu && !menu.classList.contains('hidden')) closeMenu();
        });

        /* ============ "Lainnya" dropdown (mobile) ============ */
        const lainnyaToggle = document.querySelector('[data-mobile-lainnya-toggle]');
        const lainnyaPanel  = document.getElementById('mobile-lainnya');
        if (lainnyaToggle && lainnyaPanel) {
            const chevron = lainnyaToggle.querySelector('i');
            lainnyaToggle.addEventListener('click', () => {
                const open = !lainnyaPanel.classList.contains('hidden');
                lainnyaPanel.classList.toggle('hidden', open);
                lainnyaToggle.setAttribute('aria-expanded', open ? 'false' : 'true');
                if (chevron) chevron.classList.toggle('rotate-180', !open);
            });
        }

        /* ============ "Lainnya" dropdown (desktop, ≥ xl) — click-to-toggle ============
           Sebelumnya hover-only (group-hover), jadi klik (mouse biasa, touch, keyboard)
           nggak buka apa-apa. Sekarang toggle via klik + tutup otomatis saat klik di luar
           atau tekan Escape. */
        const lainnyaBtn  = document.getElementById('lainnya-toggle');
        const lainnyaMenu = document.getElementById('lainnya-menu');
        if (lainnyaBtn && lainnyaMenu) {
            const openLainnya  = () => { lainnyaMenu.classList.add('is-open');    lainnyaBtn.setAttribute('aria-expanded', 'true'); };
            const closeLainnya = () => { lainnyaMenu.classList.remove('is-open'); lainnyaBtn.setAttribute('aria-expanded', 'false'); };
            lainnyaBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                lainnyaBtn.getAttribute('aria-expanded') === 'true' ? closeLainnya() : openLainnya();
            });
            document.addEventListener('click', (e) => {
                if (lainnyaBtn.getAttribute('aria-expanded') === 'true' &&
                    !lainnyaMenu.contains(e.target) && !lainnyaBtn.contains(e.target)) {
                    closeLainnya();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && lainnyaBtn.getAttribute('aria-expanded') === 'true') closeLainnya();
            });
        }
    });
</script>
