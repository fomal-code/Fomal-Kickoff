<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    // Helper methods
    public function postsCount()
    {
        return $this->posts()->count();
    }
}