{{--
    resources/views/profile/_activity.blade.php
    Daftar aktivitas (posts / comments / saved) dengan footer "See All →" bila
    ada lebih dari batch, atau "No more." bila batch = seluruh data. Pakai
    profile/_item.blade.php per baris (termasuk tombol hapus saat owner).

    $items     : array item unified (dari mapCollection)
    $seeAll    : URL halaman full-list (profile.list)
    $total     : jumlah total tipe konten ini
    $emptyIcon : class FA untuk empty state
    $emptyText : teks empty state
--}}
@php
    $items = $items ?? [];
    $seeAll = $seeAll ?? null;
    $total = $total ?? count($items);
    $emptyIcon = $emptyIcon ?? 'fa-inbox';
    $emptyText = $emptyText ?? 'Belum ada data.';
    $hasMore = $total > count($items);
@endphp

@if(!empty($items))
    <div class="space-y-2">
        @foreach($items as $item)
            @include('profile._item', ['item' => $item])
        @endforeach
    </div>

    @if($hasMore && $seeAll)
        <div class="text-right mt-3">
            <a href="{{ $seeAll }}" class="pr-see-all">Lihat Semua →</a>
        </div>
    @else
        <p class="pr-nomore">Tidak ada lagi.</p>
    @endif
@else
    <div class="pr-empty"><i class="fa-regular {{ $emptyIcon }}"></i><p>{{ $emptyText }}</p></div>
@endif
