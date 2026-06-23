<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Guests see only 2 posts, logged-in users see all
        if (auth()->check()) {
            // Logged-in users see all posts (10 per page with pagination)
            $posts = Post::where('status', 'published')
                ->with('user')
                ->latest('published_at')
                ->paginate(10);
        } else {
            // Guests see only 2 posts total (no pagination)
            $posts = Post::where('status', 'published')
                ->with('user')
                ->latest('published_at')
                ->limit(2)
                ->get();
        }
        
        return view('blog.index', compact('posts'));
    }

   public function show($slug)
{
    // Only logged-in users can view full posts
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please login to read full posts');
    }

    $post = Post::where('slug', $slug)
        ->where('status', 'published')
        ->with(['user', 'comments' => function($query) {
            // Show ALL approved comments, not just from current user
            $query->where('status', 'approved')
                  ->with('user')
                  ->latest();
        }])
        ->firstOrFail();
    
    // Increment views
    $post->increment('views');
    
    return view('blog.show', compact('post'));
}
    public function storeComment(Request $request, Post $post)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to comment');
        }

        $request->validate([
            'content' => 'required|min:3'
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'status' => 'pending'
        ]);

        return back()->with('success', 'Comment submitted for approval!');
    }
}