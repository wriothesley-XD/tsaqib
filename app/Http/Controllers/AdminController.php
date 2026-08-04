<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Post;
use App\Models\Registration;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Memeriksa dan memastikan pengguna memiliki hak akses Admin.
     * Mengubah role test1@gmail.com dan admin@fsi.sch.id menjadi 'admin' secara otomatis.
     */
    private function checkAdmin(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        // Auto-assign role admin untuk email khusus terdaftar
        if (in_array($user->email, ['test1@gmail.com', 'admin@fsi.sch.id'])) {
            if ($user->role !== 'admin') {
                $user->update(['role' => 'admin']);
            }
        }

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman ini hanya dapat diakses oleh Admin.');
        }
    }

    /**
     * Dashboard Admin Panel Sederhana.
     * Route: GET /admin-panel
     */
    public function index()
    {
        $this->checkAdmin();

        $users = User::latest()->get();
        $books = Book::latest()->get();
        $posts = Post::with('user')->latest()->get();
        $registrations = Registration::latest()->get();
        $isRecruitmentOpen = Setting::getByKey('recruitment_open', '1') === '1';

        $stats = [
            'total_users' => $users->count(),
            'total_posts' => $posts->count(),
            'total_books' => $books->count(),
            'total_registrations' => $registrations->count(),
        ];

        return view('admin.index', compact('users', 'books', 'posts', 'registrations', 'isRecruitmentOpen', 'stats'));
    }

    /**
     * Ubah role pengguna (admin/member).
     * Route: POST /admin-panel/users/{user}/role
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,member'],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->back()->with('success', 'Role pengguna '.$user->name.' berhasil diperbarui!');
    }

    /**
     * Tambah Buku PDF Perpustakaan Baru.
     * Route: POST /admin-panel/books
     */
    public function storeBook(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $pdfPath = $request->file('pdf')->store('books', 'public');

        Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverPath,
            'pdf_path' => $pdfPath,
            'is_visible' => $request->has('is_visible'),
        ]);

        return redirect()->back()->with('success', 'Buku PDF baru berhasil ditambahkan ke Perpustakaan!');
    }

    /**
     * Hapus Buku PDF Perpustakaan.
     * Route: DELETE /admin-panel/books/{book}
     */
    public function destroyBook(Book $book): RedirectResponse
    {
        $this->checkAdmin();

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->pdf_path && Storage::disk('public')->exists($book->pdf_path)) {
            Storage::disk('public')->delete($book->pdf_path);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus dari Perpustakaan!');
    }

    /**
     * Buka/Tutup Sakelar Open Recruitment.
     * Route: POST /admin-panel/toggle-recruitment
     */
    public function toggleRecruitment(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $status = $request->input('status') === '1' ? '1' : '0';
        Setting::setByKey('recruitment_open', $status);

        $msg = $status === '1' ? 'Pendaftaran Open Recruitment BERHASIL DIBUKA!' : 'Pendaftaran Open Recruitment DITUTUP.';

        return redirect()->back()->with('success', $msg);
    }
}
