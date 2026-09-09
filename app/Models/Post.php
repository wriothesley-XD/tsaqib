<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_slug',
        'title',
        'content',
        'image_path',
        'upvotes',
        'downvotes',
    ];

    protected function casts(): array
    {
        return [
            'upvotes' => 'integer',
            'downvotes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User yang menyimpan/mem-bookmark post ini (tab "Tersimpan" di profil).
     */
    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user')
            ->withTimestamps();
    }

    /**
     * Semua suara pada post ini. CATATAN: di feed di-eager-load HANYA untuk
     * user yang sedang login (lihat PageController::komunitasIndex), sehingga
     * $post->votes berisi 0 atau 1 item (suara user saat ini).
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Lampiran media post (foto/video), urut by `order`.
     * Di-eager-load di PageController::komunitasIndex bersama user & votes.
     */
    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Semua repost post ini. Di feed di-eager-load HANYA untuk user yang login
     * (lihat PageController) sehingga berisi 0/1 item (repost user saat ini).
     */
    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class);
    }

    /** Laporan konten (polymorphic, morph map 'post'). */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
