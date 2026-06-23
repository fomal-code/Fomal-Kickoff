<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        $posts = Post::with(['user', 'categories', 'tags'])
                    ->when(auth()->user()->role === 'author', function($query) {
                        return $query->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->paginate(15);
        
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $tags = Tag::all();
        
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
            
            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $count = 1;
            while (Post::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }
        
        // Set user_id
        $data['user_id'] = auth()->id();
        
        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                                             ->store('posts', 'public');
        }
        
        // Calculate reading time
        $wordCount = str_word_count(strip_tags($data['content']));
        $data['reading_time'] = ceil($wordCount / 200);
        
        // Set published_at if status is published
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $post = Post::create($data);
        
        // Attach relationships
        if (!empty($data['categories'])) {
            $post->categories()->attach($data['categories']);
        }
        
        if (!empty($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }
        
        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'categories', 'tags', 'comments']);
        
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = Category::active()->get();
        $tags = Tag::all();
        
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        
        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $data['featured_image'] = $request->file('featured_image')
                                             ->store('posts', 'public');
        }
        
        // Calculate reading time
        $wordCount = str_word_count(strip_tags($data['content']));
        $data['reading_time'] = ceil($wordCount / 200);
        
        // Set published_at if status changed to published
        if ($data['status'] === 'published' && $post->status !== 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $post->update($data);
        
        // Sync relationships
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }
        
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();
        
        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post deleted successfully!');
    }
}