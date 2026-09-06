{{-- Tab: Whitelist NISN (Pintu A verifikasi siswa) --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-id-card text-[var(--gold)]"></i>
                <span>Whitelist NISN ({{ $nisnWhitelist->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">
                NISN di daftar ini otomatis lolos verifikasi siswa (Pintu A) tanpa perlu upload foto KTS.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('nisn-import-form').classList.toggle('hidden')"
                    class="px-4 py-2 rounded-xl bg-white/5 border border-white/15 text-[var(--cream)] text-xs font-semibold hover:bg-white/10 transition">
                <i class="fa-solid fa-file-arrow-up mr-1"></i>Import CSV/Excel
            </button>
            <button onclick="document.getElementById('nisn-add-form').classList.toggle('hidden')"
                    class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
                + Tambah NISN
            </button>
        </div>
    </div>

    {{-- FORM IMPORT MASSAL (HIDDEN BY DEFAULT) --}}
    <div id="nisn-import-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-3">
        <h4 class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Import Massal dari File Excel / CSV</h4>
        <p class="text-[11px] text-white/50 leading-relaxed">
            Mendukung file <strong>Excel (.xlsx) Format 8355 (Buku Induk Siswa Dapodik)</strong> maupun CSV umum.
            Sistem otomatis mendeteksi kolom <span class="text-[var(--gold)]">NISN</span>,
            <span class="text-[var(--gold)]">Nomor Induk (NIS)</span>,
            <span class="text-[var(--gold)]">Nama Siswa</span>, dan
            <span class="text-[var(--gold)]">Kelas</span> meskipun baris header berada di baris 14 atau memiliki judul di atasnya.
            Data yang sudah ada akan diperbarui secara otomatis.
        </p>
        <form action="{{ route('admin.nisn-whitelist.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            @csrf
            <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required
                   class="flex-1 w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
            <button type="submit" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm whitespace-nowrap">
                Upload &amp; Import
            </button>
        </form>
    </div>

    {{-- FORM TAMBAH MANUAL SATU NISN (HIDDEN BY DEFAULT) --}}
    <div id="nisn-add-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <h4 class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Tambah NISN / NIS Manual</h4>
        <form action="{{ route('admin.nisn-whitelist.store') }}" method="POST" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">NISN *</label>
                    <input type="text" name="nisn" required maxlength="20" placeholder="mis. 0118703733" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Nomor Induk (NIS)</label>
                    <input type="text" name="nis" maxlength="20" placeholder="mis. 22455" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" maxlength="100" placeholder="Nama siswa" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kelas</label>
                    <input type="text" name="kelas" maxlength="20" placeholder="mis. X-1 / XI" class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                    Simpan Siswa
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL WHITELIST NISN --}}
    <div data-admin-list="nisn_whitelist"
         data-admin-url="/admin-panel/list/nisn_whitelist"
         data-admin-page="{{ $nisnWhitelist->currentPage() }}"
         data-admin-last="{{ $nisnWhitelist->lastPage() }}"
         data-admin-total="{{ $nisnWhitelist->total() }}"
         data-admin-per-page="{{ $nisnWhitelist->perPage() }}">

        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            <div class="relative flex-1 sm:max-w-xs">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-white/40 pointer-events-none"></i>
                <input type="text" data-admin-search
                       placeholder="Cari NISN / NIS / nama / kelas…" aria-label="Cari NISN"
                       class="tsaqib-input w-full pl-9 pr-9 py-2 text-xs">
                <button type="button" data-admin-search-clear aria-label="Hapus pencarian"
                        class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md text-white/45 hover:text-[var(--cream)] hover:bg-white/10 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-[11px]"></i>
                </button>
            </div>
            <p class="text-[11px] text-white/40">Menyaring otomatis — NISN, NIS, nama, atau kelas.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
                <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">NISN</th>
                        <th class="p-3">NIS</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Ditambahkan</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody data-admin-list-body class="divide-y divide-white/10">
                    @include('admin._list_nisn_whitelist', ['nisn_whitelist' => $nisnWhitelist, 'startIndex' => $nisnWhitelist->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @include('admin._pagination', ['paginator' => $nisnWhitelist])
        <p data-admin-status class="hidden text-center text-[11px] text-white/40 py-3"></p>
        <div data-admin-sentinel class="h-1" aria-hidden="true"></div>
    </div>
</div>
