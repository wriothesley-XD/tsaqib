<?php

namespace App\Http\Controllers;

use App\Models\NisnWhitelist;
use App\Models\StudentVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Verifikasi siswa 2 pintu (blueprint RBAC poin 3).
 *
 *   Pintu A — NISN cocok dgn whitelist config/nisn.php → langsung terverifikasi.
 *   Pintu B — NISN di luar whitelist + upload foto KTS → baris pending,
 *             disetujui/ditolak admin (resolve()).
 *
 * Route:
 *   POST /verifikasi-siswa                                (auth + throttle:5,1)
 *   POST /admin-panel/verifications/{verification}/resolve (admin)
 */
class VerificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->is_verified_student) {
            return redirect()->back()->with('success', 'Akun kamu sudah terverifikasi sebagai siswa.');
        }

        $data = $request->validate([
            'nisn'      => 'required|string|max:20',
            'kelas'     => 'nullable|string|max:20',
            'kts_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Pintu A: whitelist NISN sekolah (tabel nisn_whitelist, dikelola admin —
        // manual atau import CSV/Excel massal. Menggantikan config/nisn.php lama).
        if (NisnWhitelist::where('nisn', $data['nisn'])->exists()) {
            $user->update(['is_verified_student' => true]);

            return redirect()->back()->with('success', 'NISN cocok — akun terverifikasi sebagai siswa.');
        }

        // Pintu B: luar whitelist → wajib foto KTS untuk approval manual.
        if (! $request->hasFile('kts_photo')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'NISN tidak ditemukan di whitelist. Silakan upload foto KTS untuk diverifikasi manual.');
        }

        // Satu pengajuan pending per user — cegah spam baris.
        if (StudentVerification::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'Kamu sudah punya pengajuan yang sedang ditinjau admin.');
        }

        StudentVerification::create([
            'user_id'        => $user->id,
            'nisn'           => $data['nisn'],
            'kelas'          => $data['kelas'] ?? null,
            'kts_photo_path' => $request->file('kts_photo')->store('verifications', 'public'),
            'status'         => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan terkirim — menunggu verifikasi admin.');
    }

    /**
     * Setujui / tolak pengajuan (admin). Approve → tandai user terverifikasi.
     * Route: POST /admin-panel/verifications/{verification}/resolve  (status=approved|rejected)
     */
    public function resolve(Request $request, StudentVerification $verification): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $status = $request->input('status') === 'approved' ? 'approved' : 'rejected';
        $verification->update(['status' => $status]);

        if ($status === 'approved') {
            $verification->user->update(['is_verified_student' => true]);
        }

        return redirect()->back()->with('success', $status === 'approved'
            ? 'Pengajuan disetujui — siswa terverifikasi.'
            : 'Pengajuan ditolak.');
    }

    /** Defense in depth — route admin juga sudah dilindungi middleware 'admin'. */
    private function authorizeAdmin(Request $request): void
    {
        if (! in_array($request->user()?->role, ['admin', 'guru'], true)) {
            abort(403);
        }
    }
}
