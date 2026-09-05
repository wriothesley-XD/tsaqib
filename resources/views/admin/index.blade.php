{{-- resources/views/admin/index.blade.php — shell: sidebar nav + tab content --}}
@php($pageTitle = 'Admin Panel - TSAQIB SMAN 1 Bukittinggi')
@php($tab = in_array(request('tab'), ['dashboard','laporan','posts','users','books','news','documentations','recruitment']) ? request('tab') : 'dashboard')
@php($adminTabs = [
    'dashboard'   => ['Dashboard', 'fa-gauge-high'],
    'laporan'     => ['Laporan', 'fa-flag'],
    'posts'       => ['Kelola Postingan', 'fa-newspaper'],
    'news'        => ['Kelola Berita', 'fa-bullhorn'],
    'documentations' => ['Dokumentasi', 'fa-images'],
    'users'       => ['Pengguna & Role', 'fa-users-gear'],
    'books'       => ['Buku PDF', 'fa-book'],
    'recruitment' => ['Pendaftaran', 'fa-user-plus'],
])

{{-- Gaya untuk kontrol paginasi & "View All" list admin. Di-push ke <head>
    lewat @stack('styles') di partials/theme-head (harus didefinisikan SEBELUM
    @include theme-head agar ikut ter-render). --}}
