<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Morph map singkat untuk polymorphic reports (disimpan sbg 'post'/'comment',
        // bukan nama class penuh).
        Relation::morphMap([
            'post' => Post::class,
            'comment' => Comment::class,
        ]);
    }
}
