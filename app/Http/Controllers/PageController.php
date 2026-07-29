<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }
    public function community()
    {
        return view('community');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function perpustakaan()
    {
        return view('perpustakaan');
    }
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

