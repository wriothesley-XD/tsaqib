<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Halaman Peta (entry point utama, publik, tanpa login).
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Pustaka FSI (publik, tanpa login).
     */
    public function perpustakaan()
    {
        return view('perpustakaan');
    }

    /**
     * TODO: masih placeholder publik, belum jelas apakah ini sama dengan
     * Dashboard TSAQIB (yang seharusnya wajib login) atau halaman terpisah.
     * Jangan hapus dulu sebelum dikonfirmasi ke tim, biar tidak ada route
     * yang tiba-tiba 404 buat orang lain yang lagi kerja paralel.
     */
    public function community()
    {
        return view('community');
    }

    /**
     * Dashboard TSAQIB (profile, logout, dst) — wajib login.
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
