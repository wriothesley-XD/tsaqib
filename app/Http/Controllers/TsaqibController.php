<?php

namespace App\Http\Controllers;

use App\Models\Setting;

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

        // URL flipbook "Profil TSAQIB" (diisi admin via tabel settings, key: profil_tsaqib_url).
        // Default ke flipbook Heyzine saat setting belum diisi.
        $profilTsaqibUrl = Setting::getByKey('profil_tsaqib_url', 'https://heyzine.com/flip-book/8e0a75dc7f.html');

        // Cover thumbnail flipbook: diturunkan dari URL flipbook Heyzine.
        //   https://heyzine.com/flip-book/{id}.html → https://heyzine.com/flip-book/cover/{id}.jpg
        // Bila URL bukan format Heyzine yang dikenali → null (view fallback ke ikon buku).
        $coverUrl = null;
        if (preg_match('#^(https?://heyzine\.com/flip-book/)([^/]+)\.html$#i', $profilTsaqibUrl, $m)) {
            $coverUrl = $m[1] . 'cover/' . $m[2] . '.jpg';
        }

        return view('tsaqib.labor-pai', compact('visiMisi', 'pembina', 'pengurusSiswa', 'profilTsaqibUrl', 'coverUrl'));
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
