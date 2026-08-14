{{--
    resources/views/komunitas/_post-card.blade.php
    Isi kartu postingan (header, body, media grid, action bar).
    Dipakai feed (article clickable) & detail (article biasa).
    Variabel: $post (eager-load user, media, votes & savedBy pivot scoped utk user
    login, dengan withCount comments/reposts). $showManage (bool) -> tampil tombol edit/hapus.
--}}
@php($showManage = $showManage ?? false)

{{-- Post Header --}}
{{-- $penulisLink = true bila user login & penulis post masih ada (bukan user terhapus).
     data-no-nav pada <a> mencegah klik ini memicu navigasi kartu feed ke halaman detail. --}}
@php($penulisLink = Auth::check() && $post->user)
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
            <h4 class="font-bold text-xs text-[var(--cream)]">
                @if ($penulisLink)
                    <a href="{{ route('profile.show', $post->user->id) }}" data-no-nav
                       class="hover:underline decoration-[var(--gold)]/60 underline-offset-2">
                        {{ $post->user->name ?? 'Anggota TSAQIB' }}
                    </a>
                @else
                    {{ $post->user->name ?? 'Anggota TSAQIB' }}
                @endif
            </h4>
            <span class="text-[10px] text-white/40">
                {{ $post->created_at->diffForHumans() }} •
                <span class="font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
            </span>
        </div>
    </div>

    @if ($showManage && Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
        <div class="flex items-center space-x-2" data-no-nav>
            <button data-no-nav onclick="toggleEditModal('{{ $post->id }}')" class="text-xs text-white/60 hover:text-white font-semibold px-2.5 py-1 rounded-lg bg-white/10">
                <i class="fa-solid fa-pen mr-1"></i>Edit
            </button>
            <form data-no-nav action="{{ route('posts.destroy', $post->id) }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-semibold px-2.5 py-1 rounded-lg bg-red-500/10">
                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                </button>
            </form>
        </div>
    @endif
</div>

{{-- Post Body --}}
<h3 class="font-bold text-base text-[var(--cream)] mb-2 leading-snug">{{ $post->title }}</h3>
<p class="text-xs text-white/75 leading-relaxed whitespace-pre-line mb-2">{{ $post->content }}</p>

{{-- Media Grid --}}
@if ($post->media->isNotEmpty())
    @php($media = $post->media)
    @php($mCount = $media->count())
    @php($mCols = $mCount >= 4 ? 'cols-4' : 'cols-' . $mCount)
    @php($mVisible = min($mCount, 4))
    <div class="media-grid {{ $mCols }} my-3"
         data-media="{{ json_encode($media->map(fn($m) => ['url' => $m->url, 'type' => $m->type])) }}">
        @foreach ($media->take($mVisible) as $mIndex => $mItem)
            @php($mExtra = $mCount > 4 && $mIndex === 3 ? $mCount - 4 : 0)
            <div class="media-tile {{ $mCount === 1 ? 'single' : '' }}" data-no-nav data-index="{{ $mIndex }}"
                 onclick="openLightbox(this)">
                @if ($mItem->isVideo())
                    <video src="{{ $mItem->url }}" preload="none" muted playsinline></video>
                    <div class="media-play"><i class="fa-solid fa-play"></i></div>
                @else
                    @if ($mCount === 1)
                        <img class="media-blur" src="{{ $mItem->url }}" alt="" aria-hidden="true" loading="lazy">
                    @endif
                    <img src="{{ $mItem->url }}" alt="{{ $post->title }}" loading="lazy">
                @endif
                @if ($mExtra)
                    <div class="media-more">+{{ $mExtra }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Action Bar (vote pill + komentar + simpan/Tersimpan + share) --}}
@php($myVote = Auth::check() ? $post->votes->first()?->type : null)
@php($isSaved = Auth::check() ? $post->savedBy->isNotEmpty() : false)
<div class="flex items-center gap-2 mt-3 pt-3 border-t border-white/10 flex-wrap">
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

    <span class="action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white/60">
        <i class="fa-regular fa-comment"></i><span>{{ $post->comments_count }}</span>
    </span>

    <button type="button" data-no-nav data-post-id="{{ $post->id }}"
            class="save-btn action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $isSaved ? 'is-active' : '' }}"
            title="{{ $isSaved ? 'Hapus dari Tersimpan' : 'Simpan ke Tersimpan' }}">
        <i class="fa-solid fa-bookmark"></i><span class="hidden sm:inline">{{ $isSaved ? 'Tersimpan' : 'Simpan' }}</span>
    </button>

    <button type="button" data-no-nav
            class="share-btn action-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white/60"
            data-url="{{ route('komunitas.post.show', $post->id) }}">
        <i class="fa-solid fa-share-nodes"></i><span class="hidden sm:inline">Bagikan</span>
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
