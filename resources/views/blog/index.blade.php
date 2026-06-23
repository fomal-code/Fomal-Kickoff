<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fomal Kickoff - Football News & Insights</title>
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
                @endif
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </a>
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

    <!-- Hero Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="display-4"><i class="fas fa-futbol text-success me-3"></i>Fomal Kickoff</h1>
            <p class="lead">Your Premier Source for Football News, Analysis & Insights</p>
            
            @guest
            <div class="alert alert-warning mt-3">
                <i class="fas fa-lock me-2"></i>
                <strong>Limited Access:</strong> You're viewing only 2 posts. 
                <a href="{{ route('login') }}" class="alert-link">Login</a> or 
                <a href="{{ route('register') }}" class="alert-link">Register</a> to access all football content!
            </div>
            @endguest
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Blog Posts -->
    <div class="container my-5">
        <div class="row">
            @forelse($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-success d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-futbol fa-3x text-white"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-user me-1"></i>By {{ $post->user->name }} • 
                            <i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }} • 
                            <i class="fas fa-eye me-1"></i>{{ $post->views }} views
                        </p>
                        <p class="card-text">{{ $post->excerpt ?? Str::limit($post->content, 150) }}</p>
                        
                        @auth
                            <a href="{{ route('post.show', $post->slug) }}" class="btn btn-success">
                                <i class="fas fa-book-open me-1"></i>Read More
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning">
                                <i class="fas fa-lock me-1"></i>Login to Read
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4><i class="fas fa-futbol me-2"></i>No posts yet!</h4>
                    <p>Check back soon for the latest football news and analysis.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Show message after 2 posts for guests -->
        @guest
            @if($posts->count() >= 2)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-success text-white text-center">
                        <div class="card-body py-5">
                            <h3><i class="fas fa-lock me-2"></i>Want More Football Content?</h3>
                            <p class="lead mb-4">Login or create a free account to access all our football news, analysis, and insights!</p>
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user-plus me-1"></i>Register Free
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endguest

        <!-- Pagination for logged-in users only -->
        @auth
            @if(method_exists($posts, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
            @endif
        @endauth
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