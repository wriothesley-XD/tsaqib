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