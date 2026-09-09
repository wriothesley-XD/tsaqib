{{--
    resources/views/partials/site-footer.blade.php
    ==================================================
    Footer global TSAQIB: hak cipta, barisan logo instansi pendukung,
    serta kontrol interaktif micro-UX (SFX audio toggle, shortcuts, command palette).
--}}
<footer class="relative z-10 border-t border-white/10 bg-[#0A1F13]/95 mt-auto">
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-6">

        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            {{-- Copyright & Institusi --}}
            <div class="text-center lg:text-left space-y-1">
                <p class="text-[var(--cream)] text-xs sm:text-sm font-display font-bold tracking-tight">
                    TSAQIB &middot; Forum Studi Islam SMAN 1 Bukittinggi
                </p>
                <p class="text-white/50 text-[11px]">
                    &copy; {{ date('Y') }} Hak Cipta Dilindungi. Membina Karakter &amp; Khazanah Keilmuan Rabbani.
                </p>
            </div>

            {{-- Barisan Logo Instansi --}}
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 bg-white/[0.04] border border-white/10 rounded-2xl px-5 py-3 sm:px-6 sm:py-3.5 shadow-sm">
                <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Kementerian Agama" title="Kementerian Agama" class="h-8 w-8 sm:h-9 sm:w-9 object-contain opacity-85 hover:opacity-100 transition-opacity" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Tut Wuri Handayani" title="Tut Wuri Handayani" class="h-8 w-8 sm:h-9 sm:w-9 object-contain opacity-85 hover:opacity-100 transition-opacity" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Pemerintah Provinsi Sumatera Barat" title="Pemerintah Provinsi Sumatera Barat" class="h-8 w-8 sm:h-9 sm:w-9 object-contain opacity-85 hover:opacity-100 transition-opacity" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="SMAN 1 Bukittinggi" title="SMAN 1 Bukittinggi" class="h-8 w-8 sm:h-9 sm:w-9 object-contain opacity-85 hover:opacity-100 transition-opacity" loading="lazy" onerror="this.remove()">
                <img src="{{ asset('assets/logo-instansi/fsi.webp') }}" alt="Forum Studi Islam" title="Forum Studi Islam" class="h-8 w-8 sm:h-9 sm:w-9 object-contain opacity-85 hover:opacity-100 transition-opacity" loading="lazy" onerror="this.remove()">

                {{-- Liivo badge --}}
                <div title="Liivo"
                     class="flex items-center justify-center h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-black/80 border border-white/10"
                     aria-label="Liivo">
                    <img src="{{ asset('assets/logo-instansi/Liivo.png') }}" alt="Liivo" title="Liivo" class="h-full w-full object-contain p-1 opacity-90" loading="lazy" onerror="this.remove()">
                </div>
            </div>
        </div>

        {{-- Interactive Micro-UX Toolbar (Competition Delight Feature) --}}
        <div class="pt-4 border-t border-white/5 flex flex-wrap items-center justify-between gap-3 text-[11px] text-white/45">
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="inline-flex items-center gap-1.5 text-white/40">
                    <i class="fa-solid fa-code text-[10px] text-[var(--gold)]"></i>
                    <span>Ekosistem PAI Terintegrasi</span>
                </span>
                <span class="text-white/20">&bull;</span>
                {{-- SFX Audio Toggle Button --}}
                <button type="button" data-sfx-toggle
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 hover:border-[var(--gold)]/40 text-white/60 hover:text-white transition cursor-pointer"
                        title="Aktifkan/Nonaktifkan Audio Haptic Mikro (Web Audio API)">
                    <i class="fa-solid fa-volume-xmark text-white/40 sfx-icon text-[10px]"></i>
                    <span class="sfx-status">SFX Hening</span>
                </button>
                <span class="text-white/20">&bull;</span>
                {{-- Cinematic Intro Replay Trigger --}}
                <a href="{{ route('landing') }}?intro=1"
                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 hover:border-[var(--gold)]/40 text-white/60 hover:text-white transition cursor-pointer"
                   title="Tonton Kembali Pengantar Sinematik TSAQIB">
                    <i class="fa-solid fa-film text-[10px] text-[var(--gold)]"></i>
                    <span>Sinematik</span>
                </a>
            </div>
        </div>

    </div>
</footer>

{{-- Global Interactive Modals, Toast Container & Daily Notification --}}
@include('components.command-palette')
@include('components.shortcuts-modal')
@include('components.toast')
@include('components.daily-notification')

