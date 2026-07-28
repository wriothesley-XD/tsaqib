<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Mail\PendaftaranMasuk;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    public function store(StorePendaftaranRequest $request)
    {
        $validated = $request->validated();

        // 1. Ambil data komunitas dari config
        $komunitasConfig = config("komunitas.{$validated['komunitas']}");

        // Validasi A: Cek apakah slug komunitas ada & statusnya aktif
        if (!$komunitasConfig || !($komunitasConfig['aktif'] ?? false)) {
            return response()->json([
                'message' => 'Komunitas tidak ditemukan atau sedang tidak aktif.',
            ], 422);
        }

        // Validasi B: Cek apakah role valid (jika komunitas memiliki daftar roles)
        $daftarRoles = $komunitasConfig['roles'] ?? [];
        if (!empty($daftarRoles) && !array_key_exists($validated['role'], $daftarRoles)) {
            return response()->json([
                'message' => 'Role yang dipilih tidak valid untuk komunitas ini.',
            ], 422);
        }

        // 2. Tambahkan nama tampilan komunitas & role ke dalam data
        $validated['komunitas_nama'] = $komunitasConfig['nama'];
        $validated['role_nama'] = $daftarRoles[$validated['role']]['nama'] ?? $validated['role'];

        // 3. Kirim Email Notifikasi ke Admin
        $adminEmail = config('mail.admin_address') ?? env('MAIL_ADMIN_ADDRESS');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new PendaftaranMasuk($validated));
        }

        // 3. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diterima.',
        ], 200);
    }
}
