<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Mail\PendaftaranMasuk;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan form Open Recruitment.
     */
    public function create()
    {
        return view('pendaftaran.create');
    }

    /**
     * Simpan pendaftaran baru dan kirim notifikasi ke email resmi FSI.
     */
    public function store(StorePendaftaranRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user(); // null kalau guest — sekarang itu valid, bukan bug

        $registration = Registration::create([
            'user_id' => $user?->id, // null kalau guest, sesuai migration nullable
            'full_name' => $validated['full_name'], // dari input form, bukan lagi dari akun
            'nickname' => $validated['nickname'],
            'class' => $validated['class'],
            'username_ig' => $validated['username_ig'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        Mail::to(config('mail.admin_address'))->send(new PendaftaranMasuk($registration));
        $registration->update(['email_sent_at' => now()]);

        return redirect()
            ->route('daftar.create')
            ->with('success', 'Pendaftaran berhasil dikirim. Terima kasih!');
    }
}
