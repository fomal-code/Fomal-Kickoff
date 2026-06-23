<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        $categories = Category::with('parent')
                             ->withCount('posts')
                             ->orderBy('order')
                             ->paginate(20);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->orderBy('name')
                                   ->get();
        
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            
            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $count = 1;
            while (Category::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                                    ->store('categories', 'public');
        }
        
        Category::create($data);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->where('id', '!=', $category->id)
                                   ->orderBy('name')
                                   ->get();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $data['image'] = $request->file('image')
                                    ->store('categories', 'public');
        }
        
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category deleted successfully!');
    }
}