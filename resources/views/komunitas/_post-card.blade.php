{{--
    resources/views/komunitas/_post-card.blade.php
    Isi kartu postingan (header, body, media, action bar).
    Dipakai feed (article clickable) & detail (article biasa).
    Variabel: $post (eager-load user, media, votes & savedBy pivot scoped utk user
    login, dengan withCount comments/reposts). $showManage (bool) -> tampil menu kelola.
    $compact (bool) -> varian kartu feed ala Reddit: header ikon komunitas + nama +
    waktu + menu "..." (Edit/Hapus), judul besar, konten singkat (line-clamp-3),
    media grid maks 4 tile (sisanya overlay "+N"), dua tombol jempol terpisah.
    Halaman detail tetap memakai varian penuh (avatar + konten utuh + grid media).
--}}
@php($showManage = $showManage ?? false)
@php($compact = $compact ?? false)

{{-- $penulisLink = true bila user login & penulis post masih ada (bukan user terhapus).
     data-no-nav pada <a> mencegah klik ini memicu navigasi kartu feed ke halaman detail. --}}
@php($penulisLink = Auth::check() && $post->user)
@can('update', $post)
    @php($canManage = $showManage)
@else
    @php($canManage = false)
@endcan
@php($media = $post->media)
@php($mCount = $media->count())
@php($mCols = $mCount >= 4 ? 'cols-4' : 'cols-' . $mCount)
@php($mVisible = min($mCount, 4))

