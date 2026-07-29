<?php

namespace App\Http\Controllers;

class TsaqibController extends Controller
{
    /**
     * Labor PAI: Visi-Misi FSI + Struktur Organisasi (Pembina & Siswa).
     * Konten masih placeholder — tunggu data resmi dari pengurus FSI,
     * tinggal ganti array di bawah begitu datanya ada.
     */
    public function laborPai()
    {
        $visiMisi = [
            'visi' => 'Visi FSI belum diisi — tunggu data resmi.',
            'misi' => [
                'Misi 1 — belum diisi',
                'Misi 2 — belum diisi',
                'Misi 3 — belum diisi',
            ],
        ];

        $pembina = [
            ['nama' => 'Nama Pembina', 'jabatan' => 'Pembina FSI'],
        ];

        $pengurusSiswa = [
            ['nama' => 'Nama Ketua', 'jabatan' => 'Ketua FSI'],
            ['nama' => 'Nama Wakil', 'jabatan' => 'Wakil Ketua'],
        ];

        return view('tsaqib.labor-pai', compact('visiMisi', 'pembina', 'pengurusSiswa'));
    }
}
