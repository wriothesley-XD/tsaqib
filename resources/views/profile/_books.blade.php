{{--
    resources/views/profile/_books.blade.php
    Tab "Books" (desktop) & "Book Collection" (mobile): buku dari koleksi user
    (book_user, type=collection) dikelompokkan per kategori. Kategori kosong
    tidak ditampilkan (kita hanya iterasi grup yang ada). Dipakai untuk profil
    sendiri (owner) — $booksGrouped sudah kosel untuk non-owner.

    $booksGrouped : Collection [ "Category" => [ ['title','author','link','cover'], ... ], ... ]
--}}
@php($groups = $booksGrouped ?? collect())

@if($groups->isNotEmpty())
    @foreach($groups as $category => $books)
        @php($n = count($books))
        <div class="mb-6 last:mb-0">
            <p class="text-[10px] font-extrabold tracking-[.09em] uppercase text-white/40 mb-2.5">
                {{ strtoupper($category) }} · {{ $n }} buku
            </p>
            <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($books as $b)
                    <a href="{{ $b['link'] }}" class="block group" target="_blank" rel="noopener">
                        <div class="pr-cover">
                            @if(!empty($b['cover']))
                                <img src="{{ $b['cover'] }}" alt="{{ $b['title'] }}" loading="lazy">
                            @endif
                            <span class="pr-cover-badge"><i class="fa-solid fa-book"></i></span>
                        </div>
                        <h4 class="font-bold text-xs text-[var(--cream)] truncate mt-1.5 group-hover:text-[var(--gold)] transition">{{ $b['title'] }}</h4>
                        <p class="text-[10px] text-white/45 truncate">{{ $b['author'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="pr-empty"><i class="fa-regular fa-book"></i><p>Belum ada buku di koleksi Anda.</p></div>
@endif
