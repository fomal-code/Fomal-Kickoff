@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Total Posts</div>
                            <div class="h2 mb-0">{{ $stats['total_posts'] }}</div>
                        </div>
                        <i class="fas fa-file-alt fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.posts.index') }}">View All</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Published</div>
                            <div class="h2 mb-0">{{ $stats['published_posts'] }}</div>
                        </div>
                        <i class="fas fa-check-circle fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.posts.index', ['status' => 'published']) }}">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Comments</div>
                            <div class="h2 mb-0">{{ $stats['total_comments'] }}</div>
                        </div>
                        <i class="fas fa-comments fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.comments.index') }}">Manage</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Total Users</div>
                            <div class="h2 mb-0">{{ $stats['total_users'] }}</div>
                        </div>
                        <i class="fas fa-users fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users.index') }}">View All</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts, Comments, Users Row -->
    <div class="row g-4 mb-4">
        <!-- Recent Posts -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-alt me-1"></i> Recent Posts</span>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-primary">New Post</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_posts as $post)
                                <tr>
                                    <td>{{ Str::limit($post->title, 30) }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $post->status == 'published' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No posts yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-comments me-1"></i> Recent Comments
                    @if($stats['pending_comments'] > 0)
                    <span class="badge bg-danger ms-2">{{ $stats['pending_comments'] }} pending</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Comment</th>
                                    <th>Post</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_comments as $comment)
                                <tr>
                                    <td>{{ Str::limit($comment->content, 40) }}</td>
                                    <td>{{ Str::limit($comment->post->title, 20) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $comment->status == 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($comment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($comment->status == 'pending')
                                            <form action="{{ route('admin.comments.update', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this comment?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No comments yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-1"></i> Recent Users</span>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'success' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No users yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Posts -->
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i> Most Popular Posts
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Views</th>
                                    <th>Comments</th>
                                    <th>Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popular_posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td><span class="badge bg-primary">{{ $post->views ?? 0 }}</span></td>
                                    <td><span class="badge bg-info">{{ $post->comments_count }}</span></td>
                                    <td>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No posts yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection