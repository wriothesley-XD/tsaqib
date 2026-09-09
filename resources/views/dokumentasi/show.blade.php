@extends('layouts.master')

@php($pageTitle = ($doc->title ?? 'Dokumentasi') . ' - TSAQIB SMAN 1 Bukittinggi')

@push('styles')
<style>
    /* Kartu detail — pola sama dengan kartu artikel berita (solid hijau gelap). */
    .doc-article-card{
        background:linear-gradient(180deg, #10302a 0%, #0b201c 100%);
        border:1px solid rgba(247,245,239,.12);
        border-radius:1.25rem;
        box-shadow:0 24px 60px -34px rgba(0,0,0,.75);
    }

    /* Masonry galeri: CSS columns (native, tanpa JS). Item break-inside agar
       tidak terpotong antar kolom. */
    .doc-masonry{ columns:2; column-gap:.75rem; }
    @media (min-width:640px){ .doc-masonry{ columns:3; column-gap:1rem; } }
    @media (min-width:1024px){ .doc-masonry{ columns:4; column-gap:1rem; } }
    .doc-masonry figure{ break-inside:avoid; margin-bottom:.75rem; }

    /* ===== Lightbox ===== */
    #lightbox{
        opacity:0; pointer-events:none;
        transition:opacity .2s ease;
    }
    #lightbox.is-open{ opacity:1; pointer-events:auto; }
    #lightbox img{ max-height:82vh; max-width:92vw; }
    /* Slide antar foto: arah dikontrol --lb-dir (+1 next, -1 prev). */
    @keyframes lbSlide{ from{ opacity:0; transform:translateX(calc(16px * var(--lb-dir,1))); } }
    #lightbox img.lb-slide{ animation:lbSlide .2s ease; }
    @media (prefers-reduced-motion: reduce){
        #lightbox{ transition:none; }
        #lightbox img.lb-slide{ animation:none; }
    }
</style>
@endpush

