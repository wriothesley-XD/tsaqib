{{-- Tab: Dashboard — stat cards + Perlu Perhatian --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="tsaqib-card p-5 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold"><i class="fa-solid fa-users"></i></div>
        <div>
            <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Total Pengguna</span>
            <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_users'] ?? 0 }}</span>
        </div>
    </div>
    <div class="tsaqib-card p-5 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold"><i class="fa-solid fa-newspaper"></i></div>
        <div>
            <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Total Postingan</span>
            <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_posts'] ?? 0 }}</span>
        </div>
    </div>
    <div class="tsaqib-card p-5 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold"><i class="fa-solid fa-book"></i></div>
        <div>
            <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Buku Perpustakaan</span>
            <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_books'] ?? 0 }}</span>
        </div>
    </div>
    <div class="tsaqib-card p-5 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold"><i class="fa-solid fa-user-plus"></i></div>
        <div>
            <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Pendaftar Kelas X</span>
            <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_registrations'] ?? 0 }}</span>
        </div>
    </div>
</div>

{{-- PERLU PERHATIAN: laporan pending terbaru --}}
<div class="tsaqib-card p-6 mt-6">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
        <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-amber-300"></i>
            <span>Perlu Perhatian</span>
        </h3>
        <a href="?tab=laporan" class="text-[10px] text-[var(--gold)] font-semibold hover:underline">Lihat semua →</a>
    </div>

    <div class="space-y-1">
        @forelse($laporan->take(5) as $r)
            @php($item = $r->reportable)
            <div class="flex items-center justify-between gap-3 py-2 border-b border-white/5 last:border-0">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-[var(--gold)] uppercase">{{ $r->reportable_type }}</span>
                    <p class="text-xs text-[var(--cream)] truncate">{{ $item?->title ?? $item?->body ?? '(konten dihapus)' }}</p>
                    <span class="text-[10px] text-white/40">{{ $r->reason }} • {{ $r->created_at->diffForHumans() }}</span>
                </div>
                <a href="?tab=laporan" class="text-[10px] text-[var(--gold)] font-semibold shrink-0">Tinjau →</a>
            </div>
        @empty
            <p class="flex items-center justify-center gap-2 text-xs text-white/40 py-3">
                <i class="fa-solid fa-circle-check text-[#3fd6b0]"></i>
                <span>Tidak ada laporan pending</span>
            </p>
        @endforelse
    </div>
</div>
