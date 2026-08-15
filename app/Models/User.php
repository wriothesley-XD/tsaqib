<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'selected_community',
        'bio',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Postingan milik user (untuk tab & jumlah postingan di halaman profil).
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /**
     * Komentar milik user (untuk tab & jumlah komentar di halaman profil).
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    /**
     * Buku pribadi user di Perpustakaan — mencakup "My Collection" dan "Saved",
     * dibedakan via kolom pivot `type` ('collection' | 'saved'). Filter daftar
     * tertentu: $user->savedBooks()->wherePivot('type', 'collection').
     */
    public function savedBooks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Book::class, 'book_user')
            ->withPivot('type')
            ->withTimestamps();
    }

    /**
     * Postingan yang disimpan/bookmark user (tab "Tersimpan" di profil).
     */
    public function savedPosts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Post::class, 'post_user')
            ->withTimestamps();
    }

    /**
     * User yang dia ikuti. (Saya = follower, mereka = following.)
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'following_id')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    /**
     * Pengikut user ini. (Mereka = follower, saya = following.)
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'following_id', 'follower_id')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    /**
     * Daftar path avatar bawaan (preset) di public/assets/images/avatar/*,
     * relatif terhadap document-root (mis. 'assets/images/avatar/guy.webp').
     *
     * Memoized + sorted agar urutan stabil antar-render. Dipakai oleh
     * getAvatar(), validasi (Rule::in), dan komponen <x-avatar-picker>.
     * Tambahkan file avatar baru ke folder itu dan otomatis muncul di grid.
     *
     * @return list<string>
     */
    public static function presetAvatars(): array
    {
        static $presets = null;

        if ($presets === null) {
            $dir = public_path('assets/images/avatar');
            $presets = [];
            foreach ((array) glob($dir.'/*') as $file) {
                if (is_file($file)) {
                    $presets[] = 'assets/images/avatar/'.basename($file);
                }
            }
            sort($presets);
        }

        return $presets;
    }

    /**
     * Centralized avatar URL. Precedence:
     *  1) Foto yang di-UPLOAD (profile_photo_path, disimpan di disk 'public');
     *  2) Avatar BAWAAN yang dipilih user (kolom `avatar`, dipilih lewat
     *     <x-avatar-picker> di register / edit profil);
     *  3) Deterministic preset via id % count — fallback bagi user lama yang
     *     belum memilih, agar navbar & profil selalu cocok & stabil;
     *  4) Default komunitas bila tak ada preset sama sekali.
     */
    public function getAvatar(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        if ($this->avatar) {
            return asset($this->avatar);
        }

        $presets = self::presetAvatars();
        if (! empty($presets)) {
            return asset($presets[$this->id % count($presets)]);
        }

        return asset('images/community-avatar/default.svg');
    }

    // NOTE (security fix): hook `booted()` yang lama otomatis menjadikan
    // SIAPA PUN yang mendaftar dengan email 'test1@gmail.com' atau
    // 'admin@fsi.sch.id' sebagai admin. Karena /register terbuka untuk
    // publik tanpa verifikasi domain sekolah, ini adalah backdoor —
    // penyerang tinggal daftar akun baru pakai email tsb untuk dapat akses
    // admin penuh. Hook ini sudah dihapus.
    //
    // Untuk menetapkan admin sekarang, lakukan manual di database, mis.
    // lewat tinker:
    //   php artisan tinker
    //   >>> User::where('email', 'admin@fsi.sch.id')->update(['role' => 'admin']);
    // atau tambahkan seeder khusus admin yang HANYA dijalankan di server,
    // bukan logic otomatis berbasis email yang bisa didaftarkan siapa saja.
}
