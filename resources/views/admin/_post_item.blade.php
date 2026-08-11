{{-- Satu kartu Postingan. Dipakai _list_posts (loop) dan controller::list
     untuk merender tiap item saat mode "Lihat Semua" dikelompokkan per kategori. --}}
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
