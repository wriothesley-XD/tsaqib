{{-- Baris tabel Silabus & Modul PAI — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($moduls as $index => $m)
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Judul" class="p-3">
            <span class="font-semibold text-[var(--cream)] block">{{ $m->judul }}</span>
            @if($m->deskripsi)
                <span class="text-[11px] text-white/45 block max-w-sm truncate">{{ $m->deskripsi }}</span>
            @endif
        </td>
        <td data-label="Kategori" class="p-3 font-bold text-[var(--gold)] uppercase text-[11px]">
            {{ $m->kategori ?? 'Umum' }}
        </td>
        <td data-label="Target Kelas" class="p-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-white/80">
                {{ $m->target_kelas ? 'Kelas ' . $m->target_kelas : 'Semua Kelas' }}
            </span>
        </td>
        <td data-label="Pengunggah" class="p-3 text-white/60 text-xs">
            {{ $m->user->name ?? 'Admin FSI' }}
        </td>
        <td data-label="Berkas" class="p-3">
            @if($m->file_path)
                <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" rel="noopener" class="text-[var(--gold)] font-bold hover:underline inline-flex items-center gap-1">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Buka File</span>
                </a>
            @else
                <span class="text-white/40">-</span>
            @endif
        </td>
        <td data-label="Aksi" class="p-3">
            <form action="{{ route('admin.moduls.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi silabus/modul ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1 cursor-pointer">
                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-6 text-center text-white/40">Belum ada materi silabus atau modul yang diupload.</td>
    </tr>
@endforelse
