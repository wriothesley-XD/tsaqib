{{-- Baris tabel Berita — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($news as $index => $item)
    @php
        $isPublished = $item->published_at !== null;
        $payload = [
            'id'           => $item->id,
            'title'        => $item->title,
            'slug'         => $item->slug,
            'excerpt'      => $item->excerpt,
            'content'      => $item->content,
            'published_at' => $item->published_at ? $item->published_at->format('Y-m-d\TH:i') : '',
        ];
    @endphp
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Thumbnail" class="p-3">
            @if($item->thumbnail)
                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="w-12 h-12 rounded-lg object-cover border border-white/10">
            @else
                <div class="w-12 h-12 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white/25">
                    <i class="fa-solid fa-image text-sm"></i>
                </div>
            @endif
        </td>
        <td data-label="Judul" class="p-3 font-semibold text-[var(--cream)] max-w-[220px]">
            <span class="line-clamp-2">{{ $item->title }}</span>
            <span class="block text-[10px] text-white/35 font-normal mt-0.5">/{{ $item->slug }}</span>
        </td>
        <td data-label="Penulis" class="p-3 text-white/60">{{ $item->user?->name ?? '—' }}</td>
        <td data-label="Status" class="p-3">
            @if($isPublished)
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/15 text-[#3fd6b0]">Terbit</span>
                <span class="block text-[10px] text-white/40 mt-1">{{ $item->published_at->format('d M Y, H:i') }}</span>
            @else
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 text-white/45">Draf</span>
            @endif
        </td>
        <td data-label="Aksi" class="p-3">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('berita.show', $item->slug) }}" target="_blank" class="text-xs text-[var(--gold)] hover:underline font-bold">
                    <i class="fa-solid fa-eye"></i> Lihat
                </a>
                <button type="button" data-news-edit
                        data-payload="{{ json_encode($payload) }}"
                        class="text-xs text-sky-300 hover:text-sky-200 font-bold">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-6 text-center text-white/40">Belum ada berita. Klik tombol di atas untuk menulis berita pertama.</td>
    </tr>
@endforelse
