<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    /**
     * Landing page — pulau TSAQIB.
     * Route: GET /
     */
    public function landing()
    {
        return view('landing');
    }

    /**
     * Halaman komunitas (dinamis, 1 template untuk 13 komunitas).
     * Route: GET /komunitas/{slug}
     */
    public function komunitasShow(string $slug)
    {
        $daftarKomunitas = Config::get('komunitas.daftar', []);

        $komunitas = collect($daftarKomunitas)
            ->firstWhere('slug', $slug);

        if (! $komunitas) {
            abort(Response::HTTP_NOT_FOUND, 'Komunitas tidak ditemukan.');
        }

        return view('komunitas.show', [
            'komunitas' => $komunitas,
        ]);
    }
}
