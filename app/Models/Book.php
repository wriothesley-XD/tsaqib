<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
