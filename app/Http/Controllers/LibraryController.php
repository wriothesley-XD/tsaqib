<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tugas;

class LibraryController extends Controller
{
    public function index()
    {
        $books = Book::where('is_visible', true)->latest()->get();
        $tugas = Tugas::latest()->get();

        return view('perpustakaan', compact('books', 'tugas'));
    }
}
