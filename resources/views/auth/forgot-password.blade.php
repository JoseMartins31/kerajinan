@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-header">
        <div class="brand-logo">
            <i class="fas fa-key"></i>
        </div>
        <h1>Forgot Password</h1>
        <p>Enter your username to reset your password</p>
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

        <form method="POST" action="{{ route('forgot-password.post') }}" id="forgotPasswordForm">
            @csrf

            <div class="form-floating">
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" placeholder="Username" value="{{ old('username') }}" required>
                <label for="username">
                    <i class="fas fa-user me-2"></i>Username
                </label>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Send Reset Instructions
            </button>
        </form>

        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <small>
                <strong>Note:</strong> Password reset instructions will be sent to your registered contact information.
                If you don't receive them, please contact our support team for assistance.
            </small>
        </div>
    </div>

    <div class="auth-links">
        <p class="mb-0">Remember your password?
            <a href="{{ route('login') }}">Sign in here</a>
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
        document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;
        });
    </script>
@endpush
