{{-- Tab: Dokumentasi Kegiatan (galeri foto kegiatan di /info) --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-images text-[var(--gold)]"></i>
                <span>Dokumentasi Kegiatan ({{ $documentations->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Upload galeri foto kegiatan — tampil di tab Dokumentasi halaman /info</p>
        </div>
        <button onclick="document.getElementById('add-doc-form').classList.toggle('hidden')"
                class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
            + Dokumentasi Baru
        </button>
    </div>

    <!-- FORM UPLOAD DOKUMENTASI (HIDDEN BY DEFAULT) -->
    <div id="add-doc-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <h4 class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Upload Dokumentasi Kegiatan</h4>

        <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr] gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Nama Kegiatan *</label>
                    <input type="text" name="title" required class="tsaqib-input w-full px-3 py-2 text-xs" placeholder="mis. Peringatan Isra Mi'raj 2026">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="event_date" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kategori</label>
                    <input type="text" name="category" list="doc-categories" class="tsaqib-input w-full px-3 py-2 text-xs" placeholder="mis. Kaderisasi">
                    <datalist id="doc-categories">
                        <option value="Kaderisasi"></option>
                        <option value="Praktikum Lab PAI"></option>
                        <option value="Komunitas"></option>
                        <option value="Peringatan Hari Besar"></option>
                    </datalist>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Foto Kegiatan * (bisa pilih banyak sekaligus — jpg/png/webp, max 5MB/foto)</label>
                <input type="file" name="photos[]" multiple required accept=".jpg,.jpeg,.png,.webp"
                       class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                {{-- Preview thumbnail sebelum submit --}}
                <div id="doc-photo-preview" class="hidden flex-wrap gap-2 mt-3"></div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                    Simpan Dokumentasi
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL DOKUMENTASI -->
    {{-- URL relatif — fetch paginasi bebas mixed-content di belakang proxy TLS. --}}
    <div data-admin-list="documentations"
         data-admin-url="/admin-panel/list/documentations"
         data-admin-page="{{ $documentations->currentPage() }}"
         data-admin-last="{{ $documentations->lastPage() }}"
         data-admin-total="{{ $documentations->total() }}"
         data-admin-per-page="{{ $documentations->perPage() }}">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
                <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Cover</th>
                        <th class="p-3">Kegiatan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Foto</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody data-admin-list-body class="divide-y divide-white/10">
                    @include('admin._list_documentations', ['documentations' => $documentations, 'startIndex' => $documentations->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @include('admin._pagination', ['paginator' => $documentations])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>

{{-- JS: preview thumbnail foto terpilih (ganti pilihan = preview ikut berganti). --}}
<script>
(function () {
    var input   = document.querySelector('input[name="photos[]"]');
    var preview = document.getElementById('doc-photo-preview');
    if (!input || !preview) return;

    input.addEventListener('change', function () {
        preview.innerHTML = '';
        var files = Array.prototype.slice.call(input.files || []);
        if (!files.length) { preview.classList.add('hidden'); return; }

        files.forEach(function (file) {
            var url = URL.createObjectURL(file);
            var img = document.createElement('img');
            img.src = url;
            img.alt = file.name;
            img.className = 'w-16 h-16 object-cover rounded-lg border border-white/15';
            img.onload = function () { URL.revokeObjectURL(url); };
            preview.appendChild(img);
        });
        preview.classList.remove('hidden');
    });
})();
</script>
