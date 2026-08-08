{{--
    resources/views/partials/pagination.blade.php
    ==============================================
    Kontrol pagination tematik TSAQIB (latar gelap + aksen emas/hijau).
    Dipakai via $items->links('partials.pagination') di Perpustakaan & Komunitas.

    Variabel yang disediakan Laravel saat ->links('partials.pagination'):
      - $paginator  : instance LengthAwarePaginator
      - $elements   : array "slider" (range nomor halaman + elipsis '…')
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman"
         class="flex items-center justify-center gap-1.5 flex-wrap select-none">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true"
                  class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-white/20 bg-white/[.03] border border-white/5 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i> Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-white/70 hover:text-[var(--gold)] bg-white/5 hover:bg-white/10 border border-white/10 transition">
                <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i> Prev
            </a>
        @endif

        {{-- Nomor halaman (slider window) --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1.5 py-2 text-xs text-white/30" aria-hidden="true">&hellip;</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ((int) $page === (int) $paginator->currentPage())
                        <span aria-current="page"
                              class="min-w-[2.25rem] text-center inline-flex items-center justify-center px-2.5 py-2 rounded-lg text-xs font-bold text-[var(--cream)] bg-[var(--green)] border border-[var(--green)] shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="min-w-[2.25rem] text-center inline-flex items-center justify-center px-2.5 py-2 rounded-lg text-xs font-semibold text-white/70 hover:text-[var(--gold)] bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-white/70 hover:text-[var(--gold)] bg-white/5 hover:bg-white/10 border border-white/10 transition">
                Next <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
            </a>
        @else
            <span aria-disabled="true"
                  class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-white/20 bg-white/[.03] border border-white/5 cursor-not-allowed">
                Next <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif
