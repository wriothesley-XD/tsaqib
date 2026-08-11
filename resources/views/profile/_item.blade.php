{{--
    resources/views/profile/_item.blade.php
    =======================================
    Satu baris .pr-item bentuk unified untuk semua tab profil (Postingan,
    Komentar, Tersimpan, Koleksi Buku) dan untuk halaman full-list.

    Markup ini HARUS identik dengan output JS renderItem() di edit.blade.php
    supaya item render-server & item hasil infinite scroll tampak sama.

    Variabel yang dipakai: $item (array: id, icon, iconGold, thumb, eyebrow,
    title, excerpt, link, deletable).
--}}
@php
    $item = $item ?? [];
    $deletable = !empty($item['deletable']);
@endphp
<div class="pr-item" data-item-id="{{ $item['id'] ?? '' }}">
    <div class="pr-item-icon {{ !empty($item['iconGold']) ? 'pr-item-icon-gold' : '' }}">
        @if(!empty($item['thumb']))
            <img src="{{ $item['thumb'] }}" alt="" class="w-full h-full object-cover">
        @else
            <i class="fa-solid {{ $item['icon'] ?? 'fa-feather' }}"></i>
        @endif
    </div>
    <div class="min-w-0 flex-1">
        <a href="{{ $item['link'] ?? '#' }}" class="block min-w-0">
            @if(!empty($item['eyebrow']))
                <span class="text-[10px] font-bold text-[var(--gold)] uppercase tracking-wider block">{{ $item['eyebrow'] }}</span>
            @endif
            <h4 class="font-bold text-sm text-[var(--cream)] truncate">{{ $item['title'] ?? '' }}</h4>
            @if(!empty($item['excerpt']))
                <p class="text-xs text-white/55 truncate">{{ $item['excerpt'] }}</p>
            @endif
        </a>
    </div>
    @if($deletable)
        <button type="button" class="pr-item-del" data-delete-post="{{ $item['id'] }}" title="Hapus postingan" aria-label="Hapus postingan">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    @endif
</div>
