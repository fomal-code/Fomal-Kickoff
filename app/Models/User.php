<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
    'role',
];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_links' => 'array',
    ];

    // Relationships
    public function posts()
    {
    return $this->hasMany(Post::class);
    }

    public function comments()
    {
    return $this->hasMany(Comment::class);
    }
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function postRevisions()
    {
        return $this->hasMany(PostRevision::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isSubscriber()
    {
        return $this->role === 'subscriber';
    }

    public function isEditor()
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function isAuthor()
    {
        return in_array($this->role, ['admin', 'editor', 'author']);
    }

    
}