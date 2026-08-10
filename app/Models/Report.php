<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'status',
    ];

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    /** Hanya laporan yang belum ditangani. */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
