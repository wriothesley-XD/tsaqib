{{-- Baris tabel Whitelist NISN — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($nisn_whitelist as $index => $w)
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="NISN" class="p-3 font-semibold text-[var(--cream)]">{{ $w->nisn }}</td>
        <td data-label="NIS" class="p-3 font-mono text-[var(--gold)]">{{ $w->nis ?: '—' }}</td>
        <td data-label="Nama" class="p-3">{{ $w->nama ?: '—' }}</td>
        <td data-label="Kelas" class="p-3 font-bold text-white/70">{{ $w->kelas ?: '—' }}</td>
        <td data-label="Ditambahkan" class="p-3 text-white/40">{{ $w->created_at->format('d M Y, H:i') }}</td>
        <td data-label="Aksi" class="p-3">
            <form action="{{ route('admin.nisn-whitelist.destroy', $w) }}" method="POST"
                  onsubmit="return confirm('Hapus NISN {{ $w->nisn }} dari whitelist?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-300/80 hover:text-red-300 text-xs font-semibold">
                    <i class="fa-solid fa-trash-can mr-1"></i>Hapus
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-6 text-center text-white/40">
            @if(($search ?? '') !== '')
                Tidak ada NISN yang cocok dengan pencarian "{{ $search }}".
            @else
                Whitelist NISN masih kosong — tambah manual atau import dari CSV/Excel di atas.
            @endif
        </td>
    </tr>
@endforelse
