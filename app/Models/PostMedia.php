<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    protected $fillable = [
        'post_id',
        'path',
        'type', // 'image' | 'video'
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * URL pubikut (disk 'public' disajikan via symlink public/storage).
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->path));
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }
}
