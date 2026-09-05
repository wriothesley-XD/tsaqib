<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modul extends Model
{
    protected $table = 'moduls';

    protected $fillable = [
        'user_id',
        'judul',
        'kategori',
        'target_kelas',
        'file_path',
        'deskripsi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
