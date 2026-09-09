{{-- Tab: Kelola Dokumen & Struktur Laboratorium PAI --}}
<div class="space-y-6">
    {{-- Card 1: Infografis Struktur Organisasi --}}
    <div class="tsaqib-card p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
            <div>
                <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                    <i class="fa-solid fa-sitemap text-[var(--gold)]"></i>
                    <span>Kelola Infografis Struktur Organisasi</span>
                </h3>
                <p class="text-xs text-white/50 mt-0.5">Unggah atau ganti gambar bagan struktur pembina dan kepengurusan siswa di halaman Laboratorium PAI.</p>
            </div>
        </div>

        <form action="{{ route('admin.labor-documents.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Bagan Struktur Pembina --}}
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider">
                            1. Bagan Pembina &amp; Laboratorium PAI
                        </span>
                        @if(!empty($laborSettings['struktur_organisasi_pembina']))
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/20 text-[#3fd6b0] border border-[#01795F]/30">Custom Upload</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-white/50">Default Sistem</span>
                        @endif
                    </div>

                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black/40 border border-white/10 flex items-center justify-center relative group">
                        <img src="{{ !empty($laborSettings['struktur_organisasi_pembina']) ? asset('storage/' . $laborSettings['struktur_organisasi_pembina']) : asset('images/struktur.webp') }}"
                             alt="Bagan Pembina" class="w-full h-full object-contain p-2">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/75 mb-1">Unggah Gambar Baru (JPG, PNG, WEBP, maks 10MB)</label>
                        <input type="file" name="struktur_organisasi_pembina" accept="image/*"
                               class="tsaqib-input w-full px-3 py-1.5 text-xs text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--gold)]/20 file:text-[var(--gold)]">
                    </div>

                    @if(!empty($laborSettings['struktur_organisasi_pembina']))
                        <div class="pt-2 border-t border-white/10 flex justify-end">
                            <button type="button" onclick="if(confirm('Reset gambar ini ke bawaan sistem?')) { document.getElementById('del-pembina-form').submit(); }"
                                    class="text-xs text-red-400 hover:text-red-300 font-semibold cursor-pointer">
                                <i class="fa-solid fa-rotate-left mr-1"></i>Reset ke Default
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Bagan Kepengurusan Siswa --}}
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider">
                            2. Bagan Kepengurusan Siswa FSI
                        </span>
                        @if(!empty($laborSettings['struktur_organisasi_siswa']))
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/20 text-[#3fd6b0] border border-[#01795F]/30">Custom Upload</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-white/50">Default Sistem</span>
                        @endif
                    </div>

                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black/40 border border-white/10 flex items-center justify-center relative group">
                        <img src="{{ !empty($laborSettings['struktur_organisasi_siswa']) ? asset('storage/' . $laborSettings['struktur_organisasi_siswa']) : asset('images/kepengurusan.webp') }}"
                             alt="Bagan Kepengurusan" class="w-full h-full object-contain p-2">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/75 mb-1">Unggah Gambar Baru (JPG, PNG, WEBP, maks 10MB)</label>
                        <input type="file" name="struktur_organisasi_siswa" accept="image/*"
                               class="tsaqib-input w-full px-3 py-1.5 text-xs text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--gold)]/20 file:text-[var(--gold)]">
                    </div>

                    @if(!empty($laborSettings['struktur_organisasi_siswa']))
                        <div class="pt-2 border-t border-white/10 flex justify-end">
                            <button type="button" onclick="if(confirm('Reset gambar ini ke bawaan sistem?')) { document.getElementById('del-siswa-form').submit(); }"
                                    class="text-xs text-red-400 hover:text-red-300 font-semibold cursor-pointer">
                                <i class="fa-solid fa-rotate-left mr-1"></i>Reset ke Default
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-gold text-xs px-6 py-2.5">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <span>Simpan Perubahan Gambar</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Card 2: Profil Buku TSAQIB & Monev Internal --}}
    <div class="tsaqib-card p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
            <div>
                <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                    <i class="fa-solid fa-book-bookmark text-[var(--gold)]"></i>
                    <span>Khazanah Digital: Profil Buku TSAQIB &amp; Dokumen Monev Internal</span>
                </h3>
                <p class="text-xs text-white/50 mt-0.5">Kelola Flipbook digital dan berkas PDF evaluasi resmi yang ditampilkan di ruang baca Laboratorium PAI.</p>
            </div>
        </div>

        <form action="{{ route('admin.labor-documents.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Profil Buku TSAQIB --}}
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                    <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider block">
                        Profil Buku TSAQIB
                    </span>

                    <div>
                        <label class="block text-xs font-semibold text-white/75 mb-1">Tautan Flipbook Interaktif (Heyzine URL)</label>
                        <input type="url" name="profil_buku_tsaqib_url"
                               value="{{ $laborSettings['profil_buku_tsaqib_url'] ?? 'https://heyzine.com/flip-book/bdf3f31765.html' }}"
                               placeholder="https://heyzine.com/flip-book/..."
                               class="tsaqib-input w-full px-3 py-2 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/75 mb-1">Atau Unggah Berkas Dokumen PDF (Maks 30MB)</label>
                        <input type="file" name="profil_buku_tsaqib_pdf" accept=".pdf"
                               class="tsaqib-input w-full px-3 py-1.5 text-xs text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--gold)]/20 file:text-[var(--gold)]">
                        @if(!empty($laborSettings['profil_buku_tsaqib_pdf']))
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <a href="{{ asset('storage/' . $laborSettings['profil_buku_tsaqib_pdf']) }}" target="_blank" class="text-[var(--gold)] hover:underline inline-flex items-center gap-1">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat Berkas PDF Tersimpan
                                </a>
                                <button type="button" onclick="if(confirm('Hapus file PDF ini?')) { document.getElementById('del-buku-form').submit(); }" class="text-red-400 hover:text-red-300 font-semibold cursor-pointer">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Monev Internal Pemerintah Daerah --}}
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                    <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider block">
                        Monev Internal Pemerintah Daerah
                    </span>

                    <div>
                        <label class="block text-xs font-semibold text-white/75 mb-1">Tautan Dokumen (Heyzine/Google Drive/dst — opsional)</label>
                        <input type="url" name="monev_internal_url"
                               value="{{ $laborSettings['monev_internal_url'] ?? '' }}"
                               placeholder="https://heyzine.com/flip-book/... atau link Google Drive"
                               class="tsaqib-input w-full px-3 py-2 text-xs">
                        <p class="text-[11px] text-white/45 mt-1">Kalau diisi, tautan ini yang ditampilkan (bukan file PDF di bawah). Kosongkan untuk pakai PDF upload.</p>
                        @if(!empty($laborSettings['monev_internal_url']))
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <a href="{{ $laborSettings['monev_internal_url'] }}" target="_blank" class="text-[var(--gold)] hover:underline inline-flex items-center gap-1">
                                    <i class="fa-solid fa-link"></i> Buka Tautan Tersimpan
                                </a>
                                <button type="button" onclick="if(confirm('Hapus tautan ini?')) { document.getElementById('del-monev-url-form').submit(); }" class="text-red-400 hover:text-red-300 font-semibold cursor-pointer">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-white/10">
                        <label class="block text-xs font-semibold text-white/75 mb-1">Atau Unggah Berkas PDF Monev Resmi (Maks 30MB)</label>
                        <input type="file" name="monev_internal_pdf" accept=".pdf"
                               class="tsaqib-input w-full px-3 py-1.5 text-xs text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[var(--gold)]/20 file:text-[var(--gold)]">
                        <p class="text-[11px] text-white/45 mt-1">Jika tautan di atas kosong dan PDF belum diupload, sistem akan menampilkan dokumen monev resmi default.</p>
                        @if(!empty($laborSettings['monev_internal_pdf']))
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <a href="{{ asset('storage/' . $laborSettings['monev_internal_pdf']) }}" target="_blank" class="text-[var(--gold)] hover:underline inline-flex items-center gap-1">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat Berkas PDF Tersimpan
                                </a>
                                <button type="button" onclick="if(confirm('Hapus file PDF ini?')) { document.getElementById('del-monev-form').submit(); }" class="text-red-400 hover:text-red-300 font-semibold cursor-pointer">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-gold text-xs px-6 py-2.5">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <span>Simpan Pengaturan Dokumen</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Hidden forms for reset/delete --}}
<form id="del-pembina-form" action="{{ route('admin.labor-documents.destroy', 'struktur_organisasi_pembina') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
<form id="del-siswa-form" action="{{ route('admin.labor-documents.destroy', 'struktur_organisasi_siswa') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
<form id="del-buku-form" action="{{ route('admin.labor-documents.destroy', 'profil_buku_tsaqib_pdf') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
<form id="del-monev-form" action="{{ route('admin.labor-documents.destroy', 'monev_internal_pdf') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
<form id="del-monev-url-form" action="{{ route('admin.labor-documents.destroy', 'monev_internal_url') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
