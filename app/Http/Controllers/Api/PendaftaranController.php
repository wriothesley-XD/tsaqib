<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Mail\PendaftaranMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function store(StorePendaftaranRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Pastikan slug komunitas & role yang dikirim Framer memang ada
        // di config/komunitas.php -- jaga-jaga kalau ada typo/data
        // basi di sisi Framer (misal slug komunitas berubah tapi
        // Framer belum di-update).
        $komunitasList = config('komunitas');

        if (! array_key_exists($data['komunitas'], $komunitasList)) {
            return response()->json([
                'success' => false,
                'message' => 'Komunitas tidak ditemukan.',
            ], 422);
        }

        $komunitas = $komunitasList[$data['komunitas']];

        if (! $komunitas['aktif']) {
            return response()->json([
                'success' => false,
                'message' => 'Komunitas ini belum menerima pendaftaran.',
            ], 422);
        }

        if (! array_key_exists($data['role'], $komunitas['roles'])) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan di komunitas ini.',
            ], 422);
        }

        $payload = [
            'komunitas_slug'  => $data['komunitas'],
            'komunitas_nama'  => $komunitas['nama'],
            'role_slug'       => $data['role'],
            'role_nama'       => $komunitas['roles'][$data['role']]['nama'],
            'nama_lengkap'    => $data['nama_lengkap'],
            'nama_panggilan'  => $data['nama_panggilan'],
            'instagram'       => $data['instagram'],
            'alasan'          => $data['alasan'],
        ];

        try {
            Mail::to(config('mail.admin_address', 'fsi@example.com'))
                ->send(new PendaftaranMasuk($payload));
        } catch (\Throwable $e) {
            // Jangan bikin user gagal submit gara-gara email doang yang
            // error (misal SMTP lagi down) -- catat di log, tapi tetap
            // anggap pendaftaran berhasil diterima server.
            Log::error('Gagal kirim email pendaftaran: ' . $e->getMessage(), $payload);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diterima.',
        ]);
    }
}
