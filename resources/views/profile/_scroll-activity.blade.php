{{--
    resources/views/profile/_scroll-activity.blade.php
    Daftar aktivitas DESKTOP (posts / comments / saved) dengan infinite scroll:
    batch pertama dirender server-side, sisanya dimuat bertahap via AJAX
    (IntersectionObserver) dari GET /profile/{user}/tabs/{tab}?cursor=.

    vs profile/_activity.blade.php: partial ini memakai container scrollable
    + sentinel untuk load-more; dipakai panel desktop saja (mobile tetap
    _activity + tombol "Lihat Semua"). Buku (books) tidak memakai partial ini.

    Markup tiap baris = profile/_item.blade.php (server-render) dan HARUS cocok
    dengan renderItem() di edit.blade.php (hasil AJAX) agar tampak sama.

    $items     : array item unified (dari mapCollection) — batch pertama
    $seeAll    : URL halaman full-list (profile.list)
    $total     : jumlah total tipe konten ini
    $tab       : key tab (posts|comments|saved) — dipakai endpoint tabs()
    $emptyIcon : class FA untuk empty state
    $emptyText : teks empty state
--}}
@php
    $items = $items ?? [];
    $seeAll = $seeAll ?? null;
    $total = $total ?? count($items);
    $tab = $tab ?? 'posts';
    $emptyIcon = $emptyIcon ?? 'fa-inbox';
    $emptyText = $emptyText ?? 'Belum ada data.';
@endphp

<div class="pr-dscroll" data-dscroll data-tab="{{ $tab }}"
     data-url="{{ route('profile.tabs', [$user->id, $tab]) }}"
     data-total="{{ $total }}">
    @if(!empty($items))
        <div class="space-y-2" data-dscroll-list>
            @foreach($items as $item)
                @include('profile._item', ['item' => $item])
            @endforeach
        </div>

        {{-- Sentinel diamati IntersectionObserver; berisi status load. --}}
        <div class="pr-dscroll-sentinel" data-dscroll-sentinel></div>
    @else
        <div class="pr-empty"><i class="fa-regular {{ $emptyIcon }}"></i><p>{{ $emptyText }}</p></div>
    @endif
</div>
