<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        $post = $this->route('post');
        $user = auth()->user();
        
        // Admin and editors can edit any post
        if ($user->isEditor()) {
            return true;
        }
        
        // Authors can only edit their own posts
        return $user->id === $post->user_id;
    }

    public function rules()
    {
        $postId = $this->route('post')->id;
        
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $postId,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'scheduled_at' => 'nullable|date|after:now',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
        ];
    }
}