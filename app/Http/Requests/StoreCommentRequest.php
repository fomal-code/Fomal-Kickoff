<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Anyone can comment
    }

    public function rules()
    {
        $rules = [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ];
        
        // If user is not authenticated, require name and email
        if (!auth()->check()) {
            $rules['author_name'] = 'required|string|max:255';
            $rules['author_email'] = 'required|email|max:255';
        }
        
        return $rules;
    }
}