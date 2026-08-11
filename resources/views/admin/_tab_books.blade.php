{{-- Tab: Buku PDF (direlokasi) --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-book text-[var(--gold)]"></i>
                <span>Kelola Buku PDF Perpustakaan ({{ $books->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Tambah, edit, dan hapus koleksi buku digital FSI</p>
        </div>
        <button onclick="document.getElementById('add-book-form').classList.toggle('hidden')"
                class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
            + Tambah Buku PDF Baru
        </button>
    </div>

    <!-- FORM TAMBAH/EDIT BUKU (HIDDEN BY DEFAULT) -->
    <div id="add-book-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h4 id="book-form-title" class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Form Tambah Buku Digital Baru</h4>
            <button type="button" id="book-form-cancel" class="hidden text-[11px] text-white/50 hover:text-white font-semibold">
                <i class="fa-solid fa-xmark mr-1"></i>Batal Edit
            </button>
        </div>
        <form id="book-form" action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_book_id" value="">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Judul Buku *</label>
                    <input type="text" name="title" required class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Penulis *</label>
                    <input type="text" name="author" required class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kategori *</label>
                    <select name="category" required class="tsaqib-input w-full px-3 py-2 text-xs">
                        <option value="fiqih">Fiqih</option>
                        <option value="aqidah">Aqidah</option>
                        <option value="ski">SKI</option>
                        <option value="hadits">Hadits & Tafsir</option>
                        <option value="modul" selected>Modul PAI</option>
                        <option value="buletin">Buletin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload File PDF Buku <span id="book-pdf-required-mark">*</span></label>
                    <input type="file" name="pdf" accept="application/pdf" class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                    <p id="book-pdf-hint" class="hidden text-[10px] text-white/40 mt-1">Kosongkan = pertahankan PDF yang ada.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload Gambar Cover (Opsional)</label>
                    <input type="file" name="cover" accept="image/*" class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                    <p id="book-cover-hint" class="hidden text-[10px] text-white/40 mt-1">Kosongkan = pertahankan cover yang ada.</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="is_visible" value="1" checked class="rounded border-white/20 bg-white/5 text-[#01795F]">
                    <span class="text-xs text-white/70">Tampilkan di Perpustakaan</span>
                </label>

                <button type="submit" id="book-submit-btn" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                    Simpan Buku PDF
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL BUKU -->
    <div data-admin-list="books"
         data-admin-url="{{ route('admin.list', 'books') }}"
         data-admin-page="{{ $books->currentPage() }}"
         data-admin-last="{{ $books->lastPage() }}"
         data-admin-total="{{ $books->total() }}"
         data-admin-per-page="{{ $books->perPage() }}">

        {{-- Pencarian langsung (AJAX): judul / penulis / kategori. Terintegrasi dengan
            paginasi & "Lihat Semua" — lihat data-admin-search di admin/index.blade.php. --}}
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            <div class="relative flex-1 sm:max-w-xs">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-white/40 pointer-events-none"></i>
                <input type="text" data-admin-search
                       placeholder="Cari judul / penulis / kategori…" aria-label="Cari buku"
                       class="tsaqib-input w-full pl-9 pr-9 py-2 text-xs">
                <button type="button" data-admin-search-clear aria-label="Hapus pencarian"
                        class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md text-white/45 hover:text-[var(--cream)] hover:bg-white/10 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-[11px]"></i>
                </button>
            </div>
            <p class="text-[11px] text-white/40">Menyaring otomatis — judul, penulis, atau kategori.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
                <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Cover</th>
                        <th class="p-3">Judul Buku</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">File PDF</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody data-admin-list-body class="divide-y divide-white/10">
                    @include('admin._list_books', ['books' => $books, 'startIndex' => $books->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @include('admin._pagination', ['paginator' => $books])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>

{{-- JS: repurpose form create <-> edit. Delegasi event pada tbody agar tetap
     berfungsi setelah paginasi/pencarian AJAX mengganti baris (tbody sendiri
     tidak terganti, hanya innerHTML-nya). --}}
<script>
(function () {
    var form = document.getElementById('book-form');
    if (!form) return;

    var body      = form.closest('.tsaqib-card').querySelector('[data-admin-list-body]');
    var titleEl   = document.getElementById('book-form-title');
    var cancelBtn = document.getElementById('book-form-cancel');
    var submitBtn = document.getElementById('book-submit-btn');
    var coverHint = document.getElementById('book-cover-hint');
    var pdfHint   = document.getElementById('book-pdf-hint');
    var pdfMark   = document.getElementById('book-pdf-required-mark');
    var storeUrl  = "{{ route('admin.books.store') }}";
    var updateTpl = "{{ route('admin.books.update', ['book' => '__ID__']) }}";

    function resetToCreate() {
        form.setAttribute('action', storeUrl);
        form.querySelector('[name=_method]').value = 'POST';
        form.querySelector('[name=_book_id]').value = '';
        form.reset();
        // form.reset() tidak mengembalikan <select> ke option ber-atribut selected;
        // kategori default kembali ke 'modul' secara eksplisit.
        var cat = form.querySelector('[name=category]');
        if (cat) cat.value = 'modul';
        var vis = form.querySelector('[name=is_visible]');
        if (vis) vis.checked = true;
        titleEl.textContent = 'Form Tambah Buku Digital Baru';
        submitBtn.textContent = 'Simpan Buku PDF';
        cancelBtn.classList.add('hidden');
        if (coverHint) coverHint.classList.add('hidden');
        if (pdfHint) pdfHint.classList.add('hidden');
        if (pdfMark) pdfMark.classList.remove('hidden');      // PDF wajib saat create
        var pdfInput = form.querySelector('[name=pdf]');
        if (pdfInput) pdfInput.required = true;
    }

    function enterEdit(payload) {
        form.setAttribute('action', updateTpl.replace('__ID__', payload.id));
        form.querySelector('[name=_method]').value = 'PUT';
        form.querySelector('[name=_book_id]').value = payload.id;
        form.querySelector('[name=title]').value = payload.title;
        form.querySelector('[name=author]').value = payload.author;
        form.querySelector('[name=category]').value = payload.category;
        form.querySelector('[name=description]').value = payload.description || '';
        var vis = form.querySelector('[name=is_visible]');
        if (vis) vis.checked = !!payload.is_visible;
        titleEl.textContent = 'Edit Buku PDF';
        submitBtn.textContent = 'Perbarui Buku';
        cancelBtn.classList.remove('hidden');
        if (coverHint) coverHint.classList.remove('hidden');  // file input tak bisa di-prefill
        if (pdfHint) pdfHint.classList.remove('hidden');
        if (pdfMark) pdfMark.classList.add('hidden');         // PDF opsional saat edit
        var pdfInput = form.querySelector('[name=pdf]');
        if (pdfInput) pdfInput.required = false;
        document.getElementById('add-book-form').classList.remove('hidden');
        document.getElementById('add-book-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    cancelBtn.addEventListener('click', function () {
        resetToCreate();
        document.getElementById('add-book-form').classList.add('hidden');
    });

    // Delegasi: tangani klik "Edit" pada baris manapun (termasuk hasil AJAX).
    if (body) {
        body.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-book-edit]');
            if (!btn) return;
            e.preventDefault();
            try { enterEdit(JSON.parse(btn.dataset.payload)); }
            catch (err) { console.error('Payload buku tidak valid', err); }
        });
    }
})();
</script>
