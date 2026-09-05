{{-- Script bersama sub-halaman Laboratorium PAI: scroll-reveal + filter client-side. --}}
<script>
(function () {
    /* ===== Scroll-reveal + stagger (IntersectionObserver, pola landing) ===== */
    if ('IntersectionObserver' in window) {
        document.documentElement.classList.add('js-reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
            });
        }, { threshold: .12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    }

    /* ===== Filter client-side (tanpa reload) =====
       Root [data-filter-root]; state: kelas + kategori (pill [data-filter-btn])
       + query [data-filter-search] (cocok dgn data-search-text tiap item).
       Item hilang/muncul dgn fade .labor-fade; empty state [data-filter-empty]. */
    document.querySelectorAll('[data-filter-root]').forEach(function (root) {
        var items = Array.prototype.slice.call(root.querySelectorAll('[data-filter-item]'));
        if (!items.length) return;
        var empty = root.querySelector('[data-filter-empty]');
        var state = { kelas: 'all', kategori: 'all', q: '' };

        function apply() {
            var shown = 0;
            items.forEach(function (el) {
                var ok = (state.kelas === 'all' || el.dataset.kelas === state.kelas)
                      && (state.kategori === 'all' || !state.kategori || el.dataset.kategori === state.kategori)
                      && (!state.q || (el.dataset.searchText || '').indexOf(state.q) !== -1);
                el.classList.toggle('hidden', !ok);
                if (ok) {
                    shown++;
                    // Replay fade saat item kembali tampil.
                    el.classList.remove('labor-fade'); void el.offsetWidth; el.classList.add('labor-fade');
                }
            });
            if (empty) empty.classList.toggle('hidden', shown > 0);
            var count = root.querySelector('[data-filter-count]');
            if (count) count.textContent = shown;
        }

        root.querySelectorAll('[data-filter-btn]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var key  = btn.dataset.filter;   // "kelas" | "kategori"
                var val  = btn.dataset.value;    // "all" | nilai
                state[key] = val;
                btn.parentElement.querySelectorAll('[data-filter-btn][data-filter="' + key + '"]')
                    .forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                apply();
            });
        });

        var search = root.querySelector('[data-filter-search]');
        if (search) {
            search.addEventListener('input', function () {
                state.q = search.value.trim().toLowerCase();
                apply();
            });
        }

        apply();
    });
})();
</script>
