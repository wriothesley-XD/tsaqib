<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Post;
use App\Models\Registration;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Pastikan pengguna memiliki role 'admin'.
     */
    private function checkAdmin(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        // Auto-assign role admin jika email mengandung admin@ atau admin@fsi.sch.id
        if ($user->email === 'admin@fsi.sch.id' || str_contains($user->email, 'admin')) {
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
     */
    public function index()
    {
        $this->checkAdmin();

        $books = Book::latest()->get();
        $posts = Post::with('user')->latest()->get();
        $registrations = Registration::latest()->get();
        $isRecruitmentOpen = Setting::getByKey('recruitment_open', '1') === '1';

        return view('admin.index', compact('books', 'posts', 'registrations', 'isRecruitmentOpen'));
    }

    /**
     * Buka/Tutup Sakelar Open Recruitment.
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
