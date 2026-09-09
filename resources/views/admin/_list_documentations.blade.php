{{-- Baris tabel Dokumentasi — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($documentations as $index => $doc)
    @php($cover = $doc->photos->first()?->image_path)
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Cover" class="p-3">
            @if($cover)
                <img src="{{ asset('storage/' . $cover) }}" alt="" class="w-12 h-12 rounded-lg object-cover border border-white/10">
            @else
                <div class="w-12 h-12 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white/25">
                    <i class="fa-solid fa-image text-sm"></i>
                </div>
            @endif
        </td>
        <td data-label="Kegiatan" class="p-3 font-semibold text-[var(--cream)] max-w-[220px]">
            <span class="line-clamp-2">{{ $doc->title }}</span>
            <span class="block text-[10px] text-white/35 font-normal mt-0.5">/{{ $doc->slug }}</span>
        </td>
        <td data-label="Kategori" class="p-3">
            @if($doc->category)
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/15 text-[#3fd6b0]">{{ $doc->category }}</span>
            @else
                <span class="text-white/30">—</span>
            @endif
        </td>
        <td data-label="Tanggal" class="p-3 text-white/60">{{ $doc->event_date?->format('d M Y') ?? '—' }}</td>
        <td data-label="Foto" class="p-3 text-white/60">{{ $doc->photos->count() }} foto
@if($doc->video_path)
    <span class="text-[var(--gold)]"> + Video</span>
@endif
        <td data-label="Aksi" class="p-3">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('info.dokumentasi.show', $doc->slug) }}" target="_blank" class="text-xs text-[var(--gold)] hover:underline font-bold">
                    Lihat
                </a>
                <form action="{{ route('admin.documentations.destroy', $doc) }}" method="POST"
                      onsubmit="return confirm('Hapus dokumentasi &quot;{{ $doc->title }}&quot; beserta semua fotonya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-300 hover:text-red-200 hover:underline font-bold cursor-pointer">
                        Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr><td colspan="7" class="p-8 text-center text-white/40">Belum ada dokumentasi kegiatan.</td></tr>
@endforelse
