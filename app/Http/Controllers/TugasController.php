<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|in:10,11,12',
            'judul' => 'required|string|max:255',
            'file_tugas' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('file_tugas')->store('tugas', 'public');

        Tugas::create([
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'judul' => $request->judul,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Tugas berhasil diunggah!');
    }
}
