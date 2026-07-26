<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'username_ig' => 'required|string|max:100',
            'komunitas' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'alasan' => 'required|string',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diterima',
            'data' => $validated,
        ], 200);
    }
}