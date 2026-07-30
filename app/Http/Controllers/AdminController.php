app/Http/Controllers/AdminController.php<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   // Menampilkan daftar buku
    public function index()
    {
        $books = Book::latest()->get();
        return view('admin.index', compact('books'));
    }

    // Menampilkan form tambah buku
    public function create()
    {
        return view('admin.create');
    }

    // Menyimpan data buku baru
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'author'     => 'required|string|max:255',
            'cover_path' => 'nullable|url',
            'pdf_path'   => 'required|url',
        ]);

        Book::create($request->all());

        return redirect()->route('admin.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // Menampilkan form edit buku
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.edit', compact('book'));
    }

    // Memperbarui data buku
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'author'     => 'required|string|max:255',
            'cover_path' => 'nullable|url',
            'pdf_path'   => 'required|url',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Buku berhasil diperbarui!');
    }

    // Menghapus data buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.index')->with('success', 'Buku berhasil dihapus!');
    }
}
