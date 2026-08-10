@php
    $currentRoute = Route::currentRouteName() ?? '';
    $daftarKomunitasNav = \Illuminate\Support\Facades\Config::get('komunitas.daftar', []);

    $isKomunitasZone = str_contains($currentRoute, 'komunitas');

    // 5 link terpusat, tampil selalu (tanpa zone-hiding biar count & centering stabil).
    // Komunitas (dropdown) disisipkan di antara $navBefore & $navAfter -> posisi ke-4.
    $navBefore = [
        ['label' => 'Beranda',          'href' => route('landing'),          'active' => $currentRoute === 'landing'],
        ['label' => 'Laboratorium PAI', 'href' => route('laboratorium.pai'), 'active' => in_array($currentRoute, ['laboratorium.pai', 'labor'])],
        ['label' => 'Perpustakaan',     'href' => route('perpustakaan'),     'active' => $currentRoute === 'perpustakaan'],
    ];
    $navAfter = [
        ['label' => 'Open Recruitment', 'href' => route('open.recruitment'), 'active' => str_contains($currentRoute, 'open.recruitment')],
    ];

    // Palet minimalis: tenang saat non-aktif, emas saat aktif. Indikator aktif = garis bawah emas.
    $link      = fn ($active) => $active ? 'text-[var(--gold)]' : 'text-white/55 hover:text-white';
    $underline = fn ($active) => 'absolute left-0 bottom-0 h-[2px] bg-[var(--gold)] transition-all duration-300 '
        . ($active ? 'w-full' : 'w-0 group-hover:w-full');
@endphp

{{-- Token tema gelap (.brand-mark, .cta-primary, CSS vars, font-display/label) di-supply
    tiap halaman via @include('partials.theme-head'). Navbar ini sticky (memesan tempat
    sendiri) -> konten di bawahnya nggak ketimpa, nggak butuh padding-top. --}}

<header class="sticky top-0 z-[70] bg-[#10140F] border-b border-white/10 text-[var(--cream)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 xl:h-20 gap-4">

            {{-- ===== BRAND (kiri) ===== --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group shrink-0" title="Kembali ke Beranda">
                <div class="brand-mark brand-mark-sm group-hover:scale-105 transition-transform">TS</div>
                <span class="leading-none hidden sm:block">
                    <span class="font-display font-extrabold text-base tracking-tight text-[var(--cream)] block">TSAQIB</span>
                    <span class="text-[9px] text-[var(--gold)] font-bold tracking-[0.14em] uppercase block mt-1">FSI SMAN 1 Bukittinggi</span>
                </span>
            </a>

            {{-- ===== NAV TENGAH (≥ xl) — terpusat via flex-1 justify-center, BUKAN absolute ===== --}}
            <nav class="hidden xl:flex flex-1 justify-center items-center gap-8 font-label whitespace-nowrap">

                @foreach($navBefore as $item)
                    <a href="{{ $item['href'] }}"
                       class="group relative pb-1 text-xs uppercase tracking-[0.16em] font-semibold transition-colors duration-200 {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                    </a>
                @endforeach

                {{-- Komunitas — link langsung ke feed gabungan semua komunitas (bukan dropdown) --}}
                <a href="{{ route('komunitas', 'semua') }}"
                   class="group relative pb-1 text-xs uppercase tracking-[0.16em] font-semibold transition-colors duration-200 {{ $link($isKomunitasZone) }}">
                    Komunitas
                    <span class="{{ $underline($isKomunitasZone) }}"></span>
                </a>

                @foreach($navAfter as $item)
                    <a href="{{ $item['href'] }}"
                       class="group relative pb-1 text-xs uppercase tracking-[0.16em] font-semibold transition-colors duration-200 {{ $link($item['active']) }}">
                        {{ $item['label'] }}
                        <span class="{{ $underline($item['active']) }}"></span>
                    </a>
                @endforeach
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

        @foreach($navBefore as $item)
            <a href="{{ $item['href'] }}"
               class="block py-3 text-sm font-medium tracking-wide transition-colors duration-200 {{ $item['active'] ? 'text-[var(--gold)]' : 'text-white/80 hover:text-white' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        {{-- Komunitas — link langsung ke feed gabungan semua komunitas --}}
        <a href="{{ route('komunitas', 'semua') }}"
           class="block py-3 text-sm font-medium tracking-wide transition-colors duration-200 {{ $isKomunitasZone ? 'text-[var(--gold)]' : 'text-white/80 hover:text-white' }}">
            Komunitas
        </a>

        @foreach($navAfter as $item)
            <a href="{{ $item['href'] }}"
               class="block py-3 text-sm font-medium tracking-wide transition-colors duration-200 {{ $item['active'] ? 'text-[var(--gold)]' : 'text-white/80 hover:text-white' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        {{-- Area Auth (mobile) --}}
        <div class="pt-3 mt-2 border-t border-white/5 space-y-1">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="block py-3 text-sm font-semibold text-amber-300/90 hover:text-amber-200 transition-colors duration-200">
                        Admin Panel
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 py-3 text-sm font-medium text-white/85 hover:text-white transition-colors duration-200">
                    <x-community-avatar :user="Auth::user()" size="xs" />
                    <span>Profil Saya</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="cta-primary flex justify-center items-center w-full px-4 py-3 rounded-full text-sm font-bold text-white">
                    Masuk
                </a>
            @endauth
        </div>
    </div>

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
    });
</script>
