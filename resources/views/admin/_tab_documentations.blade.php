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
                        <option value="Kunjungan & Studi Tiru"></option>
                        <option value="Kegiatan Siswa"></option>
                    </datalist>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Foto Kegiatan (bisa pilih banyak sekaligus — jpg/png/webp, max 5MB/foto. Kosong boleh jika mengunggah video)</label>
                <input type="file" name="photos[]" multiple accept=".jpg,.jpeg,.png,.webp"
                       class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                {{-- Preview thumbnail sebelum submit --}}
                <div id="doc-photo-preview" class="hidden flex-wrap gap-2 mt-3"></div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Link Google Drive (opsional — diprioritaskan di atas video upload, hemat storage hosting)</label>
                <input type="url" name="video_drive" placeholder="https://drive.google.com/file/d/FILE_ID/view?usp=sharing"
                       class="tsaqib-input w-full px-3 py-2 text-xs">
                <p class="text-[10px] text-white/40 mt-1">Bagikan file Drive sebagai "Siapa saja yang memiliki link", lalu paste link-nya di sini.</p>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Video Kegiatan (opsional — mp4/webm/mov, maks 50MB)</label>
                <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov"
                       class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[rgba(201,166,107,.18)] file:text-[var(--gold)]">
                {{-- Preview player sebelum submit --}}
                <video id="doc-video-preview" controls playsinline preload="metadata"
                       class="hidden w-full max-w-xs aspect-video rounded-xl border border-white/10 mt-3 bg-black"></video>
            </div>

            {{-- Progress bar upload (muncul hanya saat upload video via XHR) --}}
            <div id="doc-upload-progress" class="hidden items-center gap-3">
                <div class="flex-1 h-2 rounded-full bg-white/10 overflow-hidden">
                    <div id="doc-upload-bar" class="h-full w-0 rounded-full bg-[var(--gold)] transition-[width] duration-150"></div>
                </div>
                <span id="doc-upload-pct" class="text-[11px] font-bold text-[var(--gold)] tabular-nums w-9 text-right">0%</span>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" id="doc-submit-btn" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
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

{{-- JS: preview foto & video terpilih + progress bar upload video.
     Upload video besar via XHR (fetch tidak punya upload progress);
     tanpa video → POST biasa, perilaku lama tak berubah. --}}
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

    // ===== Video: preview player =====
    var form      = input.closest('form');
    var vInput    = form.querySelector('input[name="video"]');
    var vPreview  = document.getElementById('doc-video-preview');
    if (!vInput || !vPreview) return;

    vInput.addEventListener('change', function () {
        if (vPreview.src) URL.revokeObjectURL(vPreview.src);
        var file = vInput.files && vInput.files[0];
        if (!file) {
            vPreview.removeAttribute('src');
            vPreview.classList.add('hidden');
            return;
        }
        vPreview.src = URL.createObjectURL(file);
        vPreview.classList.remove('hidden');
    });

    // ===== Submit dengan progress bar saat ada video =====
    var box  = document.getElementById('doc-upload-progress');
    var bar  = document.getElementById('doc-upload-bar');
    var pct  = document.getElementById('doc-upload-pct');
    var btn  = document.getElementById('doc-submit-btn');

    form.addEventListener('submit', function (e) {
        if (!vInput.files || !vInput.files.length) return; // upload ringan → POST biasa

        e.preventDefault();
        btn.disabled = true;
        box.classList.remove('hidden');
        box.classList.add('flex');

        var fd  = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', form.querySelector('input[name="_token"]').value);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener('progress', function (ev) {
            if (!ev.lengthComputable) return;
            var p = Math.round((ev.loaded / ev.total) * 100);
            bar.style.width = p + '%';
            pct.textContent = p + '%';
        });

        xhr.addEventListener('load', function () {
            // Sukses: Laravel redirect → ikuti URL final (halaman dengan flash sukses).
            if (xhr.status >= 200 && xhr.status < 400 && xhr.responseURL) {
                window.location.href = xhr.responseURL;
                return;
            }
            btn.disabled = false;
            box.classList.add('hidden');
            alert(xhr.status === 422
                ? 'Upload ditolak: periksa format/ukuran file (video mp4/webm/mov maks 50MB, foto max 5MB).'
                : 'Upload gagal (HTTP ' + xhr.status + '). Coba lagi.');
        });
        xhr.addEventListener('error', function () {
            btn.disabled = false;
            box.classList.add('hidden');
            alert('Koneksi terputus saat mengunggah. Coba lagi.');
        });

        xhr.send(fd);
    });
})();
</script>
