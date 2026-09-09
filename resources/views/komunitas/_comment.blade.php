{{-- Single comment row. Dipakai daftar komentar di detail & response AJAX storeComment. --}}
@php($canDelete = Auth::check() && (Auth::id() === $c->user_id || Auth::user()->role === 'admin'))
{{-- $penulisLink = true bila user login & penulis komentar masih ada (bukan user terhapus). --}}
@php($penulisLink = Auth::check() && $c->user)
<div class="comment-row flex items-start gap-3 py-3 border-b border-white/5" data-comment-id="{{ $c->id }}">
    @if ($penulisLink)
        <a href="{{ route('profile.show', $c->user->id) }}"
           class="shrink-0 transition-opacity hover:opacity-80"
           aria-label="Lihat profil {{ $c->user->name ?? 'penulis' }}">
            <x-community-avatar :user="$c->user" size="sm" />
        </a>
    @else
        <div class="shrink-0"><x-community-avatar :user="$c->user" size="sm" /></div>
    @endif
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
            @if ($penulisLink)
                <a href="{{ route('profile.show', $c->user->id) }}"
                   class="font-bold text-xs text-[var(--cream)] hover:underline decoration-[var(--gold)]/60 underline-offset-2">
                    {{ $c->user->name ?? 'Anggota TSAQIB' }}
                </a>
            @else
                <span class="font-bold text-xs text-[var(--cream)]">{{ $c->user->name ?? 'Anggota TSAQIB' }}</span>
            @endif
            @if ($c->user?->is_verified_student)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-[#01795F]/25 border border-[#01795F]/50 text-[#3fd6b0] text-[9px] font-bold shrink-0" title="Siswa Terverifikasi SMAN 1 Bukittinggi">
                    <i class="fa-solid fa-circle-check text-[8px]"></i> Siswa SMAN 1
                </span>
            @endif
            <span class="text-[10px] text-white/40">{{ $c->created_at->diffForHumans() }}</span>
            @auth
                <button type="button" class="report-btn ml-auto text-[10px] text-white/40 hover:text-[var(--gold)] font-semibold"
                        data-rt="comment" data-rid="{{ $c->id }}" title="Laporkan komentar">
                    <i class="fa-solid fa-flag"></i>
                </button>
            @endauth
            @if ($canDelete)
                <button type="button" class="comment-delete text-[10px] text-red-400 hover:text-red-300 font-semibold"
                        data-comment-id="{{ $c->id }}" data-confirm="Hapus komentar ini?">
                    <i class="fa-solid fa-trash"></i>
                </button>
            @endif
        </div>
        <p class="text-xs text-white/80 leading-relaxed whitespace-pre-line mt-0.5">{{ $c->body }}</p>
    </div>
</div>
