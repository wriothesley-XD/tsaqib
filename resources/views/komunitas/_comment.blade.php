{{-- Single comment row. Dipakai daftar komentar di detail & response AJAX storeComment. --}}
@php($canDelete = Auth::check() && (Auth::id() === $c->user_id || Auth::user()->role === 'admin'))
<div class="comment-row flex items-start gap-3 py-3 border-b border-white/5" data-comment-id="{{ $c->id }}">
    <x-community-avatar :user="$c->user" size="sm" />
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
            <span class="font-bold text-xs text-[var(--cream)]">{{ $c->user->name ?? 'Anggota TSAQIB' }}</span>
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
