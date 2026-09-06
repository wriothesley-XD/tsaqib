{{--
    resources/views/components/book-card.blade.php
    ==================================================
    Kartu buku terpadu untuk Perpustakaan Digital TSAQIB.
--}}
@props([
    'book',
    'collectionIds' => [],
    'savedIds' => [],
])

@php
    $inCollection = in_array($book->id, $collectionIds, true);
    $inSaved = in_array($book->id, $savedIds, true);
    $coverUrl = $book->cover_image ? asset('storage/' . $book->cover_image) : null;
    $pdfUrl = $book->pdf_path ? asset('storage/' . $book->pdf_path) : null;
@endphp

<div class="book-card group book-3d-wrap" data-book-id="{{ $book->id }}">

    {{-- Cover rasio 2:3 dengan 3D Book Spine Perspective --}}
    <div class="book-3d relative aspect-[2/3] rounded-xl overflow-hidden bg-[#143520] border border-white/10 transition duration-300 group-hover:border-[var(--gold)]/60">

        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $book->title }}"
                 loading="lazy"
                 class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center p-3 text-center bg-gradient-to-br from-[#1C442B] to-[#0D2818]">
                <div class="w-11 h-11 rounded-xl bg-[var(--gold)]/10 border border-[var(--gold)]/30 text-[var(--gold)] flex items-center justify-center mb-2">
                    <i class="fa-solid fa-book-bookmark text-lg"></i>
                </div>
                <span class="text-[9px] font-bold text-[var(--gold)] uppercase tracking-wider">Modul Digital</span>
            </div>
        @endif

        {{-- Tombol bookmark (hanya login) --}}
        @auth
            <div class="absolute top-2 right-2 flex gap-1.5 z-10">
                <button type="button"
                        class="bookmark-btn is-collection w-7 h-7 rounded-full backdrop-blur-md flex items-center justify-center text-[10px] transition {{ $inCollection ? 'is-on bg-[var(--gold)] text-[var(--green-s0)]' : 'is-off bg-black/60 text-white/80 hover:bg-black/80' }}"
                        data-toggle="collection" data-book-id="{{ $book->id }}"
                        aria-label="Tambah ke Koleksi" title="Koleksi Saya">
                    <i class="fa-solid fa-bookmark"></i>
                </button>
                <button type="button"
                        class="bookmark-btn is-saved w-7 h-7 rounded-full backdrop-blur-md flex items-center justify-center text-[10px] transition {{ $inSaved ? 'is-on bg-[#01795F] text-white' : 'is-off bg-black/60 text-white/80 hover:bg-black/80' }}"
                        data-toggle="saved" data-book-id="{{ $book->id }}"
                        aria-label="Simpan buku" title="Tersimpan">
                    <i class="fa-solid fa-heart"></i>
                </button>
            </div>
        @endauth

        {{-- Overlay Read / Download --}}
        @if($pdfUrl)
            <div class="absolute inset-x-0 bottom-0 p-2.5 bg-gradient-to-t from-black/90 via-black/60 to-transparent flex items-center gap-1.5 opacity-0 translate-y-2 pointer-events-none transition duration-200 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto [@media(hover:none)]:opacity-100 [@media(hover:none)]:translate-y-0 [@media(hover:none)]:pointer-events-auto">
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener"
                   class="flex-1 py-1.5 rounded-lg bg-[var(--gold)] hover:brightness-110 text-[var(--green-s0)] text-center font-bold text-[10px] flex items-center justify-center gap-1 transition shadow">
                    <i class="fa-solid fa-book-open text-[9px]"></i> Baca
                </a>
                <a href="{{ $pdfUrl }}" download
                   class="w-7 h-7 shrink-0 rounded-lg bg-white/15 hover:bg-white/25 text-[var(--cream)] flex items-center justify-center transition"
                   title="Unduh File" aria-label="Unduh PDF">
                    <i class="fa-solid fa-download text-[10px]"></i>
                </a>
            </div>
        @else
            <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 [@media(hover:none)]:opacity-100 transition">
                <span class="block w-full py-1 rounded bg-white/10 text-white/60 text-center text-[9px] font-semibold">
                    PDF Menyusul
                </span>
            </div>
        @endif
    </div>

    {{-- Meta Teks --}}
    <div class="mt-2 space-y-0.5">
        <span class="text-[9px] font-bold text-[var(--gold)] uppercase tracking-wider block truncate">
            {{ $book->category ?? 'Modul PAI' }}
        </span>
        <h3 class="font-bold text-xs sm:text-[13px] leading-snug text-[var(--cream)] group-hover:text-[var(--gold)] transition line-clamp-2">
            {{ $book->title }}
        </h3>
        <p class="text-[10px] text-white/50 truncate">{{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
    </div>
</div>
