{{-- resources/views/admin/_nav_items.blade.php — shared admin nav items.
    Included by both the desktop sidebar and the mobile drawer in admin/index.blade.php.
    Inherits $adminTabs, $tab, $pendingReportCount from the parent scope.
    The parent <nav> is a flex column (flex flex-col gap-1), so each <a> is a
    block-level flex item that stacks vertically with even spacing — no reliance
    on a separate <style> block. Active item gets a clear gold bg + gold text +
    a left accent bar (child <span>), all via utilities. --}}
@foreach($adminTabs as $key => $meta)
    @php($isActive = $tab === $key)
    <a href="?tab={{ $key }}"
       class="relative flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold transition-colors {{ $isActive ? 'bg-[rgba(201,166,107,0.18)] text-[var(--gold)]' : 'text-white/55 hover:text-[var(--cream)] hover:bg-white/5' }}"
       aria-current="{{ $isActive ? 'page' : 'false' }}">
        @if($isActive)
            <span aria-hidden="true" class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-[var(--gold)]"></span>
        @endif
        <i class="fa-solid {{ $meta[1] }} w-4 text-center"></i>
        <span>{{ $meta[0] }}</span>
        @if($key === 'laporan' && ($pendingReportCount ?? 0) > 0)
            <span class="ml-auto bg-red-500/15 text-red-300 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingReportCount }}</span>
        @endif
    </a>
@endforeach
