@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-header">
        <div class="brand-logo">
            <i class="fas fa-store"></i>
        </div>
        <h1>Welcome Back</h1>
        <p>Sign in to your Kerajinan account</p>
    </div>

    <div class="auth-body">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                @if ($errors->count() == 1)
                    {{ $errors->first() }}
                @else
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            <div class="form-floating">
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" placeholder="Username or Email" value="{{ old('username') }}" required>
                <label for="username">
                    <i class="fas fa-user me-2"></i>Username or Email
                </label>
                <div class="form-text">You can login with your username or email address</div>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating position-relative">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Password" required>
                <label for="password">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check d-flex justify-content-between align-items-center mb-3">
                <div>
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                <a href="{{ route('forgot-password') }}" class="text-decoration-none small">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>
    </div>

    <div class="auth-links">
        <p class="mb-0">Don't have an account?
            <a href="{{ route('register') }}">Create one here</a>
        </p>
        <div class="mt-2">
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-home me-1"></i>Back to Homepage
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Password toggle function
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling.nextElementSibling; // Skip label to get button
            const icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                button.setAttribute('title', 'Hide password');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                button.setAttribute('title', 'Show password');
            }
        }

        // Demo credentials helper
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Add demo credentials info (you can remove this in production)
        //     const demoInfo = document.createElement('div');
        //     demoInfo.className = 'alert alert-info mt-3';
        //     demoInfo.innerHTML = `
    //     <small><strong>Demo Credentials:</strong><br>
    //     Admin: <code>admin</code> / <code>password</code><br>
    //     User: <code>johndoe</code> / <code>password</code></small>
    // `;
        //     document.querySelector('.auth-body').appendChild(demoInfo);
        // });
    </script>
@endpush
