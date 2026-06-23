@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Edit Post</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                              id="excerpt" name="excerpt" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="10" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($post->featured_image)
                <div class="mb-3">
                    <label class="form-label">Current Featured Image</label>
                    <div>
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured" style="max-width: 200px;">
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label for="featured_image" class="form-label">Change Featured Image</label>
                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                           id="featured_image" name="featured_image" accept="image/*">
                    @error('featured_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection