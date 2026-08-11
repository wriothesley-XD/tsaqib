{{-- Footer paginasi bersama untuk semua list admin.
    Menerima: $paginator (LengthAwarePaginator).
    Hanya dirender kalau datanya melebihi satu halaman. Root JS [data-admin-list]
    mencari tombol di sini via querySelector; kalau footer tak ada (1 halaman),
    JS tidak melakukan apa-apa. --}}
@php($adminTotal   = $paginator->total())
@php($adminLastPage = $paginator->lastPage())
@php($adminPage    = $paginator->currentPage())
@php($adminPerPage = $paginator->perPage())
@php($adminFrom    = $paginator->firstItem() ?? (($adminPage - 1) * $adminPerPage + 1))
@php($adminTo      = $paginator->lastItem() ?? min($adminPage * $adminPerPage, $adminTotal))

@if($adminTotal > $adminPerPage)
<div data-admin-foot class="mt-4 pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
    <span class="text-[11px] text-white/45">
        Menampilkan <span data-admin-from>{{ $adminFrom }}</span>–<span data-admin-to>{{ $adminTo }}</span> dari {{ $adminTotal }}
    </span>
    <div class="flex items-center gap-2">
        <button type="button" data-admin-prev class="admin-page-btn" {{ $adminPage <= 1 ? 'disabled' : '' }} aria-label="Halaman sebelumnya">
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
        </button>
        <span data-admin-page-label class="px-1 text-[11px] text-white/65 tabular-nums">{{ $adminPage }} / {{ $adminLastPage }}</span>
        <button type="button" data-admin-next class="admin-page-btn" {{ $adminPage >= $adminLastPage ? 'disabled' : '' }} aria-label="Halaman berikutnya">
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </button>
        <span class="w-px h-5 bg-white/10 mx-1"></span>
        <button type="button" data-admin-viewall class="admin-viewall-btn" aria-pressed="false" title="Muat semua data secara bertahap saat di-scroll">
            <i class="fa-solid fa-arrows-down-to-line text-[10px]"></i>
            <span data-admin-viewall-label>Lihat Semua</span>
        </button>
    </div>
</div>
@endif
