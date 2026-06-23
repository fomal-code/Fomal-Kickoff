<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index()
    {
        $tags = Tag::withCount('posts')
                  ->orderBy('name')
                  ->paginate(20);
        
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        Tag::create($data);
        
        return redirect()->route('admin.tags.index')
                        ->with('success', 'Tag created successfully!');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
        ]);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $tag->update($data);
        
        return redirect()->route('admin.tags.index')
                        ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        
        return redirect()->route('admin.tags.index')
                        ->with('success', 'Tag deleted successfully!');
    }
}