{{-- Tab: Kelola Postingan (direlokasi) --}}
<div class="tsaqib-card p-6">
    <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-newspaper text-[var(--gold)]"></i>
        <span>Kelola Postingan Members ({{ count($posts) }})</span>
    </h3>

    <div class="space-y-2">
        @forelse($posts as $post)
            <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
                    <h4 class="font-bold text-xs text-[var(--cream)]">{{ $post->title }}</h4>
                </div>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center text-xs text-white/40 py-4">Belum ada postingan komunitas.</div>
        @endforelse
    </div>
</div>
