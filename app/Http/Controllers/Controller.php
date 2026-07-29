<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['nama' => 'Budi']);
    }

    public function community()
    {
        return view('community');
    }

    public function perpustakaan()
    {
        return view('perpustakaan');
    }
}
abstract class Controller
{
    //
}
