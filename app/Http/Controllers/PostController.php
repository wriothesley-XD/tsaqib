<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Repost;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Simpan postingan baru beserta lampiran media (foto / video).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'community_slug' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'array', 'max:6'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,webm', 'max:30720'],
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'community_slug' => $validated['community_slug'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        foreach ($this->collectMedia($request) as $row) {
            $post->media()->create($row);
        }

        return redirect()->back()->with('success', 'Postingan berhasil diterbitkan!');
    }

    /**
     * Update postingan milik anggota sendiri atau admin.
     * Media diganti seluruhnya hanya bila ada upload baru (replace-all).
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk mengedit postingan ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'array', 'max:6'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,webm', 'max:30720'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('media')) {
            $this->deleteMediaFiles($post);
            $post->media()->delete();
            foreach ($this->collectMedia($request) as $row) {
                $post->media()->create($row);
            }
        }

        return redirect()->back()->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Hapus postingan milik anggota sendiri atau admin (beserta semua filenya).
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk menghapus postingan ini.');
        }

        $this->deleteMediaFiles($post);
        $post->reports()->delete(); // hapus laporan terkait (polymorphic, tidak ada cascade DB)
        $post->delete(); // baris post_media ikut terhapus via cascade FK

        return redirect()->back()->with('success', 'Postingan berhasil dihapus!');
    }

    /**
     * Validasi & simpan media dari request -> array row untuk Post::media()->create().
     * Aturan XOR: maks 6 foto (jpg/png/webp, ≤3 MB) ATAU 1 video (mp4/webm, ≤30 MB),
     * tidak boleh campur. Validasi mimes & plafon 30 MB sudah dilakukan oleh validate().
     */
    private function collectMedia(Request $request): array
    {
        $files = $request->file('media', []);
        $files = array_values(array_filter(is_array($files) ? $files : [], fn ($f) => $f && $f->isValid()));
        if (! $files) {
            return [];
        }

        $images = [];
        $videos = [];
        foreach ($files as $f) {
            $isVideo = str_starts_with($f->getMimeType(), 'video/');
            if ($isVideo) {
                if ($f->getSize() > 30 * 1024 * 1024) {
                    throw ValidationException::withMessages(['media' => 'Video maksimal 30 MB.']);
                }
                $videos[] = $f;
            } else {
                if ($f->getSize() > 3 * 1024 * 1024) {
                    throw ValidationException::withMessages(['media' => 'Setiap foto maksimal 3 MB.']);
                }
                $images[] = $f;
            }
        }

        if ($images && $videos) {
            throw ValidationException::withMessages(['media' => 'Pilih hanya foto atau video, tidak boleh dicampur.']);
        }
        if (count($videos) > 1) {
            throw ValidationException::withMessages(['media' => 'Maksimal 1 video per postingan.']);
        }
        if (count($images) > 6) {
            throw ValidationException::withMessages(['media' => 'Maksimal 6 foto per postingan.']);
        }

        $rows = [];
        foreach (array_merge($videos, $images) as $i => $f) {
            $isVideo = str_starts_with($f->getMimeType(), 'video/');
            $rows[] = [
                'path' => $f->store($isVideo ? 'posts/videos' : 'posts/images', 'public'),
                'type' => $isVideo ? 'video' : 'image',
                'order' => $i,
            ];
        }

        return $rows;
    }

    /**
     * Hapus semua file media post dari disk 'public' (baris DB ditangani terpisah).
     */
    private function deleteMediaFiles(Post $post): void
    {
        foreach ($post->media as $media) {
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
        }
    }

    /**
     * Upvote / downvote sebuah post via AJAX.
     * Klik jenis yang sama -> batal (toggle off). Klik jenis berbeda -> switch.
     * Counter cache (upvotes/downvotes) dijaga sinkron di dalam transaksi.
     * Mengembalikan JSON: { upvotes, downvotes, my_vote }.
     */
    public function vote(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:up,down'],
        ]);

        $type = $validated['type'];
        // Type vote ('up'|'down') != nama kolom counter ('upvotes'|'downvotes').
        // Petakan eksplisit agar increment()/decrement() mengenai kolom yang benar.
        $column = $type === 'up' ? 'upvotes' : 'downvotes';
        $userId = Auth::id();

        DB::transaction(function () use ($post, $type, $column, $userId) {
            $existing = Vote::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->first();

            if (! $existing) {
                // Belum pernah vote -> tambah suara baru.
                Vote::create([
                    'user_id' => $userId,
                    'post_id' => $post->id,
                    'type' => $type,
                ]);
                $post->increment($column);
            } elseif ($existing->type === $type) {
                // Jenis sama -> batalkan suara (toggle off).
                $existing->delete();
                $post->decrement($column);
            } else {
                // Jenis berbeda -> pindah suara (up <-> down). Kolom lama dikurangi,
                // kolom baru ditambah.
                $previousColumn = $existing->type === 'up' ? 'upvotes' : 'downvotes';
                $existing->update(['type' => $type]);
                $post->decrement($previousColumn);
                $post->increment($column);
            }
        });

        $post->refresh();

        return response()->json([
            'upvotes' => (int) $post->upvotes,
            'downvotes' => (int) $post->downvotes,
            'my_vote' => Vote::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->value('type'),
        ]);
    }

    /**
     * Halaman detail satu postingan (kartu penuh + komentar).
     * Route: GET /komunitas/post/{post} (publik).
     */
    public function show(Post $post)
    {
        $post->load(['user', 'media', 'comments.user']);
        $post->loadCount(['comments']);

        if (Auth::check()) {
            $userId = Auth::id();
            $post->load([
                'votes' => fn ($q) => $q->where('user_id', $userId)->select(['post_id', 'type']),
                'savedBy' => fn ($q) => $q->where('user_id', $userId)->select(['post_id']),
            ]);
        }

        return view('komunitas.post', ['post' => $post]);
    }

    /**
     * Tambah komentar via AJAX. Mengembalikan HTML partial _comment yang
     * langsung di-prepend ke daftar komentar (tanpa reload).
     */
    public function storeComment(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);
        $comment->load('user');

        return response()->json([
            'html' => view('komunitas._comment', ['c' => $comment])->render(),
        ]);
    }

    /**
     * Hapus komentar milik sendiri atau admin (AJAX).
     */
    public function destroyComment(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk menghapus komentar ini.');
        }

        $comment->reports()->delete(); // hapus laporan terkait (polymorphic)
        $comment->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Repost / batal repost via AJAX. Satu per user (unique constraint).
     * Mengembalikan JSON: { reposts, reposted }.
     */
    public function repost(Post $post): JsonResponse
    {
        $userId = Auth::id();

        $existing = Repost::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        $reposted = ! $existing;
        if ($existing) {
            $existing->delete();
        } else {
            Repost::create([
                'user_id' => $userId,
                'post_id' => $post->id,
            ]);
        }

        return response()->json([
            'reposts' => Repost::where('post_id', $post->id)->count(),
            'reposted' => $reposted,
        ]);
    }

    /**
     * Simpan / batal simpan sebuah post (bookmark "Tersimpan") via AJAX.
     * Satu baris per user-post (unique constraint di pivot post_user).
     * Disimpan server-side di pivot post_user — bukan localStorage — sehingga
     * sinkron antar perangkat dan muncul di tab "Tersimpan" profil.
     * Mengembalikan JSON: { saved, saves }.
     */
    public function toggleSave(Post $post): JsonResponse
    {
        $user = Auth::user();
        $saved = $user->savedPosts()->toggle([$post->id]);

        return response()->json([
            'saved' => ! empty($saved['attached']),
            'saves' => $post->savedBy()->count(),
        ]);
    }
}
