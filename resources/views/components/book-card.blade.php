{{--
    resources/views/components/book-card.blade.php
    ==================================================
    Kartu buka reusable untuk Perpustakaan. Lebar dikendalikan parent
    (carousel: w-40 shrink-0; grid: w-full). Cover selalu rasio 2:3.

    Props:
      book          -> model Book
      collectionIds -> array id buku yang sudah ada di "My Collection" user
      savedIds      -> array id buku yang sudah ada di "Saved" user

    Aksi Read PDF / Download memakai route yang SAMA dengan versi lama:
    asset('storage/'.$book->pdf_path) — dibuka new tab / atribut download.
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

<div class="book-card group" data-book-id="{{ $book->id }}">

    {{-- Cover rasio 2:3 --}}
    <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-[#11161f] border border-[rgba(201,169,77,.14)] transition duration-200 group-hover:border-[#e8cd85]">

        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $book->title }}"
                 loading="lazy"
                 class="w-full h-full object-cover transition duration-300 group-hover:scale-[1.04]">
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center p-3 text-center bg-[#11161f]">
                <div class="w-11 h-11 rounded-xl bg-[rgba(201,169,77,.1)] border border-[rgba(201,169,77,.2)] text-[#c9a94d] flex items-center justify-center mb-2">
                    <i class="fa-solid fa-file-pdf text-lg"></i>
                </div>
                <span class="text-[9px] font-bold text-[#e8cd85] uppercase tracking-wider">Modul Digital</span>
            </div>
        @endif

        {{-- Tombol bookmark (hanya login) — state awal dari server, lalu di-toggle JS --}}
        @auth
            <div class="absolute top-2 right-2 flex gap-1.5">
                <button type="button"
                        class="bookmark-btn is-collection w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ $inCollection ? 'is-on' : 'is-off' }}"
                        data-toggle="collection" data-book-id="{{ $book->id }}"
                        aria-label="Tambah ke Koleksi" title="Koleksi Saya">
                    <i class="fa-solid fa-bookmark"></i>
                </button>
                <button type="button"
                        class="bookmark-btn is-saved w-8 h-8 rounded-full backdrop-blur-sm flex items-center justify-center text-xs transition {{ $inSaved ? 'is-on' : 'is-off' }}"
                        data-toggle="saved" data-book-id="{{ $book->id }}"
                        aria-label="Simpan buku" title="Tersimpan">
                    <i class="fa-solid fa-heart"></i>
                </button>
            </div>
        @endauth

        {{-- Overlay Read / Download — hover-reveal di desktop (perangkat dg hover),
             SELALU tampil di touchscreen via [@media(hover:none)] (tanpa itu tombol
             tak pernah terungkap di mobile karena tidak ada :hover). --}}
        @if($pdfUrl)
            <div class="absolute inset-x-0 bottom-0 p-2.5 bg-gradient-to-t from-black/90 via-black/55 to-transparent flex items-center gap-2 opacity-0 translate-y-2 pointer-events-none transition duration-200 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto [@media(hover:none)]:opacity-100 [@media(hover:none)]:translate-y-0 [@media(hover:none)]:pointer-events-auto">
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener"
                   class="flex-1 py-1.5 rounded-lg border border-[rgba(201,169,77,.4)] bg-[rgba(201,169,77,.1)] hover:bg-[rgba(201,169,77,.2)] text-[#e8cd85] text-center font-semibold text-[11px] flex items-center justify-center gap-1.5 transition">
                    <i class="fa-solid fa-eye text-[10px]"></i> Baca PDF
                </a>
                <a href="{{ $pdfUrl }}" download
                   class="w-[30px] h-[30px] shrink-0 rounded-lg bg-[rgba(245,245,244,.08)] hover:bg-[rgba(245,245,244,.16)] border border-[rgba(201,169,77,.2)] text-stone-200 flex items-center justify-center transition"
                   title="Unduh File" aria-label="Unduh PDF">
                    <i class="fa-solid fa-download text-[11px]"></i>
                </a>
            </div>
        @else
            <div class="absolute inset-x-0 bottom-0 p-2.5 bg-gradient-to-t from-black/85 to-transparent opacity-0 group-hover:opacity-100 [@media(hover:none)]:opacity-100 transition">
                <span class="block w-full py-1.5 rounded-lg bg-white/10 text-white/55 text-center text-[10px] font-semibold">
                    PDF Belum Tersedia
                </span>
            </div>
        @endif
    </div>

    {{-- Meta --}}
    <div class="mt-2">
        <span class="text-[9px] font-bold text-[#c9a94d] uppercase tracking-wider">
            {{ $book->category ?? 'Modul PAI' }}
        </span>
        <h3 class="font-bold text-[13px] leading-snug text-stone-100 group-hover:text-[#e8cd85] transition line-clamp-2 mt-0.5">
            {{ $book->title }}
        </h3>
        <p class="text-[11px] text-stone-500 mt-0.5 line-clamp-1">{{ $book->author ?? 'Tim PAI SMAN 1 Bukittinggi' }}</p>
    </div>
</div>
