{{-- Baris tabel Pengguna — dipakai baik di tab (hal. 1) maupun endpoint AJAX
     (hal. N). $startIndex = nomor item pertama di halaman ini (1-based). --}}
@forelse($users as $index => $u)
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Nama" class="p-3 font-semibold text-[var(--cream)]">
            <span class="cell-val">
                <x-community-avatar :user="$u" size="sm" />
                <span>{{ $u->name }}</span>
            </span>
        </td>
        <td data-label="Email" class="p-3 text-white/60">{{ $u->email }}</td>
        <td data-label="Komunitas" class="p-3 uppercase font-bold text-[var(--gold)]">{{ $u->selected_community ?? '-' }}</td>
        <td data-label="Role" class="p-3 font-bold">
            @if($u->role === 'admin')
                <span class="px-2 py-0.5 rounded bg-amber-400/15 text-amber-300">Admin</span>
            @else
                <span class="px-2 py-0.5 rounded bg-white/10 text-white/70">Member</span>
            @endif
        </td>
        <td data-label="Ubah Role" class="p-3">
            <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center space-x-1">
                @csrf
                <select name="role" class="tsaqib-input rounded px-2 py-1 text-[11px]">
                    <option value="member" {{ $u->role === 'member' ? 'selected' : '' }}>Member</option>
                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="px-2 py-1 rounded bg-[#01795F] text-white font-bold text-[10px]">Simpan</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-4 text-center text-white/40">Belum ada pengguna terdaftar.</td>
    </tr>
@endforelse
