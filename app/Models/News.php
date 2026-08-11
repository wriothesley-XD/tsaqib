<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    /**
     * Laravel default plural "news" → "news" (uncountable) cocok dengan nama
     * tabel yang kita buat di migration, tapi eksplisit agar tidak ambigu.
     */
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Hanya berita yang sudah terbit (published_at di masa lalu).
     * Draf (NULL / di masa depan) tidak tampil ke publik.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
