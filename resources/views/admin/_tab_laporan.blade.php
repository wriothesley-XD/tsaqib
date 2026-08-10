{{-- Tab: Laporan — antrian laporan pending untuk moderasi --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
        <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center gap-2">
            <i class="fa-solid fa-flag text-[var(--gold)]"></i>
            <span>Laporan Pending ({{ $laporan->count() }})</span>
        </h3>
    </div>

    <div class="space-y-2">
        @forelse($laporan as $r)
            @php($item = $r->reportable)
            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-400/15 text-amber-300 uppercase">{{ $r->reportable_type }}</span>
                        <span class="text-[10px] text-white/40">dilaporkan oleh {{ $r->reporter?->name ?? '?' }} • {{ $r->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($r->reportable_type === 'post' && $item)
                            <a href="{{ route('komunitas.post.show', $item->id) }}" target="_blank" class="text-[10px] text-[var(--gold)] font-semibold hover:underline">Lihat Post</a>
                        @endif
                        <form action="{{ route('admin.reports.resolve', $r->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] text-[#3fd6b0] font-bold px-2 py-1 rounded bg-[#01795F]/15 hover:bg-[#01795F]/25 transition">Selesai</button>
                        </form>
                    </div>
                </div>
                <p class="text-xs text-[var(--cream)] truncate">{{ $item?->title ?? $item?->body ?? '(konten sudah dihapus)' }}</p>
                <p class="text-[10px] text-white/50 mt-0.5">Alasan: {{ $r->reason }}</p>
            </div>
        @empty
            <div class="text-center text-xs text-white/40 py-8">Tidak ada laporan pending. Semua aman. 🎉</div>
        @endforelse
    </div>
</div>
