<!DOCTYPE html>
<html>
<head>
    <title>Register - Fomal Kickoff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label>Register as</label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Choose your role...</option>
                                    <option value="subscriber" {{ old('role') == 'subscriber' ? 'selected' : '' }}>Subscriber</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <small class="text-muted">Admins can create and manage posts. Subscribers can read and comment.</small>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Admin Code Field (only shows when Admin is selected) -->
                            <div class="mb-3" id="admin-code-field" style="display: none;">
                                <label>Admin Registration Code <span class="text-danger">*</span></label>
                                <input type="text" name="admin_code" class="form-control @error('admin_code') is-invalid @enderror" value="{{ old('admin_code') }}" placeholder="Enter admin code">
                                <small class="text-muted">You need a special code to register as admin.</small>
                                @error('admin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                            <div class="mt-3 text-center">
                                <a href="{{ route('login') }}">Already have an account? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide admin code field based on role selection
        document.getElementById('role').addEventListener('change', function() {
            const adminCodeField = document.getElementById('admin-code-field');
            if (this.value === 'admin') {
                adminCodeField.style.display = 'block';
            } else {
                adminCodeField.style.display = 'none';
            }
        });

        // Check on page load (for old() values)
        window.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const adminCodeField = document.getElementById('admin-code-field');
            if (roleSelect.value === 'admin') {
                adminCodeField.style.display = 'block';
            }
        });
    </script>
</body>
</html>