{{-- Baris tabel Pendaftar — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($registrations as $index => $r)
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Nama" class="p-3 font-semibold text-[var(--cream)]">{{ $r->nama_lengkap }}</td>
        <td data-label="Panggilan" class="p-3">{{ $r->nama_panggilan }}</td>
        <td data-label="Kelas" class="p-3 font-bold text-[var(--gold)]">{{ $r->kelas }}</td>
        <td data-label="Instagram" class="p-3">@ {{ $r->instagram_username }}</td>
        <td data-label="Alasan" class="p-3"><span class="block sm:max-w-xs sm:truncate">{{ $r->alasan_bergabung }}</span></td>
        <td data-label="Submit" class="p-3 text-white/40">{{ $r->created_at->format('d M Y, H:i') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-6 text-center text-white/40">Belum ada data pendaftar.</td>
    </tr>
@endforelse
