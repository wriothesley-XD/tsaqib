<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'mapel_pengampu',
        'kelas_diampu',
        'foto_path',
        'wa_number',
        'email',
        'deskripsi',
        'facebook_url',
        'instagram_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kelas_diampu' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama guru — fallback ke nama relasi User jika nama langsung kosong.
     */
    public function getNamaAttribute(?string $value): string
    {
        return $value ?: ($this->user?->name ?? 'Guru PAI');
    }

    /**
     * Email guru — fallback ke email relasi User jika kosong.
     */
    public function getEmailAttribute(?string $value): ?string
    {
        return $value ?: $this->user?->email;
    }
}
