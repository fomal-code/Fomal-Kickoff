<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAny(User $user)
    {
        return $user->isEditor();
    }

    public function create(User $user)
    {
        return $user->isEditor();
    }

    public function update(User $user, Tag $tag)
    {
        return $user->isEditor();
    }

    public function delete(User $user, Tag $tag)
    {
        return $user->isEditor();
    }
}