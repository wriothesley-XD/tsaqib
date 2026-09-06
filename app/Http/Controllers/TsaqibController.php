<?php

namespace App\Http\Controllers;

use App\Models\GuruProfile;
use App\Models\Modul;
use App\Models\Setting;
use App\Models\Tugas;

class TsaqibController extends Controller
{
    /**
     * Gate akses penuh halaman Modul & Tugas: siswa terverifikasi, atau guru/admin.
     * Belum terverifikasi → view tetap dirender (bukan 403) dengan panel gate.
     */
    private function isVerified(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->is_verified_student || ! empty($user->nisn) || in_array($user->role, ['admin', 'guru'], true));
    }

    /**
     * Profil Laboratorium & Guru (publik, read-only).
     * Route: GET /laboratorium-pai/profil
     */
    public function laborProfil()
    {
        $visiMisi = [
            'visi' => 'Visi FSI belum diisi — tunggu data resmi.',
            'misi' => [
                'Misi 1 — belum diisi',
                'Misi 2 — belum diisi',
                'Misi 3 — belum diisi',
            ],
        ];

        $gurus = GuruProfile::with('user')->latest()->get();

        return view('tsaqib.labor-profil', compact('visiMisi', 'gurus'));
    }

    /**
     * Modul Pembelajaran (PDF) — penuh hanya untuk siswa terverifikasi/guru.
     * Filter kelas+kategori+search dikerjakan client-side (volume data kelas
     * sekolah kecil; tanpa endpoint AJAX).
     * Route: GET /laboratorium-pai/modul
     */
    public function laborModul()
    {
        $verified = $this->isVerified();
        $moduls = $verified
            ? Modul::with('user')->latest()->get()
            : collect();

        return view('tsaqib.labor-modul', compact('moduls', 'verified'));
    }

    /**
     * Tugas Siswa — penuh hanya untuk siswa terverifikasi/guru.
     * Urut deadline terdekat di atas (NULL paling akhir).
     * Route: GET /laboratorium-pai/tugas
     */
    public function laborTugas()
    {
        $verified = $this->isVerified();
        $tugas = $verified
            ? Tugas::with('user')->orderByRaw('deadline IS NULL, deadline')->get()
            : collect();

        return view('tsaqib.labor-tugas', compact('tugas', 'verified'));
    }

    /**
     * Labor PAI: Visi-Misi FSI + Struktur Organisasi (Pembina & Siswa).
     * Konten dinamis terhubung dengan pengaturan admin.
     */
    public function laborPai()
    {
        $visiMisi = [
            'visi' => 'Mewujudkan generasi muda Muslim SMAN 1 Bukittinggi yang berkarakter Rabbani, berintelektual unggul, dan berakhlaqul karimah.',
            'misi' => [
                'Menyelenggarakan kajian dan pembinaan keislaman yang komprehensif berlandaskan Al-Qur\'an dan Sunnah.',
                'Mengembangkan khazanah literasi dan riset keagamaan melalui ekosistem Laboratorium PAI modern.',
                'Menumbuhkan ukhuwah islamiyah dan kepedulian sosial di lingkungan sekolah dan masyarakat.',
            ],
        ];

        $pembina = [
            ['nama' => 'Dewan Guru Pembina PAI', 'jabatan' => 'Pembina FSI TSAQIB'],
        ];

        $pengurusSiswa = [
            ['nama' => 'Ketua Umum FSI', 'jabatan' => 'Ketua Umum FSI TSAQIB'],
            ['nama' => 'Wakil Ketua', 'jabatan' => 'Wakil Ketua'],
        ];

        // URL flipbook "Profil TSAQIB" (diisi admin via tabel settings, key: profil_buku_tsaqib_url).
        $profilTsaqibUrl = Setting::getByKey('profil_buku_tsaqib_url')
            ?? Setting::getByKey('profil_tsaqib_url', 'https://heyzine.com/flip-book/bdf3f31765.html');

        $profilBukuPdf = Setting::getByKey('profil_buku_tsaqib_pdf');
        $monevPdf = Setting::getByKey('monev_internal_pdf');
        $strukturPembinaImg = Setting::getByKey('struktur_organisasi_pembina');
        $strukturSiswaImg = Setting::getByKey('struktur_organisasi_siswa');

        // Cover thumbnail flipbook: diturunkan dari URL flipbook Heyzine.
        $coverUrl = null;
        if (preg_match('#^(https?://heyzine\.com/flip-book/)([^/]+)\.html$#i', $profilTsaqibUrl, $m)) {
            $coverUrl = $m[1].'cover/'.$m[2].'.jpg';
        }

        return view('tsaqib.labor-pai', compact(
            'visiMisi', 'pembina', 'pengurusSiswa', 'profilTsaqibUrl', 'coverUrl',
            'profilBukuPdf', 'monevPdf', 'strukturPembinaImg', 'strukturSiswaImg'
        ));
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