@section('content')
<main class="flex-1 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        {{-- Back link --}}
        <a href="{{ route('info') }}#dokumentasi" class="inline-flex items-center gap-1.5 text-xs text-white/55 hover:text-[var(--gold)] font-semibold mb-6 transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Semua Dokumentasi
        </a>

        {{-- ===== Header kegiatan ===== --}}
        <article class="doc-article-card p-6 sm:p-8 mb-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="eyebrow-pill eyebrow-pill-green">
                    <i class="fa-regular fa-calendar text-[10px]"></i>
                    {{ $doc->event_date?->format('d F Y') ?? 'Dokumentasi Kegiatan' }}
                </span>
                @if($doc->category)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider {{ $doc->badgeClass() }}">
                        {{ $doc->category }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-white/55">
                    <i class="fa-solid fa-camera text-[9px]"></i>{{ $doc->photos->count() }} Foto
                </span>
                @if($doc->video_path)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-white/55">
                        <i class="fa-solid fa-circle-play text-[9px] text-[var(--gold)]"></i>Video
                    </span>
                @endif
            </div>

            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--cream)] tracking-tight leading-tight mt-4">
                {{ $doc->title }}
            </h1>

            @if($doc->description)
                <p class="text-white/55 text-sm sm:text-base mt-4 leading-relaxed whitespace-pre-line">{{ $doc->description }}</p>
            @endif
        </article>

        {{-- ===== Video rekaman kegiatan (opsional) =====
             controls + preload="metadata": tidak autoplay, suara hanya jika user play. --}}
        @if($doc->video_path)
            <video src="{{ asset('storage/' . $doc->video_path) }}" controls playsinline preload="metadata"
                   class="w-full aspect-video rounded-xl border border-white/10 bg-black shadow-[0_24px_60px_-34px_rgba(0,0,0,.75)] mb-8"></video>
        @endif

        {{-- ===== Galeri masonry ===== --}}
        @if($doc->photos->isNotEmpty())
            <div class="doc-masonry" data-doc-gallery>
                @foreach($doc->photos as $photo)
                    <figure>
                        <button type="button" data-lb-index="{{ $loop->index }}"
                                class="block w-full cursor-pointer rounded-xl overflow-hidden border border-white/10 hover:border-[rgba(201,166,107,.5)] transition group"
                                aria-label="Perbesar foto {{ $loop->iteration }}">
                            <img src="{{ asset('storage/' . $photo->image_path) }}"
                                 alt="{{ $photo->caption ?? $doc->title }}"
                                 loading="lazy"
                                 class="w-full h-auto transition-transform duration-500 group-hover:scale-[1.03]">
                        </button>
                        @if($photo->caption)
                            <figcaption class="text-[10px] text-white/40 mt-1.5 px-0.5">{{ $photo->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @elseif(!$doc->video_path)
            <div class="tsaqib-card-flat p-12 text-center">
                <i class="fa-solid fa-images text-3xl text-white/15 block mb-3"></i>
                <p class="text-white/45 text-xs">Belum ada foto pada dokumentasi ini.</p>
            </div>
        @endif
    </div>
</main>

{{-- ===== Lightbox (fullscreen, panah navigasi, close) ===== --}}
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center" role="dialog" aria-modal="true" aria-label="Galeri foto {{ $doc->title }}">
    <button type="button" data-lb-close class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white text-lg cursor-pointer transition" aria-label="Tutup galeri">
        <i class="fa-solid fa-xmark"></i>
    </button>

    @if($doc->photos->count() > 1)
        <button type="button" data-lb-prev class="absolute left-3 sm:left-6 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white cursor-pointer transition" aria-label="Foto sebelumnya">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" data-lb-next class="absolute right-3 sm:right-6 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white cursor-pointer transition" aria-label="Foto berikutnya">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    @endif

    <figure class="flex flex-col items-center gap-3">
        <img src="" alt="" class="rounded-xl shadow-2xl object-contain">
        <figcaption class="text-xs text-white/60">
            <span data-lb-counter></span>
            @if($doc->photos->where('caption')->isNotEmpty())
                <span data-lb-caption class="block text-center text-white/45 mt-1"></span>
            @endif
        </figcaption>
    </figure>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var lb      = document.getElementById('lightbox');
    var gallery = document.querySelector('[data-doc-gallery]');
    if (!lb || !gallery) return;

    var img     = lb.querySelector('img');
    var counter = lb.querySelector('[data-lb-counter]');
    var capEl   = lb.querySelector('[data-lb-caption]');
    var photos  = Array.prototype.slice.call(gallery.querySelectorAll('[data-lb-index]')).map(function (btn) {
        var image = btn.querySelector('img');
        return { src: image.src, caption: image.alt };
    });
    var idx = 0;

    function show(i, dir) {
        idx = (i + photos.length) % photos.length;
        img.classList.remove('lb-slide');
        void img.offsetWidth; // reset animasi supaya replay tiap pindah foto
        img.style.setProperty('--lb-dir', dir || 1);
        img.src = photos[idx].src;
        img.alt = photos[idx].caption;
        img.classList.add('lb-slide');
        counter.textContent = (idx + 1) + ' / ' + photos.length;
        if (capEl) capEl.textContent = photos[idx].caption;
    }

    function open(i) {
        show(i);
        lb.classList.remove('hidden');
        requestAnimationFrame(function () { lb.classList.add('is-open'); });
        document.body.classList.add('overflow-hidden');
    }

    function close() {
        lb.classList.remove('is-open');
        document.body.classList.remove('overflow-hidden');
        setTimeout(function () { lb.classList.add('hidden'); }, 200); // tunggu fade-out
    }

    gallery.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-lb-index]');
        if (btn) open(parseInt(btn.dataset.lbIndex, 10));
    });

    lb.querySelector('[data-lb-close]').addEventListener('click', close);
    lb.addEventListener('click', function (e) { if (e.target === lb) close(); });

    var prev = lb.querySelector('[data-lb-prev]');
    var next = lb.querySelector('[data-lb-next]');
    if (prev) prev.addEventListener('click', function () { show(idx - 1, -1); });
    if (next) next.addEventListener('click', function () { show(idx + 1, 1); });

    document.addEventListener('keydown', function (e) {
        if (lb.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft' && prev) show(idx - 1, -1);
        else if (e.key === 'ArrowRight' && next) show(idx + 1, 1);
    });
})();
</script>
@endpush
