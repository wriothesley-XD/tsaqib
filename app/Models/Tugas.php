<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $fillable = [
        'user_id',
        'judul_tugas',
        'deskripsi',
        'deadline',
        'target_kelas',
        'link_google_classroom',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
