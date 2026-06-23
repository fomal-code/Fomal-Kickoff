<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAuthor();
    }

    public function view(User $user, Post $post)
    {
        return $user->isAuthor();
    }

    public function create(User $user)
    {
        return $user->isAuthor();
    }

    public function update(User $user, Post $post)
    {
        // Admins and editors can edit any post
        if ($user->isEditor()) {
            return true;
        }
        
        // Authors can only edit their own posts
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post)
    {
        // Admins can delete any post
        if ($user->isAdmin()) {
            return true;
        }
        
        // Editors can delete any post
        if ($user->role === 'editor') {
            return true;
        }
        
        // Authors can only delete their own posts
        return $user->id === $post->user_id;
    }

    public function publish(User $user, Post $post)
    {
        // Only admins and editors can publish
        return $user->isEditor();
    }

    public function feature(User $user, Post $post)
    {
        // Only admins and editors can feature posts
        return $user->isEditor();
    }
}