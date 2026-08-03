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
     * Get default community avatar URL or custom profile photo URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (isset($this->profile_photo_path) && $this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        $community = $this->selected_community ?? 'default';

        return asset('images/community-avatar/'.$community.'.png');
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
