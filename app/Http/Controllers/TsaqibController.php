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

    /**
     * Informasi Kegiatan FSI — publik (baru).
     * Konten masih placeholder, sama seperti laborPai() di atas.
     */
    public function kegiatan()
    {
        $kegiatan = [
            ['nama' => 'Kegiatan 1 — belum diisi', 'tanggal' => null, 'deskripsi' => 'Menunggu data resmi.'],
        ];

        return view('tsaqib.kegiatan', compact('kegiatan'));
    }

    /**
     * Informasi Role (detail jabatan internal) — wajib login.
     * Beda dari laborPai(): ini detail per-jabatan (tugas, wewenang, dst),
     * bukan struktur formal FSI secara umum.
     */
    public function role()
    {
        $roles = [
            ['jabatan' => 'Jabatan 1 — belum diisi', 'tugas' => 'Menunggu data resmi.'],
        ];

        return view('tsaqib.role', compact('roles'));
    }
}
