{{-- Tab: Pengguna & Role (direlokasi) --}}
<div class="tsaqib-card p-6">
    <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-users-gear text-[var(--gold)]"></i>
        <span>Manajemen Pengguna & Role Sistem</span>
    </h3>

    <div data-admin-list="users"
         data-admin-url="{{ route('admin.list', 'users') }}"
         data-admin-page="{{ $users->currentPage() }}"
         data-admin-last="{{ $users->lastPage() }}"
         data-admin-total="{{ $users->total() }}"
         data-admin-per-page="{{ $users->perPage() }}">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
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
                <tbody data-admin-list-body class="divide-y divide-white/10">
                    @include('admin._list_users', ['users' => $users, 'startIndex' => $users->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @include('admin._pagination', ['paginator' => $users])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>
