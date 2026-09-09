<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category',
        'cover_image',
        'pdf_path',
        'description',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /**
     * Hanya buku yang ditampilkan ke publik di Perpustakaan.
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * User yang menyimpan buku ini ke koleksi/tersimpan (pivot book_user.type).
     */
    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_user')
            ->withPivot('type')
            ->withTimestamps();
    }
}
