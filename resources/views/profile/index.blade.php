<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Fomal Kickoff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-futbol me-2"></i>Fomal Kickoff
            </a>
            <div class="ms-auto">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                    </a>
                @endif
                <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-user me-1"></i>Profile
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <!-- User Info Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle fa-5x text-success mb-3"></i>
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'success' }} fs-6">
                            {{ ucfirst($user->role) }}
                        </span>
                        <hr>
                        <p class="mb-1"><strong>Member Since:</strong></p>
                        <p>{{ $user->created_at->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- User Content -->
            <div class="col-md-8">
                <!-- My Comments -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-comments me-2"></i>My Comments ({{ $comments->total() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($comments as $comment)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">
                                    On: <a href="{{ route('post.show', $comment->post->slug) }}">{{ $comment->post->title }}</a>
                                </h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <span class="badge bg-{{ $comment->status == 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($comment->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-muted text-center">You haven't made any comments yet.</p>
                        @endforelse

                        @if($comments->hasPages())
                        <div class="mt-3">
                            {{ $comments->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- My Posts (Admin Only) -->
                @if($user->isAdmin() && $posts)
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>My Posts ({{ $posts->total() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($posts as $post)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $post->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>{{ $post->views }} views • 
                                        <i class="fas fa-comments me-1"></i>{{ $post->comments_count }} comments
                                    </small>
                                </div>
                                <span class="badge bg-{{ $post->status == 'published' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <a href="{{ route('post.show', $post->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">You haven't created any posts yet.</p>
                        @endforelse

                        @if($posts->hasPages())
                        <div class="mt-3">
                            {{ $posts->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">
                <i class="fas fa-futbol me-2"></i>&copy; 2024 Fomal Kickoff. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>