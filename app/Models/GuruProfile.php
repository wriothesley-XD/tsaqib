<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'mapel_pengampu',
        'kelas_diampu',
        'foto_path',
        'wa_number',
    ];

    protected $casts = [
        'kelas_diampu' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
