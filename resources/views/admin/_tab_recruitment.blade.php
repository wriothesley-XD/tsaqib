{{-- Tab: Pendaftaran / Open Recruitment (direlokasi) --}}
<div class="tsaqib-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-user-plus text-[var(--gold)]"></i>
                <span>Status & Data Pendaftar Open Recruitment</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Buka atau tutup pendaftaran siswa baru Kelas X SMAN 1 Bukittinggi.</p>
        </div>

        <form action="{{ route('admin.toggle-recruitment') }}" method="POST" class="flex items-center space-x-2">
            @csrf
            @if($isRecruitmentOpen)
                <input type="hidden" name="status" value="0">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-lock mr-1.5"></i>Tutup Pendaftaran
                </button>
            @else
                <input type="hidden" name="status" value="1">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-door-open mr-1.5"></i>Buka Pendaftaran
                </button>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-white/75">
            <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Nama Lengkap</th>
                    <th class="p-3">Panggilan</th>
                    <th class="p-3">Kelas</th>
                    <th class="p-3">Instagram</th>
                    <th class="p-3">Alasan Bergabung</th>
                    <th class="p-3">Tanggal Submit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($registrations as $index => $r)
                    <tr class="hover:bg-white/5">
                        <td class="p-3 font-bold">{{ $index + 1 }}</td>
                        <td class="p-3 font-semibold text-[var(--cream)]">{{ $r->nama_lengkap }}</td>
                        <td class="p-3">{{ $r->nama_panggilan }}</td>
                        <td class="p-3 font-bold text-[var(--gold)]">{{ $r->kelas }}</td>
                        <td class="p-3">@ {{ $r->instagram_username }}</td>
                        <td class="p-3 max-w-xs truncate">{{ $r->alasan_bergabung }}</td>
                        <td class="p-3 text-white/40">{{ $r->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-white/40">Belum ada data pendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
