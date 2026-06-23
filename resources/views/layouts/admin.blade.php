<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }
        
        #wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background-color: #212529;
            transition: margin 0.25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 1rem 1.25rem;
            font-size: 1.2rem;
            background-color: #212529;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 1rem 1.25rem;
        }

        #page-content-wrapper {
            flex: 1;
            min-width: 0;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -250px;
            }
            
            body.sb-sidenav-toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }

        @media (min-width: 769px) {
            body.sb-sidenav-toggled #sidebar-wrapper {
                margin-left: -250px;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom text-white">
               <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                    <i class="fas fa-futbol me-2"></i>Fomal Kickoff Admin
                </a>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-2"></i> Posts
                </a>
                <a href="{{ route('admin.posts.create') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-plus me-2"></i> New Post
                </a>
                <a href="{{ route('admin.comments.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    <i class="fas fa-comments me-2"></i> Comments
                </a>
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
                <a href="{{ route('home') }}" target="_blank" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-external-link-alt me-2"></i> View Site
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="d-flex ms-auto align-items-center">
                        <span class="me-3">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ auth()->user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <!-- Success/Error Messages -->
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

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>