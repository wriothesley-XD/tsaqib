{{-- Tab: Pengguna & Role (direlokasi) --}}
<div class="tsaqib-card p-6">
    <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-users-gear text-[var(--gold)]"></i>
        <span>Manajemen Pengguna & Role Sistem</span>
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-white/75">
            <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Nama Lengkap</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Minat Komunitas</th>
                    <th class="p-3">Role Sistem</th>
                    <th class="p-3">Aksi Ubah Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($users as $index => $u)
                    <tr class="hover:bg-white/5">
                        <td class="p-3 font-bold">{{ $index + 1 }}</td>
                        <td class="p-3 font-semibold text-[var(--cream)] flex items-center space-x-2">
                            <x-community-avatar :user="$u" size="sm" />
                            <span>{{ $u->name }}</span>
                        </td>
                        <td class="p-3 text-white/60">{{ $u->email }}</td>
                        <td class="p-3 uppercase font-bold text-[var(--gold)]">{{ $u->selected_community ?? '-' }}</td>
                        <td class="p-3 font-bold">
                            @if($u->role === 'admin')
                                <span class="px-2 py-0.5 rounded bg-amber-400/15 text-amber-300">Admin</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-white/10 text-white/70">Member</span>
                            @endif
                        </td>
                        <td class="p-3">
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
            </tbody>
        </table>
    </div>
</div>
