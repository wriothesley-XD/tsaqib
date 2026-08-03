<?php

namespace App\Http\Controllers;

use App\Mail\PendaftaranFsiDiterima;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OpenRecruitmentController extends Controller
{
    /**
     * Tampilkan halaman Open Recruitment (penjelasan FSI + syarat + form).
     * Route: GET /open-recruitment
     */
    public function showForm(): View
    {
        return view('open-recruitment.form');
    }

    /**
     * Proses submit form pendaftaran.
     * Route: POST /open-recruitment
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:50'],
            'instagram_username' => ['required', 'string', 'max:100'],
            'alasan_bergabung' => ['required', 'string', 'max:2000'],
        ]);

        $registration = Registration::create([
            'user_id' => null, // guest submission, tidak butuh login
            'full_name' => $validated['nama_lengkap'],
            'nickname' => $validated['nama_panggilan'],
            'class' => $validated['kelas'],
            'username_ig' => $validated['instagram_username'],
            'reason' => $validated['alasan_bergabung'],
        ]);

        // Kirim notifikasi ke email resmi FSI.
        // Kalau SMTP sekolah blokir port 587/465 (sudah pernah terjadi di web_smansa),
        // pendaftaran tetap tersimpan di DB walau email gagal terkirim —
        // jangan sampai calon anggota kehilangan data cuma karena email down.
        try {
            Mail::to(config('mail.fsi_notification_address', 'fsi.sman1bkt@gmail.com'))
                ->send(new PendaftaranFsiDiterima($registration));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email notifikasi pendaftaran FSI', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);
            // Sengaja tidak dilempar ulang — submission tetap dianggap sukses.
        }

        return redirect()
            ->route('open.recruitment.thank-you');
    }

    /**
     * Halaman Thank You setelah submit.
     * Route: GET /open-recruitment/terima-kasih
     */
    public function thankYou(): View
    {
        return view('open-recruitment.thank-you');
    }
}
