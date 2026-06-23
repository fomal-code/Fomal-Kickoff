<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Fomal Kickoff</title>
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
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                        </a>
                    @else
                        <span class="text-white me-3">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                        </span>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Post Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Back Button -->
                <a href="{{ route('home') }}" class="btn btn-outline-success mb-4">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>

                <!-- Post Header -->
                <article>
                    <h1 class="display-4 mb-3">{{ $post->title }}</h1>
                    
                    <div class="text-muted mb-4">
                        <span><i class="fas fa-user me-1"></i><strong>{{ $post->user->name }}</strong></span> • 
                        <span><i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('F d, Y') }}</span> • 
                        <span><i class="fas fa-eye me-1"></i>{{ $post->views }} views</span> • 
                        <span><i class="fas fa-comments me-1"></i>{{ $post->comments->count() }} comments</span>
                    </div>

                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">
                    @endif

                    <!-- Post Body -->
                    <div class="post-content" style="line-height: 1.8; font-size: 1.1rem;">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </article>

                <hr class="my-5">

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3 class="mb-4"><i class="fas fa-comments me-2"></i>Comments ({{ $post->comments->count() }})</h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @auth
                    <!-- Comment Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('comment.store', $post) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Leave a comment</label>
                                    <textarea name="content" class="form-control" rows="3" placeholder="Share your thoughts..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <a href="{{ route('login') }}">Login</a> to leave a comment
                    </div>
                    @endauth

                    <!-- Display Comments -->
                    @forelse($post->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong><i class="fas fa-user-circle me-1"></i>{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted"><i class="fas fa-comment-slash me-2"></i>No comments yet. Be the first to comment!</p>
                    @endforelse
                </div>
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