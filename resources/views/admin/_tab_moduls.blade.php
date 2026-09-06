{{-- Tab: Kelola Silabus & Modul PAI --}}
<div class="space-y-6">
    {{-- Form Tambah Modul / Silabus Baru --}}
    <div class="tsaqib-card p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
            <div>
                <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                    <i class="fa-solid fa-cloud-arrow-up text-[var(--gold)]"></i>
                    <span>Upload Silabus &amp; Modul Pembelajaran PAI</span>
                </h3>
                <p class="text-xs text-white/50 mt-0.5">Unggah berkas kurikulum, silabus, atau modul ajar untuk siswa dan guru.</p>
            </div>
        </div>

        <form action="{{ route('admin.moduls.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-white/80 mb-1">Judul Materi / Silabus <span class="text-red-400">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: Silabus PAI Semester Ganjil Kelas X"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-white/80 mb-1">Kategori</label>
                    <select name="kategori" class="tsaqib-input w-full px-3 py-2 text-xs">
                        <option value="Silabus">Silabus Kurikulum</option>
                        <option value="Aqidah">Aqidah</option>
                        <option value="Fiqih">Fiqih</option>
                        <option value="Al-Qur'an">Al-Qur'an &amp; Hadits</option>
                        <option value="SKI">Sejarah Kebudayaan Islam (SKI)</option>
                        <option value="Akhlak">Akhlak &amp; Adab</option>
                        <option value="Praktikum">Praktikum Ibadah</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-white/80 mb-1">Target Tingkat Kelas</label>
                    <select name="target_kelas" class="tsaqib-input w-full px-3 py-2 text-xs">
                        <option value="Semua">Semua Kelas (X, XI, XII)</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-white/80 mb-1">Berkas Dokumen (PDF / DOCX, maks 25MB) <span class="text-red-400">*</span></label>
                    <input type="file" name="file" required accept=".pdf,.docx,.doc"
                           class="tsaqib-input w-full px-3 py-1.5 text-xs text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--gold)]/20 file:text-[var(--gold)]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-white/80 mb-1">Deskripsi / Catatan Pembelajaran (Opsional)</label>
                <textarea name="deskripsi" rows="2" placeholder="Tuliskan petunjuk pembelajaran, capaian kompetensi, atau rincian materi..."
                          class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-gold text-xs px-5 py-2.5">
                    <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>
                    <span>Simpan &amp; Terbitkan Materi</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Silabus & Modul --}}
    <div class="tsaqib-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-white/10">
            <div>
                <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                    <i class="fa-solid fa-book-open text-[var(--gold)]"></i>
                    <span>Daftar Materi Silabus &amp; Modul Terdaftar ({{ $moduls->total() }})</span>
                </h3>
                <p class="text-xs text-white/50 mt-0.5">Kelola dan hapus materi pembelajaran yang dapat diakses siswa terverifikasi.</p>
            </div>
        </div>

        <div data-admin-list="moduls"
             data-admin-url="/admin-panel/list/moduls"
             data-admin-page="{{ $moduls->currentPage() }}"
             data-admin-last="{{ $moduls->lastPage() }}"
             data-admin-total="{{ $moduls->total() }}"
             data-admin-per-page="{{ $moduls->perPage() }}">
            <div class="overflow-x-auto">
                <table class="admin-table w-full text-left text-xs text-white/75">
                    <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Judul Materi</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Kelas</th>
                            <th class="p-3">Pengunggah</th>
                            <th class="p-3">Berkas</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-admin-list-body class="divide-y divide-white/10">
                        @include('admin._list_moduls', ['moduls' => $moduls, 'startIndex' => $moduls->firstItem() ?? 1])
                    </tbody>
                </table>
            </div>

            @include('admin._pagination', ['paginator' => $moduls])
            <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
            <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
        </div>
    </div>
</div>
