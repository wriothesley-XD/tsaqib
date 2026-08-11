@extends('layouts.master')

@php($pageTitle = ($news->title ?? 'Berita') . ' - TSAQIB SMAN 1 Bukittinggi')

@push('styles')
<style>
    /* Wadah artikel: kartu PADAT (solid) bertema hijau gelap — bukan kaca
       transparan — supaya isi berita terasa grounded, tidak melayang di atas
       pola girih body. */
    .news-article-card{
        background:linear-gradient(180deg, #10302a 0%, #0b201c 100%);
        border:1px solid rgba(247,245,239,.12);
        border-radius:1.25rem;
        box-shadow:0 24px 60px -34px rgba(0,0,0,.75);
    }

    /* Isi berita: paragraf rapi, dapat di-read. Konten dari textarea (plain text),
       jadi kita pecah per baris kosong menjadi <p>. */
    .news-body p{ margin-bottom:1rem; line-height:1.75; }
    .news-body p:last-child{ margin-bottom:0; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        {{-- Back link --}}
        <a href="{{ route('info') }}" class="inline-flex items-center gap-1.5 text-xs text-white/55 hover:text-[var(--gold)] font-semibold mb-6 transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Semua Berita
        </a>

        <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-10 xl:gap-14">

            {{-- ===================== ARTIKEL ===================== --}}
            <article class="news-article-card p-6 sm:p-8 min-w-0">

                {{-- Meta --}}
                <span class="eyebrow-pill eyebrow-pill-green">
                    <i class="fa-regular fa-calendar text-[10px]"></i>
                    {{ $news->published_at?->format('d F Y') }}
                </span>
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[var(--cream)] tracking-tight leading-tight mt-4">
                    {{ $news->title }}
                </h1>
                @if($news->excerpt)
                    <p class="text-white/55 text-sm sm:text-base mt-4 leading-relaxed">{{ $news->excerpt }}</p>
                @endif

                {{-- Author (avatar asli via <x-community-avatar>; fallback default) --}}
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-white/10">
                    <x-community-avatar :user="$news->user" size="sm" />
                    <div class="leading-tight">
                        <p class="text-xs font-bold text-[var(--cream)]">{{ $news->user?->name ?? 'Redaksi TSAQIB' }}</p>
                        <p class="text-[10px] text-white/45">{{ $news->published_at?->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                {{-- Thumbnail --}}
                @if($news->thumbnail)
                    <div class="mt-6 rounded-2xl overflow-hidden border border-white/10">
                        <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="w-full max-h-[460px] object-cover">
                    </div>
                @endif

                {{-- Isi berita (paragraf dari plain-text content, dipecah di controller) --}}
                <div class="news-body text-white/75 text-sm sm:text-[15px] mt-6">
                    @foreach($paragraphs as $p)
                        <p>{!! nl2br(e($p)) !!}</p>
                    @endforeach
                </div>

            </article>

            {{-- ===================== SIDEBAR: berita terbaru ===================== --}}
            <aside class="hidden lg:block">
                <div class="sticky top-24">
                    <h3 class="font-display font-bold text-[var(--gold)] text-xs uppercase tracking-wider mb-4">Berita Terbaru</h3>
                    <div class="space-y-3">
                        @foreach($recent as $r)
                            <a href="{{ route('berita.show', $r->slug) }}" class="tsaqib-card p-3 flex gap-3 group">
                                <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 bg-[#01795F]/20">
                                    @if($r->thumbnail)
                                        <img src="{{ asset('storage/' . $r->thumbnail) }}" alt="{{ $r->title }}" class="w-full h-full object-cover" onerror="this.remove()">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-white/25"><i class="fa-solid fa-newspaper"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] text-white/40">{{ $r->published_at?->format('d M Y') }}</p>
                                    <p class="text-xs font-semibold text-[var(--cream)] line-clamp-3 group-hover:text-[var(--gold)] transition-colors leading-snug">{{ $r->title }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>

        {{-- Mobile recent list (di bawah artikel) --}}
        @if($recent->isNotEmpty())
            <div class="lg:hidden mt-10 pt-8 border-t border-white/10">
                <h3 class="font-display font-bold text-[var(--gold)] text-xs uppercase tracking-wider mb-4">Berita Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($recent as $r)
                        <a href="{{ route('berita.show', $r->slug) }}" class="tsaqib-card p-3 flex gap-3">
                            <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0 bg-[#01795F]/20">
                                @if($r->thumbnail)
                                    <img src="{{ asset('storage/' . $r->thumbnail) }}" alt="{{ $r->title }}" class="w-full h-full object-cover" onerror="this.remove()">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/25"><i class="fa-solid fa-newspaper"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-white/40">{{ $r->published_at?->format('d M Y') }}</p>
                                <p class="text-xs font-semibold text-[var(--cream)] line-clamp-2 leading-snug">{{ $r->title }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
