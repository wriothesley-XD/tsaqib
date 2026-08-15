{{-- Tab: Kelola Postingan (direlokasi) --}}
<div class="tsaqib-card p-6">
    <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-newspaper text-[var(--gold)]"></i>
        <span>Kelola Postingan Members ({{ $posts->total() }})</span>
    </h3>

    {{-- URL relatif — fetch AJAX bebas mixed-content di belakang proxy TLS. --}}
    <div data-admin-list="posts"
         data-admin-url="/admin-panel/list/posts"
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
