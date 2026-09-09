{{-- Tab: Kelola Guru Pengampu PAI --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-chalkboard-user text-[var(--gold)]"></i>
                <span>Kelola Guru Pengampu PAI ({{ $gurus->total() }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Tambah, edit, dan kelola profil ustadz/ustadzah pengampu Laboratorium PAI SMAN 1 Bukittinggi</p>
        </div>
        <button type="button" id="toggle-guru-form-btn"
                onclick="toggleGuruForm()"
                class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition cursor-pointer flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Guru Pengampu</span>
        </button>
    </div>

    <!-- FORM TAMBAH / EDIT GURU PENGAMPU -->
    <div id="guru-form-container" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h4 id="guru-form-title" class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-[var(--gold)]"></i>
                <span>Form Tambah Guru Pengampu Baru</span>
            </h4>
            <button type="button" id="guru-form-cancel" onclick="resetGuruForm()"
                    class="text-[11px] text-white/50 hover:text-white font-semibold cursor-pointer">
                <i class="fa-solid fa-xmark mr-1"></i>Batal Edit / Tutup
            </button>
        </div>

        <form id="guru-form" action="{{ route('admin.gurus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="guru-form-method" value="POST">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        Nama Lengkap &amp; Gelar <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama" id="guru-nama" required
                           placeholder="Contoh: Ustadz Ahmad Fauzi, S.Pd.I., M.Pd."
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        NIP / NUPTK (Opsional)
                    </label>
                    <input type="text" name="nip" id="guru-nip"
                           placeholder="Contoh: 19800101 200501 1 001"
                           class="tsaqib-input w-full px-3 py-2 text-xs font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        Mata Pelajaran
                    </label>
                    <input type="text" name="mapel_pengampu" id="guru-mapel"
                           value="Pendidikan Agama Islam"
                           placeholder="Pendidikan Agama Islam"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        Kelas yang Diampu
                    </label>
                    <div class="flex items-center gap-4 py-2 text-xs text-white/80">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="kelas_diampu[]" value="X" id="guru-kelas-X" class="rounded border-white/20 bg-white/5 text-[#01795F]">
                            <span>Kelas X</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="kelas_diampu[]" value="XI" id="guru-kelas-XI" class="rounded border-white/20 bg-white/5 text-[#01795F]">
                            <span>Kelas XI</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="kelas_diampu[]" value="XII" id="guru-kelas-XII" class="rounded border-white/20 bg-white/5 text-[#01795F]">
                            <span>Kelas XII</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        Nomor WhatsApp (Opsional)
                    </label>
                    <input type="text" name="wa_number" id="guru-wa"
                           placeholder="081234567890"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                    Deskripsi / Bio Guru (Tampil di kartu profil)
                </label>
                <textarea name="deskripsi" id="guru-deskripsi" rows="3"
                          placeholder="Tuliskan kata mutiara, profil singkat, atau bidang keahlian guru pengampu..."
                          class="tsaqib-input w-full px-3 py-2 text-xs leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        <i class="fa-brands fa-facebook-f text-blue-400 mr-1"></i> Akun Facebook (URL)
                    </label>
                    <input type="text" name="facebook_url" id="guru-facebook"
                           placeholder="https://facebook.com/nama.guru"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        <i class="fa-brands fa-instagram text-pink-400 mr-1"></i> Akun Instagram (URL / @username)
                    </label>
                    <input type="text" name="instagram_url" id="guru-instagram"
                           placeholder="https://instagram.com/username atau @username"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                        <i class="fa-solid fa-envelope text-[var(--gold)] mr-1"></i> Email Guru
                    </label>
                    <input type="email" name="email" id="guru-email"
                           placeholder="guru@sman1bukittinggi.sch.id"
                           class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/70 mb-1">
                    Foto Profil Guru (JPG, PNG, WebP — Maks. 5MB)
                </label>
                <input type="file" name="foto" id="guru-foto" accept="image/jpeg,image/png,image/webp"
                       class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0] cursor-pointer">
                <p id="guru-foto-hint" class="hidden text-[10px] text-white/40 mt-1">
                    * Kosongkan jika tidak ingin mengubah foto profil yang sudah ada.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" id="guru-submit-btn" class="px-5 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-bold text-xs shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <span id="guru-submit-text">Simpan Profil Guru</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL DAFTAR GURU PENGAMPU -->
    <div data-admin-list="gurus"
         data-admin-url="/admin-panel/list/gurus"
         data-admin-page="{{ $gurus->currentPage() }}"
         data-admin-last="{{ $gurus->lastPage() }}"
         data-admin-total="{{ $gurus->total() }}"
         data-admin-per-page="{{ $gurus->perPage() }}">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-xs text-white/75">
                <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Foto</th>
                        <th class="p-3">Nama &amp; NIP</th>
                        <th class="p-3">Mapel &amp; Kelas</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3">Media Sosial</th>
                        <th class="p-3">Kontak</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @include('admin._list_gurus', ['startIndex' => $gurus->firstItem() ?? 1])
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-white/50">
                <span>Menampilkan {{ $gurus->firstItem() ?? 0 }} - {{ $gurus->lastItem() ?? 0 }} dari {{ $gurus->total() }} guru</span>
                <div class="flex items-center gap-1">
                    {{ $gurus->links('pagination::simple-tailwind') }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleGuruForm() {
    const container = document.getElementById('guru-form-container');
    if (container.classList.contains('hidden')) {
        container.classList.remove('hidden');
        document.getElementById('guru-nama').focus();
    } else {
        resetGuruForm();
    }
}

function resetGuruForm() {
    const form = document.getElementById('guru-form');
    const container = document.getElementById('guru-form-container');
    form.reset();
    form.action = "{{ route('admin.gurus.store') }}";
    document.getElementById('guru-form-method').value = 'POST';
    document.getElementById('guru-form-title').innerHTML = '<i class="fa-solid fa-user-plus text-[var(--gold)]"></i><span>Form Tambah Guru Pengampu Baru</span>';
    document.getElementById('guru-submit-text').textContent = 'Simpan Profil Guru';
    document.getElementById('guru-foto-hint').classList.add('hidden');
    document.getElementById('guru-kelas-X').checked = false;
    document.getElementById('guru-kelas-XI').checked = false;
    document.getElementById('guru-kelas-XII').checked = false;
    container.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-guru-edit]');
        if (!btn) return;

        const payloadStr = btn.getAttribute('data-payload');
        if (!payloadStr) return;

        try {
            const data = JSON.parse(payloadStr);
            const container = document.getElementById('guru-form-container');
            const form = document.getElementById('guru-form');

            container.classList.remove('hidden');
            form.action = '/admin-panel/gurus/' + data.id;
            document.getElementById('guru-form-method').value = 'PUT';
            document.getElementById('guru-form-title').innerHTML = '<i class="fa-solid fa-pen-to-square text-sky-400"></i><span>Edit Profil: ' + (data.nama || 'Guru') + '</span>';
            document.getElementById('guru-submit-text').textContent = 'Perbarui Profil Guru';
            document.getElementById('guru-foto-hint').classList.remove('hidden');

            document.getElementById('guru-nama').value = data.nama || '';
            document.getElementById('guru-nip').value = data.nip || '';
            document.getElementById('guru-mapel').value = data.mapel_pengampu || 'Pendidikan Agama Islam';
            document.getElementById('guru-wa').value = data.wa_number || '';
            document.getElementById('guru-deskripsi').value = data.deskripsi || '';
            document.getElementById('guru-facebook').value = data.facebook_url || '';
            document.getElementById('guru-instagram').value = data.instagram_url || '';
            document.getElementById('guru-email').value = data.email || '';

            const kelas = Array.isArray(data.kelas_diampu) ? data.kelas_diampu : [];
            document.getElementById('guru-kelas-X').checked = kelas.includes('X');
            document.getElementById('guru-kelas-XI').checked = kelas.includes('XI');
            document.getElementById('guru-kelas-XII').checked = kelas.includes('XII');

            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            document.getElementById('guru-nama').focus();
        } catch (err) {
            console.error('Error parsing guru payload:', err);
        }
    });
});
</script>