@push('styles')
    .admin-page-btn{
        width:28px;height:28px;border-radius:8px;
        display:inline-flex;align-items:center;justify-content:center;
        background:rgba(247,245,239,.05);
        border:1px solid rgba(247,245,239,.12);
        color:rgba(247,245,239,.7);
        transition:background .15s ease,color .15s ease,border-color .15s ease;
    }
    .admin-page-btn:hover:not(:disabled){ background:rgba(247,245,239,.1); color:var(--cream); }
    .admin-page-btn:disabled{ opacity:.3; cursor:not-allowed; }

    .admin-viewall-btn{
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 12px;border-radius:8px;
        background:rgba(247,245,239,.05);
        border:1px solid rgba(247,245,239,.12);
        color:rgba(247,245,239,.7);
        font-size:11px;font-weight:600;
        transition:background .15s ease,color .15s ease,border-color .15s ease;
    }
    .admin-viewall-btn:hover{ background:rgba(1,121,95,.15); color:#5fd3b0; border-color:rgba(1,121,95,.35); }
    .admin-viewall-btn.is-on{ background:rgba(1,121,95,.25); color:#5fd3b0; border-color:rgba(1,121,95,.45); }

    [data-admin-list]{ transition:opacity .15s ease; }
    [data-admin-list].is-loading{ opacity:.65; }

    [data-admin-status].is-busy::before{
        content:'';display:inline-block;width:10px;height:10px;border-radius:50%;
        border:1.5px solid rgba(247,245,239,.2);border-top-color:#5fd3b0;
        margin-right:6px;vertical-align:-1px;
        animation:adminSpin .6s linear infinite;
    }
    @keyframes adminSpin{ to{ transform:rotate(360deg); } }

    /* ===== Tabel admin → kartu berjejak di layar kecil (<sm) =====
       Tujuan: di ponsel, baris tabel jadi kartu "definition list" (label kecil
       di atas, nilai di bawah) supaya TIDAK ada scroll horizontal. data-label
       di tiap <td> jadi sumber label. Di desktop (≥sm) tabel biasa — tak berubah.
       Kelas .cell-val (inline-flex) nyatukan konten multi-elemen (mis. avatar+nama). */
    .cell-val{ display:inline-flex; align-items:center; gap:.4rem; min-width:0; }

    @media (max-width:639px){
        .admin-table{ width:100%; }
        .admin-table thead{ display:none; }
        .admin-table tbody{ display:flex; flex-direction:column; gap:.6rem; }
        .admin-table tbody > tr{ border-top:0; }            /* hilangkan sisa divide-y */
        .admin-table tr{
            display:block;
            background:rgba(247,245,239,.04);
            border:1px solid rgba(247,245,239,.10);
            border-radius:.85rem;
            padding:.55rem .9rem;
        }
        .admin-table td{
            display:flex; flex-direction:column;
            padding:.3rem 0;
            border:0 !important;
            text-align:left;
            max-width:none !important;                      /* tidak terbatas max-w-xs */
        }
        .admin-table td::before{
            content:attr(data-label);
            font-weight:700; font-size:9px; text-transform:uppercase; letter-spacing:.06em;
            color:rgba(247,245,239,.4);
            margin-bottom:.15rem;
        }
        .admin-table td[data-label="#"]{ display:none; }    /* nomor baris tak berguna di kartu */
        .admin-table td .truncate{                          /* alasan & teks panjang boleh wrap */
            white-space:normal; overflow:visible; text-overflow:clip; max-width:none !important;
        }
    }
@endpush

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
                <h1 class="text-xl sm:text-2xl font-display font-bold text-[var(--cream)]">Admin Dashboard TSAQIB</h1>
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
                <section data-tab="news"        class="{{ $tab === 'news' ? '' : 'hidden' }}">@include('admin._tab_news')</section>
                <section data-tab="documentations" class="{{ $tab === 'documentations' ? '' : 'hidden' }}">@include('admin._tab_documentations')</section>
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

    {{-- Paginasi + "Lihat Semua" (infinite scroll) untuk semua list admin.
         Setiap root [data-admin-list] punya: body, footer (prev/next/Lihat Semua),
         status text, dan sentinel. Halaman di-fetch via AJAX (admin.list) sebagai
         HTML satu halaman + metadata JSON.
           - Mode paginasi: prev/next MENGGANTI isi body (satu halaman).
           - Mode "Lihat Semua": IntersectionObserver MEMBEKALI halaman berikutnya
             saat sentinel mendekati viewport — load bertahap, bukan sekaligus.
           - data-admin-grouped="1" (Posts/Laporan): di mode "Lihat Semua", tiap
             kartu dikelompokkan per kategori (data.items → <section> per grup),
             bukan daftar datar panjang. List tabel tetap sebagai tabel. --}}
    <script>
    (function () {
        function initList(root) {
            var body = root.querySelector('[data-admin-list-body]');
            if (!body) return;

            var baseUrl = root.getAttribute('data-admin-url');
            var state = {
                page:    parseInt(root.getAttribute('data-admin-page'), 10)    || 1,
                last:    parseInt(root.getAttribute('data-admin-last'), 10)    || 1,
                total:   parseInt(root.getAttribute('data-admin-total'), 10)   || 0,
                perPage: parseInt(root.getAttribute('data-admin-per-page'), 10)|| 10,
                grouped: root.getAttribute('data-admin-grouped') === '1',  // Lihat Semua → dikelompokkan per kategori
                viewAll: false,
                loading: false,
                q: '',                 // query pencarian teks aktif (input [data-admin-search])
            };
            var groupLabel = root.getAttribute('data-admin-group-label') || 'Grup';
            var groupEls = {};  // key -> <section> kelompok (mode Lihat Semua yang dikelompokkan)

            var prevBtn  = root.querySelector('[data-admin-prev]');
            var nextBtn  = root.querySelector('[data-admin-next]');
            var pageLab  = root.querySelector('[data-admin-page-label]');
            var fromSp   = root.querySelector('[data-admin-from]');
            var toSp     = root.querySelector('[data-admin-to]');
            var viewBtn  = root.querySelector('[data-admin-viewall]');
            var viewLab  = root.querySelector('[data-admin-viewall-label]');
            var status   = root.querySelector('[data-admin-status]');
            var sentinel = root.querySelector('[data-admin-sentinel]');
            var foot        = root.querySelector('[data-admin-foot]');
            var searchInput = root.querySelector('[data-admin-search]');
            var searchClear = root.querySelector('[data-admin-search-clear]');
            var io = null;

            function pageUrl(p) {
                var url = baseUrl + (baseUrl.indexOf('?') > -1 ? '&' : '?') + 'page=' + p;
                if (state.q) url += '&q=' + encodeURIComponent(state.q);   // pertahankan filter pencarian aktif
                return url;
            }

            // Samakan metadata paginasi dari respons server — wajib setelah pencarian
            // karena total & halaman terakhir bisa berubah.
            function syncMeta(data) {
                state.page    = data.currentPage;
                state.last    = data.lastPage;
                state.total   = data.total;
                state.perPage = data.perPage;
            }

            function setLoading(on) {
                root.classList.toggle('is-loading', on);
                if (prevBtn) prevBtn.disabled = on || state.page <= 1;
                if (nextBtn) nextBtn.disabled = on || state.page >= state.last;
            }

            function setStatus(text, show, busy) {
                if (!status) return;
                status.textContent = text || '';
                status.classList.toggle('hidden', !show);
                status.classList.toggle('is-busy', !!busy);
            }

            function renderFooter() {
                var from = state.total === 0 ? 0 : ((state.page - 1) * state.perPage + 1);
                var to   = Math.min(state.page * state.perPage, state.total);
                if (fromSp) fromSp.textContent = from;
                if (toSp)   toSp.textContent = to;
                if (pageLab) pageLab.textContent = state.page + ' / ' + state.last;
                if (prevBtn) prevBtn.disabled = state.page <= 1;
                if (nextBtn) nextBtn.disabled = state.page >= state.last;
                // Sembunyikan footer paginasi saat cuma 1 halaman (mis. hasil cari sedikit).
                if (foot) foot.classList.toggle('hidden', state.last <= 1);
            }

            function fetchPage(p) {
                state.loading = true;
                setLoading(true);
                return fetch(pageUrl(p), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                }).then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                }).then(function (data) {
                    state.loading = false;
                    setLoading(false);
                    return data;
                }).catch(function (err) {
                    state.loading = false;
                    setLoading(false);
                    setStatus('Gagal memuat data.', true, false);
                    throw err;
                });
            }

            // ---- Mode paginasi: ganti isi body dengan satu halaman ----
            function goToPage(p) {
                if (p < 1 || p > state.last) return Promise.resolve();
                return fetchPage(p).then(function (data) {
                    body.innerHTML = data.html;
                    groupEls = {};
                    syncMeta(data);
                    renderFooter();
                });
            }

            if (prevBtn) prevBtn.addEventListener('click', function () { if (!state.viewAll) goToPage(state.page - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function () { if (!state.viewAll) goToPage(state.page + 1); });

            // ---- Mode "Lihat Semua": infinite scroll.
            //      grouped (Posts/Laporan) → tiap kartu masuk ke kelompok kategorinya.
            //      non-grouped (Users/Books/Registrations) → baris tabel dibekalkan. ----
            function inView() {
                if (!sentinel) return false;
                var rect = sentinel.getBoundingClientRect();
                var vh = window.innerHeight || document.documentElement.clientHeight;
                return rect.top < vh + 300;
            }

            // find-or-create <section> kelompok berdasarkan key (hanya mode grouped).
            function groupSection(key) {
                key = String(key);
                if (groupEls[key]) return groupEls[key];
                var sec = document.createElement('section');
                sec.className = 'mt-6 first:mt-2';
                var h = document.createElement('h5');
                h.className = 'flex items-center gap-2 font-display font-bold text-[var(--gold)] text-[11px] uppercase tracking-wider mb-2';
                var label = document.createElement('span');
                label.className = 'text-white/85';
                label.textContent = groupLabel + ': ' + key;   // textContent → aman dari karakter khusus
                var badge = document.createElement('span');
                badge.setAttribute('data-group-count', '');
                badge.className = 'text-[10px] font-bold text-white/45 bg-white/5 border border-white/10 rounded-full px-2 py-0.5';
                badge.textContent = '0';
                h.appendChild(label); h.appendChild(badge);
                var list = document.createElement('div');
                list.setAttribute('data-group-items', '');
                list.className = 'space-y-2';
                sec.appendChild(h); sec.appendChild(list);
                body.appendChild(sec);
                groupEls[key] = sec;
                return sec;
            }
            function appendGroupedItem(group, html) {
                var sec = groupSection(group);
                var list = sec.querySelector('[data-group-items]');
                list.insertAdjacentHTML('beforeend', html);
                var c = sec.querySelector('[data-group-count]');
                if (c) c.textContent = list.children.length;
            }
            function renderGrouped(data) {
                (data.items || []).forEach(function (it) { appendGroupedItem(it.group, it.html); });
            }

            function loadMore() {
                if (state.loading || !state.viewAll) return;
                if (state.page >= state.last) {
                    stopObserver();
                    setStatus('Semua ' + state.total + ' data termuat.', true, false);
                    return;
                }
                setStatus('Memuat lebih banyak…', true, true);
                fetchPage(state.page + 1).then(function (data) {
                    if (state.grouped && data.items) renderGrouped(data);
                    else body.insertAdjacentHTML('beforeend', data.html);
                    syncMeta(data);
                    renderFooter();
                    if (state.page >= state.last) {
                        stopObserver();
                        setStatus('Semua ' + state.total + ' data termuat.', true, false);
                    } else {
                        setStatus('', false, false);
                        if (inView()) loadMore();   // sentinel masih terlihat → lanjut muat
                    }
                }).catch(function () { /* setStatus sudah diisi fetchPage */ });
            }

            function startObserver() {
                if (io || !sentinel || !('IntersectionObserver' in window)) return;
                io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) { if (e.isIntersecting) loadMore(); });
                }, { rootMargin: '300px 0px' });
                io.observe(sentinel);
            }
            function stopObserver() {
                if (io) { io.disconnect(); io = null; }
            }

            function setViewAll(on) {
                state.viewAll = on;
                if (viewBtn) {
                    viewBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
                    viewBtn.classList.toggle('is-on', on);
                }
                if (viewLab) viewLab.textContent = on ? 'Matikan Scroll' : 'Lihat Semua';
                // prev/next & label halaman hanya relevan di mode paginasi
                if (prevBtn) prevBtn.classList.toggle('hidden', on);
                if (nextBtn) nextBtn.classList.toggle('hidden', on);
                if (pageLab) pageLab.classList.toggle('hidden', on);

                stopObserver();
                if (on) {
                    if (state.grouped) {
                        // Bangun ulang dari hal. 1 sebagai <section> per kategori (bukan flat).
                        body.innerHTML = ''; groupEls = {};
                        fetchPage(1).then(function (data) {
                            renderGrouped(data);
                            syncMeta(data);
                            renderFooter();
                            startObserver(); loadMore();
                        }).catch(function () {});
                    } else {
                        // Reset ke halaman 1 dulu supaya akumulasi scroll mulai dari atas.
                        var afterReset = function () { startObserver(); loadMore(); };
                        if (state.page !== 1) {
                            goToPage(1).then(afterReset).catch(function () {});
                        } else {
                            afterReset();
                        }
                    }
                } else {
                    setStatus('', false, false);
                    groupEls = {};
                    // grouped body memakai <section> → rebuild ke flat; otherwise balik ke hal. 1.
                    if (state.grouped || state.page !== 1) goToPage(1);
                }
            }

            // ---- Pencarian teks (resource dengan input [data-admin-search]). ----
            // Menyaring hasil langsung (debounced); keluar dari mode "Lihat Semua"
            // karena hasil cari tampil terpaginasi. state.q dibawa pageUrl() →
            // paginasi & infinite-scroll mempertahankan filter aktif.
            function exitViewAllUI() {
                state.viewAll = false;
                stopObserver();
                if (viewBtn) { viewBtn.setAttribute('aria-pressed', 'false'); viewBtn.classList.remove('is-on'); }
                if (viewLab) viewLab.textContent = 'Lihat Semua';
                if (prevBtn) prevBtn.classList.remove('hidden');
                if (nextBtn) nextBtn.classList.remove('hidden');
                if (pageLab) pageLab.classList.remove('hidden');
            }

            function syncSearchUI() {
                if (!searchInput) return;
                if (searchClear) searchClear.classList.toggle('hidden', !searchInput.value);
            }

            var searchReqId = 0;   // token: hanya respons pencarian terbaru yang dipakai
            function applySearch(q) {
                var newQ = (q || '').trim();
                if (newQ === state.q) return;
                state.q = newQ;
                syncSearchUI();
                if (state.viewAll) exitViewAllUI();        // pencarian selalu di mode paginasi
                setStatus(state.q ? 'Mencari…' : '', !!state.q, true);
                var myId = ++searchReqId;
                fetchPage(1).then(function (data) {        // hal. 1 hasil filter (bypass guard state.last)
                    if (myId !== searchReqId) return;       // ada pencarian lebih baru → abaikan hasil usang
                    body.innerHTML = data.html;
                    groupEls = {};
                    syncMeta(data);
                    renderFooter();
                    setStatus('', false, false);
                }).catch(function () {});
            }

            if (searchInput) {
                var searchTimer = null;
                searchInput.addEventListener('input', function () {
                    syncSearchUI();
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function () { applySearch(searchInput.value); }, 300);
                });
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') { searchInput.value = ''; syncSearchUI(); applySearch(''); }
                });
            }
            if (searchClear) {
                searchClear.addEventListener('click', function () {
                    if (!searchInput) return;
                    searchInput.value = ''; syncSearchUI(); applySearch(''); searchInput.focus();
                });
            }
            syncSearchUI();   // sinkronkan tombol "hapus pencarian" saat init

            if (viewBtn) viewBtn.addEventListener('click', function () { setViewAll(!state.viewAll); });

            renderFooter();
        }

        document.querySelectorAll('[data-admin-list]').forEach(initList);
    })();
    </script>

</body>
</html>
