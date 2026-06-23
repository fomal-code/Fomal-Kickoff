<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAuthor();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
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

    public function messages()
    {
        return [
            'title.required' => 'The post title is required.',
            'content.required' => 'The post content cannot be empty.',
            'status.in' => 'Invalid post status selected.',
            'featured_image.image' => 'The file must be an image.',
            'featured_image.max' => 'The image size must not exceed 2MB.',
        ];
    }
}