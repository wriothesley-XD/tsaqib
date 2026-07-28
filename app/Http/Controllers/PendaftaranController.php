<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:100',
            'class' => 'required|string|max:100',
            'ig_user' => 'required|string|max:100',
            'reason' => ['required', 'string', 'max:2000']
           
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diterima',
            'data' => $validated,
        ], 200);
    }
}