@if ($compact)
    {{-- Header kartu feed: ikon komunitas bulat + nama (tebal) + waktu; kanan: menu "..." --}}
    @php($komunitasKartu = $post->community_slug ? collect(config('komunitas.daftar'))->firstWhere('slug', $post->community_slug) : null)
    <div class="flex items-center gap-2 px-4 pt-3.5 sm:px-5">
        @if ($komunitasKartu)
            <img src="{{ asset($komunitasKartu['image']) }}" alt="" loading="lazy" onerror="this.remove()"
                 class="w-7 h-7 rounded-full object-cover shrink-0 bg-white/5">
        @else
            <span class="w-7 h-7 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-users text-[10px] text-white/40"></i>
            </span>
        @endif
        <a href="{{ route('komunitas', $post->community_slug ?? 'semua') }}" data-no-nav
           class="font-bold text-xs text-[var(--cream)] hover:text-[var(--gold)] transition truncate">
            {{ $komunitasKartu['nama'] ?? 'umum' }}
        </a>
        <span class="text-white/30 text-xs shrink-0" aria-hidden="true">•</span>
        <span class="text-[11px] text-white/60 font-medium shrink-0">
            {{ $post->user->name ?? 'Anggota TSAQIB' }}
        </span>
        @if ($post->user?->is_verified_student)
            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-[#01795F]/25 border border-[#01795F]/50 text-[#3fd6b0] text-[9px] font-bold shrink-0" title="Siswa Terverifikasi SMAN 1 Bukittinggi">
                <i class="fa-solid fa-circle-check text-[8px]"></i> Siswa SMAN 1
            </span>
        @endif
        <span class="text-white/30 text-xs shrink-0" aria-hidden="true">•</span>
        <span class="text-[11px] text-white/40 shrink-0">{{ $post->created_at->diffForHumans() }}</span>

        @if ($canManage)
            {{-- Menu "..." — Edit/Hapus dipindah ke dropdown (details + tutup saat klik di luar via JS). --}}
            <details class="k-menu ml-auto shrink-0" data-no-nav>
                <summary class="w-8 h-8 rounded-full flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 transition cursor-pointer"
                         aria-label="Opsi postingan" title="Opsi postingan">
                    <i class="fa-solid fa-ellipsis text-sm"></i>
                </summary>
                <div class="k-menu-panel">
                    <a href="{{ route('komunitas.post.edit', $post->id) }}"
                       class="k-menu-item text-white/75 hover:bg-white/10 hover:text-white">
                        <i class="fa-solid fa-pen w-3.5 text-center"></i> Edit
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                          data-confirm="Apakah Anda yakin ingin menghapus postingan ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="k-menu-item text-red-400 hover:bg-red-500/10">
                            <i class="fa-solid fa-trash w-3.5 text-center"></i> Hapus
                        </button>
                    </form>
                </div>
            </details>
        @else
            <span class="ml-auto" aria-hidden="true"></span>
        @endif
    </div>

    {{-- Judul (elemen paling menonjol) + cuplikan konten --}}
    <h3 class="font-bold text-lg sm:text-xl text-[var(--cream)] leading-snug mt-2 px-4 sm:px-5">{{ $post->title }}</h3>
    @if ($post->content)
        <p class="text-sm text-white/70 leading-relaxed line-clamp-3 mt-1 px-4 sm:px-5">{{ $post->content }}</p>
    @endif
@else
    {{-- Post Header (varian penuh): avatar + nama penulis + waktu --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-3">
            @if ($penulisLink)
                <a href="{{ route('profile.show', $post->user->id) }}" data-no-nav
                   class="shrink-0 transition-opacity hover:opacity-80"
                   aria-label="Lihat profil {{ $post->user->name ?? 'penulis' }}">
                    <x-community-avatar :user="$post->user" :slug="$post->community_slug" size="md" />
                </a>
            @else
                <div class="shrink-0"><x-community-avatar :user="$post->user" :slug="$post->community_slug" size="md" /></div>
            @endif
            <div>
                <h4 class="font-bold text-xs text-[var(--cream)] flex items-center gap-1.5 flex-wrap">
                    @if ($penulisLink)
                        <a href="{{ route('profile.show', $post->user->id) }}" data-no-nav
                           class="hover:underline decoration-[var(--gold)]/60 underline-offset-2">
                            {{ $post->user->name ?? 'Anggota TSAQIB' }}
                        </a>
                    @else
                        {{ $post->user->name ?? 'Anggota TSAQIB' }}
                    @endif
                    @if ($post->user?->is_verified_student)
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-[#01795F]/25 border border-[#01795F]/50 text-[#3fd6b0] text-[9px] font-bold" title="Siswa Terverifikasi SMAN 1 Bukittinggi">
                            <i class="fa-solid fa-circle-check text-[8px]"></i> Siswa SMAN 1
                        </span>
                    @endif
                </h4>
                <span class="text-[10px] text-white/40">
                    {{ $post->created_at->diffForHumans() }} •
                    <span class="font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
                </span>
            </div>
        </div>

        @if ($canManage)
            <div class="flex items-center space-x-2 shrink-0" data-no-nav>
                <a href="{{ route('komunitas.post.edit', $post->id) }}"
                   data-no-nav class="text-xs text-white/60 hover:text-white font-semibold px-2.5 py-1 rounded-lg bg-white/10">
                    <i class="fa-solid fa-pen mr-1"></i>Edit
                </a>
                <form data-no-nav action="{{ route('posts.destroy', $post->id) }}" method="POST"
                      data-confirm="Apakah Anda yakin ingin menghapus postingan ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-semibold px-2.5 py-1 rounded-lg bg-red-500/10">
                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Post Body (varian penuh): konten utuh --}}
    <h3 class="post-title font-bold text-base text-[var(--cream)] mb-2 leading-snug">{{ $post->title }}</h3>
    <p class="post-content text-xs text-white/75 leading-relaxed whitespace-pre-line mb-2">{{ $post->content }}</p>
@endif

{{-- Media Grid — dibagikan kedua varian. Maks 4 tile; bila lebih, tile ke-4
     dapat overlay gelap "+N" (.media-more) dan sisanya terlihat di lightbox.
     Compact: full-bleed (article tanpa padding). Penuh: inset dengan margin. --}}
@if ($mCount > 0)
    <div class="media-grid {{ $mCols }} {{ $compact ? 'mt-3' : 'my-3' }}"
         data-media="{{ json_encode($media->map(fn($m) => ['url' => $m->url, 'type' => $m->type])) }}">
        @foreach ($media->take($mVisible) as $mIndex => $mItem)
            @php($mExtra = $mCount > 4 && $mIndex === 3 ? $mCount - 4 : 0)
            <div class="media-tile {{ $mCount === 1 ? 'single' : '' }}" data-no-nav data-index="{{ $mIndex }}"
                 data-action="open-lightbox">
                @if ($mItem->isVideo())
                    <video src="{{ $mItem->url }}" preload="none" muted playsinline></video>
                    <div class="media-play"><i class="fa-solid fa-play"></i></div>
                @else
                    <img src="{{ $mItem->url }}" alt="{{ $post->title }}" loading="lazy">
                @endif
                @if ($mExtra)
                    <div class="media-more">+{{ $mExtra }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Action Bar: compact = kapsul vote (atas/skor bersih/bawah) + chip; penuh = pil jempol. --}}
@php($myVote = Auth::check() ? $post->votes->first()?->type : null)
@php($isSaved = Auth::check() ? $post->savedBy->isNotEmpty() : false)
<div class="flex items-center gap-2 flex-wrap {{ $compact ? 'px-4 sm:px-5 py-2.5 mt-3 border-t border-white/5' : 'mt-3 pt-3 border-t border-white/10' }}">
    @if ($compact)
        {{-- Dua tombol jempol terpisah (satu per tombol, gaya chip selaras komentar/share).
            Kontrak data-post-id/data-type/data-count sama dengan varian penuh -> handler
            vote AJAX yang ada update jumlah + is-up (hijau) / is-down (merah) tanpa ubahan. --}}
        <div class="flex items-center gap-1.5">
            <button type="button" data-no-nav data-post-id="{{ $post->id }}" data-type="up"
                    class="vote-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $myVote === 'up' ? 'is-up' : '' }}"
                    aria-label="Vote positif">
                <i class="fa-solid fa-thumbs-up"></i><span data-count>{{ $post->upvotes }}</span>
            </button>
            <button type="button" data-no-nav data-post-id="{{ $post->id }}" data-type="down"
                    class="vote-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $myVote === 'down' ? 'is-down' : '' }}"
                    aria-label="Vote negatif">
                <i class="fa-solid fa-thumbs-down"></i><span data-count>{{ $post->downvotes }}</span>
            </button>
        </div>
    @else
        <div class="inline-flex rounded-full border border-white/10 overflow-hidden">
            <button type="button" data-no-nav data-post-id="{{ $post->id }}" data-type="up"
                    class="vote-btn flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold {{ $myVote === 'up' ? 'is-up' : '' }}">
                <i class="fa-solid fa-thumbs-up"></i><span data-count>{{ $post->upvotes }}</span>
            </button>
            <button type="button" data-no-nav data-post-id="{{ $post->id }}" data-type="down"
                    class="vote-btn flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold {{ $myVote === 'down' ? 'is-down' : '' }}">
                <i class="fa-solid fa-thumbs-down"></i><span data-count>{{ $post->downvotes }}</span>
            </button>
        </div>
    @endif

    <span class="action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white/60">
        <i class="fa-regular fa-comment"></i><span>{{ $post->comments_count }}</span>
    </span>

    <button type="button" data-no-nav
            class="share-btn action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white/60"
            data-url="{{ route('komunitas.post.show', $post->id) }}">
        <i class="fa-solid fa-share-nodes"></i><span class="hidden sm:inline">Bagikan</span>
    </button>

    <button type="button" data-no-nav data-post-id="{{ $post->id }}"
            class="save-btn action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $isSaved ? 'is-active' : '' }}"
            title="{{ $isSaved ? 'Hapus dari Tersimpan' : 'Simpan ke Tersimpan' }}">
        <i class="fa-solid fa-bookmark"></i><span class="hidden sm:inline">{{ $isSaved ? 'Tersimpan' : 'Simpan' }}</span>
    </button>

    @auth
        <button type="button" data-no-nav data-rt="post" data-rid="{{ $post->id }}"
                class="report-btn action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white/60"
                title="Laporkan postingan ini">
            <i class="fa-solid fa-flag"></i>
        </button>
    @endauth

    @guest
        <span class="ml-1 text-[10px] text-white/30">Masuk untuk berinteraksi</span>
    @endguest
</div>
