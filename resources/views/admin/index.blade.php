<!-- resources/views/admin/index.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TSAQIB SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar (6 Items) -->
    @include('partials.navbar')

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 w-full">

        <!-- Header Banner -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold text-[#01795F] uppercase tracking-wider block mb-1">Panel Kelola Administrator</span>
                <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard TSAQIB</h1>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- 1. MANAGEMENT SAKELAR OPEN RECRUITMENT -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-user-plus text-[#01795F]"></i>
                    <span>Status Open Recruitment</span>
                </h3>
                <p class="text-xs text-slate-500 mt-1">Buka atau tutup pendaftaran siswa baru Kelas X SMAN 1 Bukittinggi.</p>
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

        <!-- 2. TABEL DATA PENDAFTAR OPEN RECRUITMENT -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-users-rectangle text-[#01795F]"></i>
                <span>Data Pendaftar Open Recruitment ({{ count($registrations) }})</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 font-bold uppercase text-[10px] text-slate-500">
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
                    <tbody class="divide-y divide-slate-100">
                        @forelse($registrations as $index => $r)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-bold">{{ $index + 1 }}</td>
                                <td class="p-3 font-semibold text-slate-900">{{ $r->nama_lengkap }}</td>
                                <td class="p-3">{{ $r->nama_panggilan }}</td>
                                <td class="p-3 font-bold text-[#01795F]">{{ $r->kelas }}</td>
                                <td class="p-3">@ {{ $r->instagram_username }}</td>
                                <td class="p-3 max-w-xs truncate">{{ $r->alasan_bergabung }}</td>
                                <td class="p-3 text-slate-400">{{ $r->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-400">Belum ada data pendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. KELOLA BUKU PERPUSTAKAAN (PDF) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-book text-[#01795F]"></i>
                    <span>Kelola Buku Perpustakaan ({{ count($books) }})</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($books as $book)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-xs text-slate-900">{{ $book->title }}</h4>
                            <p class="text-[10px] text-slate-500">{{ $book->author }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-xs text-slate-400 py-4">Belum ada koleksi buku digital.</div>
                @endforelse
            </div>
        </div>

        <!-- 4. KELOLA POSTINGAN KOMUNITAS -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-[#01795F]"></i>
                <span>Kelola Postingan Members ({{ count($posts) }})</span>
            </h3>

            <div class="space-y-2">
                @forelse($posts as $post)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-[#01795F] uppercase">{{ $post->community_slug }}</span>
                            <h4 class="font-bold text-xs text-slate-900">{{ $post->title }}</h4>
                        </div>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold p-1">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-xs text-slate-400 py-4">Belum ada postingan komunitas.</div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
