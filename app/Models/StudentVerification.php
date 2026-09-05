<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentVerification extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'kelas',
        'kts_photo_path',
        'status',
    ];

    /** Status approval: pending | approved | rejected. */
    public const STATUSES = ['pending', 'approved', 'rejected'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
