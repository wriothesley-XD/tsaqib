{{-- Tab: Laporan — antrian laporan pending untuk moderasi --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
        <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center gap-2">
            <i class="fa-solid fa-flag text-[var(--gold)]"></i>
            <span>Laporan Pending ({{ $laporan->total() }})</span>
        </h3>
    </div>

    {{-- URL relatif — fetch AJAX bebas mixed-content di belakang proxy TLS. --}}
    <div data-admin-list="laporan"
         data-admin-url="/admin-panel/list/laporan"
         data-admin-page="{{ $laporan->currentPage() }}"
         data-admin-last="{{ $laporan->lastPage() }}"
         data-admin-total="{{ $laporan->total() }}"
         data-admin-per-page="{{ $laporan->perPage() }}"
         data-admin-grouped="1"
         data-admin-group-label="Tipe">
        <div data-admin-list-body class="space-y-2">
            @include('admin._list_laporan', ['laporan' => $laporan, 'startIndex' => $laporan->firstItem() ?? 1])
        </div>

        @include('admin._pagination', ['paginator' => $laporan])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>
