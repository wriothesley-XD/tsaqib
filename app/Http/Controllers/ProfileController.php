<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Profil sendiri (GET /profile). Data dibangun bersama ProfileController@show
     * lewat profileViewData() agar UI ikut/follow & tab aktivitas identik.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', $this->profileViewData($request, $request->user()));
    }

    /**
     * Profil user mana pun (GET /profile/{user}). Bisa sendiri atau orang lain;
     * view menentukan tombol Edit (owner) vs Follow (orang lain) via $isOwner.
     */
    public function show(Request $request, User $user): View
    {
        return view('profile.edit', $this->profileViewData($request, $user));
    }

    /**
     * Update data profile (name, email, bio, avatar). Avatar disimpan ke disk
     * 'public' (avatars/...) → profile_photo_path → User::getAvatar(). Mendukung
     * submit AJAX (JSON) maupun form biasa (redirect + flash).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('avatar')) {
            // Ganti dengan foto yang di-UPLOAD: hapus upload lama, lalu simpan
            // yang baru. Upload selalu diutamakan oleh getAvatar(), jadi bersihkan
            // pilihan preset agar keduanya tak menyimpan kondisi ganda.
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = null;
        } elseif ($preset = $request->input('preset_avatar')) {
            // Beralih ke avatar BAWAAN: bersihkan upload lama (file + kolom) agar
            // getAvatar() menampilkan preset, bukan foto yang pernah di-upload.
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = null;
            $user->avatar = $preset;
        }

        // Ganti komunitas (selected_community). Slug divalidasi sudah pasti
        // salah satu dari config('komunitas.daftar') — daftar yang sama dengan
        // /komunitas/{slug} & select-role. Pilihan kosong dibolehkan (nullable),
        // tapi hidden input selalu terkirim, jadi cukup tulis saat field ada.
        if ($request->has('community_slug')) {
            $user->selected_community = $request->validated('community_slug');
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $user->banner_path = $bannerPath;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Profil berhasil diperbarui.',
                'avatar' => $user->getAvatar() . '?v=' . ($user->updated_at?->timestamp ?? ''),
                'banner' => $user->banner_path
                    ? asset('storage/' . $user->banner_path) . '?v=' . ($user->updated_at?->timestamp ?? '')
                    : null,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * DELETE /profile/avatar — hapus foto profil kustom (upload MAUPUN preset),
     * kembalikan ke avatar default (preset deterministik via getAvatar()).
     *
     * Menghapus file upload di disk 'public' bila ada, lalu mengosongkan
     * `profile_photo_path` & `avatar`. AJAX → JSON berisi URL avatar baru agar
     * pratinjau & kartu profil ter-update tanpa reload.
     */
    public function destroyAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = null;
        $user->avatar = null;
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Foto profil direset ke default.',
            'avatar' => $user->getAvatar() . '?v=' . ($user->updated_at?->timestamp ?? ''),
        ]);
    }

    /**
     * Hapus akun user (hard delete). Mengapa begitu banyak langkah:
     *
     *  - Validasi password (rule current_password) sebelum melakukan apa pun.
     *  - Larang admin terakhir menghapus dirinya sendiri (jangan biarkan
     *    sistem tanpa admin sama sekali).
     *  - Kumpulkan path file (avatar, banner, media postingan) SEBELUM baris
     *    DB terhapus — setelah itu path sudah tak bisa diambil.
     *  - Bungkus penghapusan data dalam DB::transaction() agar atomik: bila
     *    gagal di tengah, tidak ada data setengah-hapus.
     *    CATATAN SKEMA: posts.user_id memakai nullOnDelete (bukan cascade),
     *    jadi postingan user HARUS dihapus eksplisit — sisanya (votes, reposts,
     *    comments, follows, post_user, book_user, reports) ikut cascade saat
     *    user dihapus. Penghapusan post juga men-cascade comments/media/votes/
     *    reposts yang merujuk post tersebut.
     *  - Hapus file di disk 'public' di luar transaksi (filesystem tak
     *    transaksional) — hanya dijalankan bila transaksi sukses.
     *  - Logout, invalidate session, redirect ke beranda dengan flash.
     *
     * registrations.user_id nullable → data Open Recruitment tetap aman.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Lindungi admin terakhir.
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return Redirect::back()->withErrors([
                'password' => 'Anda adalah admin terakhir di sistem. Tambahkan admin lain sebelum menghapus akun Anda sendiri.',
            ]);
        }

        // Path file (disk 'public') — diambil sebelum baris DB terhapus.
        $postIds = $user->posts()->pluck('id');
        $mediaPaths = $postIds->isNotEmpty()
            ? DB::table('post_media')->whereIn('post_id', $postIds)->pluck('path')
            : collect();
        $legacyImagePaths = $postIds->isNotEmpty()
            ? DB::table('posts')->whereIn('id', $postIds)->whereNotNull('image_path')->pluck('image_path')
            : collect();
        $avatar = $user->profile_photo_path;
        $banner = $user->banner_path;

        // Penghapusan atomik. posts.user_id = nullOnDelete → hapus eksplisit;
        // sisa relasi ikut cascade dari $user->delete().
        DB::transaction(function () use ($user, $postIds) {
            if ($postIds->isNotEmpty()) {
                DB::table('posts')->whereIn('id', $postIds)->delete();
            }

            $user->delete();
        });

        // Hapus file (hanya dicapai bila transaksi sukses).
        $disk = Storage::disk('public');
        foreach ($mediaPaths->merge($legacyImagePaths) as $p) {
            if ($p && $disk->exists($p)) {
                $disk->delete($p);
            }
        }
        if ($avatar && $disk->exists($avatar)) {
            $disk->delete($avatar);
        }
        if ($banner && $disk->exists($banner)) {
            $disk->delete($banner);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'account-deleted');
    }

    // ========================================================================
    // FOLLOW SYSTEM
    // ========================================================================

    /** POST /profile/{user}/follow — ikuti user. Dilarang ikut diri sendiri. */
    public function follow(Request $request, User $user): JsonResponse
    {
        $me = $request->user();

        if ($me->is($user)) {
            return response()->json(['message' => 'Tidak bisa mengikuti diri sendiri.'], 422);
        }

        $me->following()->syncWithoutDetaching([$user->id]);

        return $this->followJson($user, true);
    }

    /** DELETE /profile/{user}/unfollow — berhenti ikut. */
    public function unfollow(Request $request, User $user): JsonResponse
    {
        $request->user()->following()->detach([$user->id]);

        return $this->followJson($user, false);
    }

    /** GET /profile/{user}/followers — JSON cursor-paginated (id, name, avatar, url). */
    public function followers(Request $request, User $user): JsonResponse
    {
        return $this->peopleJson($user->followers());
    }

    /** GET /profile/{user}/following — JSON cursor-paginated. */
    public function following(Request $request, User $user): JsonResponse
    {
        return $this->peopleJson($user->following());
    }

    // ========================================================================
    // ACTIVITY TABS
    // ========================================================================

    /**
     * GET /profile/{user}/tabs/{tab} — cursor-paginated JSON (dipakai bila ingin
     * infinite scroll; view redesign memakai batch render-server + "See All").
     * tab ∈ posts|comments|saved|books. saved/books privat (hanya owner, 403).
     */
    public function tabs(Request $request, User $user, string $tab): JsonResponse
    {
        $owner = $request->user()->is($user);

        if (in_array($tab, ['saved', 'books'], true) && ! $owner) {
            abort(403);
        }

        $pg = $this->tabQuery($user, $tab)->cursorPaginate(10);

        return response()->json([
            'items' => $this->mapCollection($pg->items(), $tab, $owner),
            'next_cursor' => $pg->nextCursor()?->encode(),
            'has_more' => $pg->hasMorePages(),
        ]);
    }

    /**
     * GET /profile/{user}/list/{tab} — halaman HTML full-list dengan pagination
     * bernomor (tujuan tombol "See All"). saved/books privat.
     */
    public function list(Request $request, User $user, string $tab): View
    {
        $owner = $request->user()->is($user);

        if (in_array($tab, ['saved', 'books'], true) && ! $owner) {
            abort(403);
        }

        $titles = [
            'posts' => 'Postingan',
            'comments' => 'Komentar',
            'saved' => 'Tersimpan',
            'books' => 'Koleksi Buku',
        ];

        $pg = $this->tabQuery($user, $tab)->paginate(12)->withQueryString();

        return view('profile.list', [
            'user' => $user,
            'isOwner' => $owner,
            'tab' => $tab,
            'tabTitle' => $titles[$tab] ?? 'Item',
            'items' => $this->mapCollection($pg->items(), $tab, $owner),
            'page' => $pg,
        ]);
    }

    // ========================================================================
    // Shared helpers
    // ========================================================================

    /**
     * Semua data untuk view profil (sendiri / orang lain): counts, status follow,
     * batch pertama tiap tipe konten (+ total untuk "See All" vs "No more."),
     * serta buku tersimpan dikelompokkan per kategori (tab Books / Book Collection).
     */
    protected function profileViewData(Request $request, User $user): array
    {
        $owner = $request->user()->is($user);
        $batch = 6;

        $postsBatch = $this->mapCollection(
            $user->posts()->orderBy('id', 'desc')->take($batch)->get(),
            'posts', $owner
        );
        $commentsBatch = $this->mapCollection(
            $user->comments()->with('post')->orderBy('id', 'desc')->take($batch)->get(),
            'comments', $owner
        );

        $savedBatch = [];
        $booksGrouped = collect();

        if ($owner) {
            $savedBatch = $this->mapCollection(
                $user->savedPosts()->orderBy('posts.id', 'desc')->take($batch)->get(),
                'saved', $owner
            );

            // Buku dari koleksi (type=collection), dikelompokkan per kategori.
            $booksGrouped = $user->savedBooks()->wherePivot('type', 'collection')
                ->orderByDesc('books.id')
                ->get()
                ->groupBy(fn ($b) => ucfirst($b->category ?? 'Lainnya'))
                ->map(fn ($group) => $group->map(fn ($b) => [
                    'title' => $b->title,
                    'author' => $b->author ?? 'Tim PAI',
                    'link' => $b->pdf_path ? asset('storage/' . $b->pdf_path) : '#',
                    'cover' => $b->cover_image ? asset('storage/' . $b->cover_image) : null,
                ])->values()->all());
        }

        $postsTotal = $user->posts()->count();
        $commentsTotal = $user->comments()->count();
        $savedTotal = $owner ? $user->savedPosts()->count() : 0;
        $booksTotal = $booksGrouped->flatten(1)->count();

        return [
            'user' => $user,
            'isOwner' => $owner,
            'isFollowing' => $request->user()->following()->where('following_id', $user->id)->exists(),

            'postsCount' => $postsTotal,
            'commentsCount' => $commentsTotal,
            'savedCount' => $savedTotal,
            'booksCount' => $booksTotal,

            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'recentFollowers' => $user->followers()->latest('follows.created_at')->limit(4)->get(),

            // batch + total untuk list "Recent activity" (See All / No more.)
            'postsBatch' => $postsBatch,
            'postsTotal' => $postsTotal,
            'commentsBatch' => $commentsBatch,
            'commentsTotal' => $commentsTotal,
            'savedBatch' => $savedBatch,
            'savedTotal' => $savedTotal,

            // buku dikelompokkan per kategori (Books / Book Collection)
            'booksGrouped' => $booksGrouped,
        ];
    }

    /**
     * Query builder tiap tab (dipakai endpoint tabs/list). Diurutkan per `id`
     * (unik & accessible) — syarat cursorPaginate().
     */
    protected function tabQuery(User $user, string $tab)
    {
        return match ($tab) {
            'posts' => $user->posts()->orderBy('id', 'desc'),
            'comments' => $user->comments()->with('post')->orderBy('id', 'desc'),
            'saved' => $user->savedPosts()->orderBy('posts.id', 'desc'),
            'books' => $user->savedBooks()->wherePivot('type', 'collection')->orderBy('books.id', 'desc'),
            default => abort(404),
        };
    }

    /** Peta kumpulan model (Collection/array) ke bentuk unified untuk partial/JS. */
    protected function mapCollection($models, string $tab, bool $owner): array
    {
        return collect($models)
            ->map(function ($x) use ($tab, $owner) {
                return match ($tab) {
                    'posts' => $this->mapPost($x, $owner),
                    'comments' => $this->mapComment($x),
                    'saved' => $this->mapSavedPost($x),
                    'books' => $this->mapBook($x),
                    default => null,
                };
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function mapPost($p, bool $deletable): array
    {
        return [
            'id' => $p->id,
            'icon' => 'fa-feather',
            'iconGold' => false,
            'thumb' => null,
            'eyebrow' => ($p->community_slug ?? 'komunitas') . ' • ' . $p->created_at?->diffForHumans(),
            'title' => $p->title ?? '(tanpa judul)',
            'excerpt' => Str::limit($p->content ?? '', 120),
            'link' => route('komunitas.post.show', $p->id),
            'deletable' => $deletable,
        ];
    }

    protected function mapComment($c): array
    {
        $where = ! empty($c->post) ? 'di "' . Str::limit($c->post->title, 30) . '"' : 'komentar';

        return [
            'id' => $c->id,
            'icon' => 'fa-comment-dots',
            'iconGold' => true,
            'thumb' => null,
            'eyebrow' => $where . ' • ' . $c->created_at?->diffForHumans(),
            'title' => Str::limit($c->body ?? '', 90),
            'excerpt' => null,
            'link' => ! empty($c->post) ? route('komunitas.post.show', $c->post->id) : '#',
            'deletable' => false,
        ];
    }

    protected function mapSavedPost($p): array
    {
        $savedAt = $p->pivot->created_at ?? $p->created_at;

        return [
            'id' => $p->id,
            'icon' => 'fa-bookmark',
            'iconGold' => false,
            'thumb' => null,
            'eyebrow' => ($p->community_slug ?? 'komunitas') . ' • disimpan ' . $savedAt?->diffForHumans(),
            'title' => $p->title ?? '(tanpa judul)',
            'excerpt' => Str::limit($p->content ?? '', 120),
            'link' => route('komunitas.post.show', $p->id),
            'deletable' => false,
        ];
    }

    protected function mapBook($b): array
    {
        return [
            'id' => $b->id,
            'icon' => 'fa-book',
            'iconGold' => true,
            'thumb' => $b->cover_image ? asset('storage/' . $b->cover_image) : null,
            'eyebrow' => ($b->category ?? 'buku') . ' • ' . ($b->author ?? 'Tim PAI'),
            'title' => $b->title,
            'excerpt' => null,
            'link' => $b->pdf_path ? asset('storage/' . $b->pdf_path) : '#',
            'deletable' => false,
        ];
    }

    /** JSON follow/unfollow: status baru + jumlah pengikut terkini. */
    protected function followJson(User $user, bool $following): JsonResponse
    {
        return response()->json([
            'following' => $following,
            'followerCount' => $user->followers()->count(),
        ]);
    }

    /** JSON cursor-paginated untuk modal pengikut/mengikuti. */
    protected function peopleJson($relation): JsonResponse
    {
        // Diurutkan per users.id (unik) — BUKAN alias follows.created_at — karena
        // cursorPaginate() menyisipkan kolom urut ke klausa WHERE, dan MySQL
        // melarang alias di WHERE.
        $pg = $relation->orderBy('users.id', 'desc')->cursorPaginate(20);

        $items = collect($pg->items())->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'avatar' => $u->getAvatar(),
            'profile_url' => route('profile.show', $u->id),
        ])->values()->all();

        return response()->json([
            'items' => $items,
            'next_cursor' => $pg->nextCursor()?->encode(),
            'has_more' => $pg->hasMorePages(),
        ]);
    }
}
