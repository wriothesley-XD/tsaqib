{{-- resources/views/admin/index.blade.php — shell: sidebar nav + tab content --}}
@php($pageTitle = 'Admin Panel - TSAQIB SMAN 1 Bukittinggi')
@php($tab = in_array(request('tab'), ['dashboard','laporan','posts','users','books','recruitment']) ? request('tab') : 'dashboard')
@php($adminTabs = [
    'dashboard'   => ['Dashboard', 'fa-gauge-high'],
    'laporan'     => ['Laporan', 'fa-flag'],
    'posts'       => ['Kelola Postingan', 'fa-newspaper'],
    'users'       => ['Pengguna & Role', 'fa-users-gear'],
    'books'       => ['Buku PDF', 'fa-book'],
    'recruitment' => ['Pendaftaran', 'fa-user-plus'],
])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 w-full">

        <!-- Header Banner -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
            <div>
                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider block mb-1">Panel Kelola Administrator</span>
                <h1 class="text-2xl font-display font-bold text-[var(--cream)]">Admin Dashboard TSAQIB</h1>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mobile section bar — hamburger opens the drawer; shows the active section name --}}
        <div class="lg:hidden sticky top-16 z-30 -mx-4 sm:-mx-6 px-4 sm:px-6 mb-4 py-2.5 bg-[#10140F]/95 backdrop-blur-sm border-b border-white/10 flex items-center justify-between gap-3">
            <button id="admin-drawer-btn" type="button"
                    aria-label="Buka navigasi panel" aria-expanded="false" aria-controls="admin-drawer"
                    class="flex items-center gap-2 text-[var(--cream)] font-semibold text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#01795F] rounded-lg">
                <i class="fa-solid fa-bars text-lg" id="admin-drawer-icon"></i>
                <span>Menu</span>
            </button>
            <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider">{{ $adminTabs[$tab][0] ?? 'Dashboard' }}</span>
        </div>

        {{-- Layout: left sidebar (desktop) + content --}}
        <div class="flex gap-6 lg:gap-8 items-start">

            <!-- DESKTOP SIDEBAR (lg+, sticky) -->
            <aside class="hidden lg:block w-60 xl:w-64 shrink-0">
                <div class="lg:sticky lg:top-20 xl:top-24 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto tsaqib-card-flat p-3">
                    <div class="font-label text-[10px] font-bold uppercase tracking-wider text-white/40 px-2 pb-2">Navigasi Panel</div>
                    <nav class="flex flex-col gap-1">
                        @include('admin._nav_items')
                    </nav>
                </div>
            </aside>

            <!-- TAB CONTENT -->
            <div class="flex-1 min-w-0 space-y-6">
                <section data-tab="dashboard"   class="{{ $tab === 'dashboard' ? '' : 'hidden' }}">@include('admin._tab_dashboard')</section>
                <section data-tab="laporan"     class="{{ $tab === 'laporan' ? '' : 'hidden' }}">@include('admin._tab_laporan')</section>
                <section data-tab="posts"       class="{{ $tab === 'posts' ? '' : 'hidden' }}">@include('admin._tab_posts')</section>
                <section data-tab="users"       class="{{ $tab === 'users' ? '' : 'hidden' }}">@include('admin._tab_users')</section>
                <section data-tab="books"       class="{{ $tab === 'books' ? '' : 'hidden' }}">@include('admin._tab_books')</section>
                <section data-tab="recruitment" class="{{ $tab === 'recruitment' ? '' : 'hidden' }}">@include('admin._tab_recruitment')</section>
            </div>
        </div>

    </main>

    {{-- Mobile drawer (mirrors the navbar pattern; rendered outside <main> so it
         isn't trapped under a parent backdrop-filter). Shown below lg only. --}}
    <div id="admin-drawer-backdrop" class="hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"></div>
    <aside id="admin-drawer" class="hidden fixed top-16 bottom-0 left-0 z-50 w-72 max-w-[80vw] border-t border-white/10 bg-[#10140F] p-3 shadow-2xl overflow-y-auto lg:hidden" aria-hidden="true">
        <div class="flex items-center justify-between px-2 pb-2">
            <span class="font-label text-[10px] font-bold uppercase tracking-wider text-white/40">Navigasi Panel</span>
            <button id="admin-drawer-close" type="button" aria-label="Tutup navigasi panel" class="text-white/60 hover:text-[var(--cream)] p-1 -mr-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <nav class="flex flex-col gap-1">
            @include('admin._nav_items')
        </nav>
    </aside>

    <!-- Footer -->
    @include('partials.site-footer')

    {{-- Drawer toggle (vanilla JS, mirrors partials/navbar.blade.php) --}}
    <script>
    (function () {
        const btn      = document.getElementById('admin-drawer-btn');
        const icon     = document.getElementById('admin-drawer-icon');
        const drawer   = document.getElementById('admin-drawer');
        const backdrop = document.getElementById('admin-drawer-backdrop');
        const closeBtn = document.getElementById('admin-drawer-close');
        if (!btn || !drawer || !backdrop) return;

        const openDrawer = () => {
            drawer.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            btn.setAttribute('aria-expanded', 'true');
            drawer.setAttribute('aria-hidden', 'false');
            if (icon) { icon.classList.remove('fa-bars'); icon.classList.add('fa-xmark'); }
        };
        const closeDrawer = () => {
            drawer.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            btn.setAttribute('aria-expanded', 'false');
            drawer.setAttribute('aria-hidden', 'true');
            if (icon) { icon.classList.add('fa-bars'); icon.classList.remove('fa-xmark'); }
        };

        btn.addEventListener('click', () => {
            drawer.classList.contains('hidden') ? openDrawer() : closeDrawer();
        });
        backdrop.addEventListener('click', closeDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !drawer.classList.contains('hidden')) closeDrawer();
        });
    })();
    </script>

</body>
</html>
