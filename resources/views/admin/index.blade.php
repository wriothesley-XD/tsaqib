{{-- resources/views/admin/index.blade.php --}}
@php($pageTitle = 'Admin Panel Dashboard - TSAQIB SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 w-full">

        <!-- Header Banner -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div>
                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider block mb-1">Panel Kelola Administrator</span>
                <h1 class="text-2xl font-display font-bold text-[var(--cream)]">Admin Dashboard TSAQIB</h1>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- 1. DASHBOARD STATISTIK CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="tsaqib-card p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Total Pengguna</span>
                    <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_users'] ?? 0 }}</span>
                </div>
            </div>

            <div class="tsaqib-card p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Total Postingan</span>
                    <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_posts'] ?? 0 }}</span>
                </div>
            </div>

            <div class="tsaqib-card p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Buku Perpustakaan</span>
                    <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_books'] ?? 0 }}</span>
                </div>
            </div>

            <div class="tsaqib-card p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Pendaftar Kelas X</span>
                    <span class="text-2xl font-bold text-[var(--cream)]">{{ $stats['total_registrations'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- 2. KELOLA BUKU PERPUSTAKAAN (PDF) -->
        <div class="tsaqib-card p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div>
                    <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                        <i class="fa-solid fa-book text-[var(--gold)]"></i>
                        <span>Kelola Buku PDF Perpustakaan ({{ count($books) }})</span>
                    </h3>
                    <p class="text-xs text-white/50 mt-0.5">Tambah, edit, dan hapus koleksi buku digital FSI</p>
                </div>
                <button onclick="document.getElementById('add-book-form').classList.toggle('hidden')"
                        class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
                    + Tambah Buku PDF Baru
                </button>
            </div>

            <!-- FORM TAMBAH BUKU (HIDDEN BY DEFAULT) -->
            <div id="add-book-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
                <h4 class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Form Tambah Buku Digital Baru</h4>
                <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
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
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Deskripsi Singkat</label>
                        <textarea name="description" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload File PDF Buku *</label>
                            <input type="file" name="pdf" accept="application/pdf" required class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload Gambar Cover (Opsional)</label>
                            <input type="file" name="cover" accept="image/*" class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="is_visible" value="1" checked class="rounded border-white/20 bg-white/5 text-[#01795F]">
                            <span class="text-xs text-white/70">Tampilkan di Perpustakaan</span>
                        </label>

                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                            Simpan Buku PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABEL BUKU -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-white/75">
                    <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">File PDF</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($books as $index => $book)
                            <tr class="hover:bg-white/5">
                                <td class="p-3 font-bold">{{ $index + 1 }}</td>
                                <td class="p-3 font-semibold text-[var(--cream)]">{{ $book->title }}</td>
                                <td class="p-3 text-white/60">{{ $book->author }}</td>
                                <td class="p-3 font-bold text-[var(--gold)] uppercase">{{ $book->category }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/15 text-[#3fd6b0]">Aktif Tampil</span>
                                </td>
                                <td class="p-3">
                                    @if($book->pdf_path)
                                        <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" class="text-[var(--gold)] font-bold hover:underline">
                                            <i class="fa-solid fa-file-pdf mr-1"></i>Lihat PDF
                                        </a>
                                    @else
                                        <span class="text-white/40">-</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-white/40">Belum ada koleksi buku digital. Klik tombol di atas untuk menambah buku baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. MANAJEMEN USER & ROLE -->
        <div class="tsaqib-card p-6">
            <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-users-gear text-[var(--gold)]"></i>
                <span>Manajemen Pengguna & Role Sistem</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-white/75">
                    <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Minat Komunitas</th>
                            <th class="p-3">Role Sistem saat Ini</th>
                            <th class="p-3">Aksi Ubah Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($users as $index => $u)
                            <tr class="hover:bg-white/5">
                                <td class="p-3 font-bold">{{ $index + 1 }}</td>
                                <td class="p-3 font-semibold text-[var(--cream)] flex items-center space-x-2">
                                    <x-community-avatar :user="$u" size="sm" />
                                    <span>{{ $u->name }}</span>
                                </td>
                                <td class="p-3 text-white/60">{{ $u->email }}</td>
                                <td class="p-3 uppercase font-bold text-[var(--gold)]">{{ $u->selected_community ?? '-' }}</td>
                                <td class="p-3 font-bold">
                                    @if($u->role === 'admin')
                                        <span class="px-2 py-0.5 rounded bg-amber-400/15 text-amber-300">Admin</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-white/10 text-white/70">Member</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center space-x-1">
                                        @csrf
                                        <select name="role" class="tsaqib-input rounded px-2 py-1 text-[11px]">
                                            <option value="member" {{ $u->role === 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="px-2 py-1 rounded bg-[#01795F] text-white font-bold text-[10px]">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-white/40">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. MANAGEMENT SAKELAR OPEN RECRUITMENT & TABEL PENDAFTAR -->
        <div class="tsaqib-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/10">
                <div>
                    <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                        <i class="fa-solid fa-user-plus text-[var(--gold)]"></i>
                        <span>Status & Data Pendaftar Open Recruitment</span>
                    </h3>
                    <p class="text-xs text-white/50 mt-0.5">Buka atau tutup pendaftaran siswa baru Kelas X SMAN 1 Bukittinggi.</p>
                </div>

                <form action="{{ route('admin.toggle-recruitment') }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    @if($isRecruitmentOpen)
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm transition">
                            <i class="fa-solid fa-lock mr-1.5"></i>Tutup Pendaftaran
                        </button>
                    @else
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-bold text-xs shadow-sm transition">
                            <i class="fa-solid fa-door-open mr-1.5"></i>Buka Pendaftaran
                        </button>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-white/75">
                    <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Panggilan</th>
                            <th class="p-3">Kelas</th>
                            <th class="p-3">Instagram</th>
                            <th class="p-3">Alasan Bergabung</th>
                            <th class="p-3">Tanggal Submit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($registrations as $index => $r)
                            <tr class="hover:bg-white/5">
                                <td class="p-3 font-bold">{{ $index + 1 }}</td>
                                <td class="p-3 font-semibold text-[var(--cream)]">{{ $r->nama_lengkap }}</td>
                                <td class="p-3">{{ $r->nama_panggilan }}</td>
                                <td class="p-3 font-bold text-[var(--gold)]">{{ $r->kelas }}</td>
                                <td class="p-3">@ {{ $r->instagram_username }}</td>
                                <td class="p-3 max-w-xs truncate">{{ $r->alasan_bergabung }}</td>
                                <td class="p-3 text-white/40">{{ $r->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-white/40">Belum ada data pendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. KELOLA POSTINGAN KOMUNITAS -->
        <div class="tsaqib-card p-6">
            <h3 class="font-display font-bold text-[var(--cream)] text-base mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-[var(--gold)]"></i>
                <span>Kelola Postingan Members ({{ count($posts) }})</span>
            </h3>

            <div class="space-y-2">
                @forelse($posts as $post)
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
                            <h4 class="font-bold text-xs text-[var(--cream)]">{{ $post->title }}</h4>
                        </div>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-xs text-white/40 py-4">Belum ada postingan komunitas.</div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
