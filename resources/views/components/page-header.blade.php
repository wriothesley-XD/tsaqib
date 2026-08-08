{{--
    resources/views/components/page-header.blade.php
    ==================================================
    Header judul halaman yang REUSABLE — dipakai di semua halaman publik biar
    eyebrow + judul + deskripsi-nya konsisten. Pakai: <x-page-header ... />

    Props:
      title        (string, boleh HTML)  → H1. Contoh: "Perpustakaan Digital <span class='text-[var(--gold)]'>PAI</span>"
      subtitle     (string, boleh HTML)  → paragraf deskripsi di bawah judul
      eyebrow      (string)              → teks pill kecil di atas judul
      eyebrowIcon  (string)              → class Font Awesome, mis. "fa-solid fa-book-open"
      eyebrowClass (string)              → override class pill (default: eyebrow-pill hijau).
                                           Kirim class merah untuk state error/ditutup, dsb.
      align        (string)              → "center" (default) | "left"

    Slot:
      extra                              → konten tambahan DI BAWAH subtitle (search bar, tombol CTA, dsb).
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'eyebrow' => null,
    'eyebrowIcon' => null,
    'eyebrowClass' => 'eyebrow-pill eyebrow-pill-green',
    'align' => 'center',
])

<div class="@if($align === 'center') text-center max-w-3xl mx-auto @else max-w-3xl @endif">
    @if($eyebrow)
        <span class="{{ $eyebrowClass }}">
            @if($eyebrowIcon)<i class="{{ $eyebrowIcon }} text-[10px]"></i>@endif
            {{ $eyebrow }}
        </span>
    @endif

    @if($title)
        <h1 class="font-display font-extrabold text-[var(--cream)] tracking-tight leading-tight text-3xl sm:text-4xl mt-3">
            {!! $title !!}
        </h1>
    @endif

    @if($subtitle)
        <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2 @if($align === 'center') max-w-2xl mx-auto @endif">
            {!! $subtitle !!}
        </p>
    @endif

    @isset($extra)
        <div class="mt-6">
            {{ $extra }}
        </div>
    @endisset
</div>
