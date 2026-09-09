@php
    $variant = $variant ?? 'desktop';
    $isMobile = $variant === 'mobile';

    $socials = [
        [
            'name'        => 'Instagram',
            'url'         => 'https://www.instagram.com/fsi.smansa_landbouw?igsh=MXVzMzd5Nms0eDZpNQ==',
            'icon'        => 'fa-brands fa-instagram',
            'borderHover' => 'hover:border-pink-500/50',
            'shadowHover' => 'hover:shadow-[0_0_14px_rgba(225,48,108,0.35)]',
            'iconHover'   => 'group-hover:text-[var(--gold)]',
            'ambientBg'   => 'group-hover:bg-pink-500/10',
        ],
        [
            'name'        => 'Facebook',
            'url'         => 'https://www.facebook.com/share/1BJMFJvK5k/',
            'icon'        => 'fa-brands fa-facebook',
            'borderHover' => 'hover:border-blue-500/50',
            'shadowHover' => 'hover:shadow-[0_0_14px_rgba(24,119,242,0.35)]',
            'iconHover'   => 'group-hover:text-[var(--gold)]',
            'ambientBg'   => 'group-hover:bg-blue-600/10',
        ],
        [
            'name'        => 'YouTube',
            'url'         => 'https://ytfsi.carrd.co',
            'icon'        => 'fa-brands fa-youtube',
            'borderHover' => 'hover:border-red-500/50',
            'shadowHover' => 'hover:shadow-[0_0_14px_rgba(255,0,0,0.35)]',
            'iconHover'   => 'group-hover:text-[var(--gold)]',
            'ambientBg'   => 'group-hover:bg-red-600/10',
        ],
    ];

    $btnSize  = $isMobile ? 'w-9 h-9' : 'w-7.5 h-7.5 2xl:w-8 2xl:h-8';
    $iconSize = $isMobile ? 'text-base' : 'text-xs 2xl:text-sm';
    $gapSize  = $isMobile ? 'gap-2.5' : 'gap-1.5 2xl:gap-2';
@endphp

<div class="flex items-center {{ $gapSize }}" role="group" aria-label="Media Sosial FSI SMAN 1 Bukittinggi">
    @foreach($socials as $social)
        <div class="relative group">
            <a href="{{ $social['url'] }}"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ $social['name'] }} FSI SMAN 1 Bukittinggi"
               class="relative flex items-center justify-center {{ $btnSize }} rounded-xl bg-white/[0.04] hover:bg-white/[0.08] {{ $social['ambientBg'] }} border border-white/10 {{ $social['borderHover'] }} {{ $social['shadowHover'] }} text-white/60 hover:text-[var(--gold)] hover:-translate-y-0.5 active:scale-95 transition-all duration-300 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--gold)] cursor-pointer">
                <i class="{{ $social['icon'] }} {{ $iconSize }} {{ $social['iconHover'] }} transition-transform duration-300 group-hover:scale-115"></i>
            </a>

            {{-- Floating Tooltip (Desktop view) --}}
            @if(!$isMobile)
                <div class="pointer-events-none absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-200 z-50 whitespace-nowrap hidden xl:block">
                    <div class="relative px-2 py-0.5 rounded-md bg-[#0A1F13]/95 backdrop-blur-md border border-white/15 text-[10px] font-bold text-[var(--gold)] shadow-xl tracking-wider uppercase">
                        {{ $social['name'] }}
                        <span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-[#0A1F13] border-t border-l border-white/15 rotate-45" aria-hidden="true"></span>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
