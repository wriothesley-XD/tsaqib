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
     * Centralized avatar URL. A real uploaded photo takes precedence;
     * otherwise deterministically pick from the preset set in
     * images/avatars/ (stable per user via id % count, so the navbar
     * and profile always match and the image never changes between renders).
     * Falls back to the default community avatar only if no presets exist.
     */
    public function getAvatar(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        // Memoized + sorted so the id-based pick is deterministic across renders.
        static $presets = null;
        if ($presets === null) {
            $dir = public_path('images/avatars');
            $presets = array_merge(
                glob($dir.'/*.jpg') ?: [],
                glob($dir.'/*.jpeg') ?: [],
                glob($dir.'/*.png') ?: [],
                glob($dir.'/*.webp') ?: []
            );
            sort($presets);
        }

        if (empty($presets)) {
            return asset('images/community-avatar/default.svg');
        }

        return asset('images/avatars/'.basename($presets[$this->id % count($presets)]));
    }

    /**
     * Model booted hook untuk secara otomatis menetapkan role 'admin'
     * untuk email test1@gmail.com dan admin@fsi.sch.id
     */
    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if ($user->email === 'test1@gmail.com' || $user->email === 'admin@fsi.sch.id') {
                $user->role = 'admin';
            }
        });
    }
}
