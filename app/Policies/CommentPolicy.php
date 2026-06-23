<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user)
    {
        return $user->isEditor();
    }

    public function approve(User $user, Comment $comment)
    {
        return $user->isEditor();
    }

    public function update(User $user, Comment $comment)
    {
        // Users can edit their own comments
        if ($comment->user_id === $user->id) {
            return true;
        }
        
        // Editors can edit any comment
        return $user->isEditor();
    }

    public function delete(User $user, Comment $comment)
    {
        // Users can delete their own comments
        if ($comment->user_id === $user->id) {
            return true;
        }
        
        // Editors can delete any comment
        return $user->isEditor();
    }
}