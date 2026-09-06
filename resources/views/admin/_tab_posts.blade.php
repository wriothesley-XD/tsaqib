{{-- Tab: Kelola Postingan (direlokasi) --}}
<div class="tsaqib-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-[var(--gold)]"></i>
                <span>Kelola Postingan Members ({{ $posts->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Pantau dan kelola seluruh konten diskusi pelajar di 13 circle komunitas.</p>
        </div>

        {{-- Filter Per Komunitas --}}
        <div class="flex items-center gap-2">
            <label for="admin-post-comm-filter" class="text-xs text-white/60 font-semibold whitespace-nowrap">Circle:</label>
            <select id="admin-post-comm-filter"
                    onchange="location.href='?tab=posts&community=' + encodeURIComponent(this.value)"
                    class="tsaqib-input py-1.5 px-3 text-xs bg-black/40 border border-white/15 rounded-xl text-[var(--cream)]">
                <option value="all" {{ empty($selectedCommunity) || $selectedCommunity === 'all' ? 'selected' : '' }}>Semua Circle Komunitas</option>
                @foreach(config('komunitas.daftar', []) as $kom)
                    <option value="{{ $kom['slug'] }}" {{ ($selectedCommunity ?? '') === $kom['slug'] ? 'selected' : '' }}>
                        {{ $kom['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- URL relatif — fetch AJAX bebas mixed-content di belakang proxy TLS. --}}
    <div data-admin-list="posts"
         data-admin-url="/admin-panel/list/posts{{ !empty($selectedCommunity) && $selectedCommunity !== 'all' ? '?community='.$selectedCommunity : '' }}"
         data-admin-page="{{ $posts->currentPage() }}"
         data-admin-last="{{ $posts->lastPage() }}"
         data-admin-total="{{ $posts->total() }}"
         data-admin-per-page="{{ $posts->perPage() }}"
         data-admin-grouped="1"
         data-admin-group-label="Komunitas">
        <div data-admin-list-body class="space-y-2">
            @include('admin._list_posts', ['posts' => $posts, 'startIndex' => $posts->firstItem() ?? 1])
        </div>

        @include('admin._pagination', ['paginator' => $posts])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>
