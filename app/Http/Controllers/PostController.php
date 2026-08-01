<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Simpan postingan baru dari anggota komunitas.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'community_slug' => ['required', 'string', 'max:100'],
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['required', 'string', 'max:5000'],
            'image'          => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id'        => Auth::id(),
            'community_slug' => $validated['community_slug'],
            'title'          => $validated['title'],
            'content'        => $validated['content'],
            'image_path'     => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil diterbitkan!');
    }

    /**
     * Update postingan milik anggota sendiri.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        // Pastikan hanya pemilik postingan atau admin yang bisa edit
        if ($post->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk mengedit postingan ini.');
        }

        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $post->update([
            'title'   => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Hapus postingan milik anggota sendiri.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Pastikan hanya pemilik postingan atau admin yang bisa hapus
        if ($post->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk menghapus postingan ini.');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Postingan berhasil dihapus!');
    }
}
