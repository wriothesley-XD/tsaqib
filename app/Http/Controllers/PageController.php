<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;

class PageController extends Controller
{
=======
class PageController extends Controller
{
    /**
     * Halaman Peta (entry point utama, publik, tanpa login).
     */
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf
    public function home()
    {
        return view('home');
    }

<<<<<<< HEAD
    public function community()
    {
        return view('community');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

=======
    /**
     * Pustaka FSI (publik, tanpa login).
     */
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf
    public function perpustakaan()
    {
        return view('perpustakaan');
    }
<<<<<<< HEAD
}
=======

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
}
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf
