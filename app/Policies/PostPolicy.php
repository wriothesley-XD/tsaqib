<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any posts.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the post.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create posts
        return true;
    }

    /**
     * Determine whether the user can update the post.
     * User hanya bisa edit postingan MILIKNYA SENDIRI atau admin.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the post.
     * User hanya bisa hapus postingan MILIKNYA SENDIRI atau admin.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can manage (update/delete) the post.
     * Convenience method for UI checks.
     */
    public function manage(User $user, Post $post): bool
    {
        return $this->update($user, $post) || $this->delete($user, $post);
    }
}
