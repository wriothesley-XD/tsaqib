{{-- Tab: Kelola Berita (Kabar Terbaru) --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-bullhorn text-[var(--gold)]"></i>
                <span>Kelola Berita ({{ $news->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Tulis, edit, dan terbitkan kabar terbaru TSAQIB</p>
        </div>
        <button onclick="document.getElementById('add-news-form').classList.toggle('hidden')"
                class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
            + Tulis Berita Baru
        </button>
    </div>

    <!-- FORM TULIS/EDIT BERITA (HIDDEN BY DEFAULT) -->
    <div id="add-news-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h4 id="news-form-title" class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Form Tulis Berita Baru</h4>
            <button type="button" id="news-form-cancel" class="hidden text-[11px] text-white/50 hover:text-white font-semibold">
                <i class="fa-solid fa-xmark mr-1"></i>Batal Edit
            </button>
        </div>

        <form id="news-form" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_news_id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr] gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Judul Berita *</label>
                    <input type="text" name="title" required class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Slug (otomatis dari judul)</label>
                    <input type="text" name="slug" placeholder="kosongkan untuk auto" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Ringkasan (Excerpt)</label>
                <textarea name="excerpt" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs" placeholder="Kosongkan untuk auto dari isi berita"></textarea>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Isi Berita *</label>
                <textarea name="content" rows="6" required class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                    <p id="news-thumb-hint" class="hidden text-[10px] text-white/40 mt-1">Kosongkan = pertahankan thumbnail yang ada.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Tanggal Terbit (kosongkan = draf)</label>
                    <input type="datetime-local" name="published_at" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" id="news-submit-btn" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                    Simpan &amp; Terbitkan
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL BERITA -->
    {{-- URL relatif — fetch paginasi bebas mixed-content di belakang proxy TLS. --}}
    <div data-admin-list="news"
         data-admin-url="/admin-panel/list/news"
         data-admin-page="{{ $news->currentPage() }}"
         data-admin-last="{{ $news->lastPage() }}"
         data-admin-total="{{ $news->total() }}"
         data-admin-per-page="{{ $news->perPage() }}">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
                <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Thumbnail</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody data-admin-list-body class="divide-y divide-white/10">
                    @include('admin._list_news', ['news' => $news, 'startIndex' => $news->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @include('admin._pagination', ['paginator' => $news])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>

{{-- JS: repurpose form create <-> edit. Delegasi event pada tbody agar tetap
     berfungsi setelah paginasi AJAX mengganti baris. --}}
<script>
(function () {
    var form = document.getElementById('news-form');
    if (!form) return;

    var body        = form.closest('.tsaqib-card').querySelector('[data-admin-list-body]');
    var titleEl     = document.getElementById('news-form-title');
    var cancelBtn   = document.getElementById('news-form-cancel');
    var submitBtn   = document.getElementById('news-submit-btn');
    var thumbHint   = document.getElementById('news-thumb-hint');
    // Path relatif supaya action form hasil JS tidak pernah http:// absolut
    // (mixed content) di belakang proxy TLS — pola sama dengan _tab_books.
    var storeUrl    = "/admin-panel/news";
    var updateTpl   = "/admin-panel/news/__ID__";

    function resetToCreate() {
        form.setAttribute('action', storeUrl);
        form.querySelector('[name=_method]').value = 'POST';
        form.querySelector('[name=_news_id]').value = '';
        form.reset();
        titleEl.textContent = 'Form Tulis Berita Baru';
        submitBtn.textContent = 'Simpan & Terbitkan';
        cancelBtn.classList.add('hidden');
        if (thumbHint) thumbHint.classList.add('hidden');
    }

    function enterEdit(payload) {
        form.setAttribute('action', updateTpl.replace('__ID__', payload.id));
        form.querySelector('[name=_method]').value = 'PUT';
        form.querySelector('[name=_news_id]').value = payload.id;
        form.querySelector('[name=title]').value = payload.title;
        form.querySelector('[name=slug]').value = payload.slug;
        form.querySelector('[name=excerpt]').value = payload.excerpt || '';
        form.querySelector('[name=content]').value = payload.content || '';
        form.querySelector('[name=published_at]').value = payload.published_at || '';
        titleEl.textContent = 'Edit Berita';
        submitBtn.textContent = 'Perbarui Berita';
        cancelBtn.classList.remove('hidden');
        if (thumbHint) thumbHint.classList.remove('hidden');
        document.getElementById('add-news-form').classList.remove('hidden');
        document.getElementById('add-news-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    cancelBtn.addEventListener('click', function () {
        resetToCreate();
        document.getElementById('add-news-form').classList.add('hidden');
    });

    // Delegasi: tangani klik "Edit" pada baris manapun (termasuk hasil AJAX).
    if (body) {
        body.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-news-edit]');
            if (!btn) return;
            e.preventDefault();
            try { enterEdit(JSON.parse(btn.dataset.payload)); }
            catch (err) { console.error('Payload berita tidak valid', err); }
        });
    }
})();
</script>
