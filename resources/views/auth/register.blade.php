@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-header">
        <div class="brand-logo">
            <i class="fas fa-user-plus"></i>
        </div>
        <h1>Join Kerajinan</h1>
        <p>Create your account to start shopping</p>
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

        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf

            <div class="form-floating">
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" placeholder="Username" value="{{ old('username') }}" pattern="[a-zA-Z0-9_]+"
                    title="Username can only contain letters, numbers, and underscores" required>
                <label for="username">
                    <i class="fas fa-user me-2"></i>Username
                </label>
                <div class="form-text">Only letters, numbers, and underscores allowed</div>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div id="username-feedback" class="form-text"></div>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                <label for="email">
                    <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <div class="form-text">Your email address for account verification and notifications</div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating position-relative">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Password" minlength="8" required>
                <label for="password">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye"></i>
                </button>
                <div class="form-text">Minimum 8 characters</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating position-relative">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" minlength="8"
                    required>
                <label for="password_confirmation">
                    <i class="fas fa-lock me-2"></i>Confirm Password
                </label>
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye"></i>
                </button>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div id="password-match-feedback" class="form-text"></div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a>
                    and <a href="#" class="text-decoration-none">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" id="registerBtn">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </form>
    </div>

    <div class="auth-links">
        <p class="mb-0">Already have an account?
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
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const usernameFeedback = document.getElementById('username-feedback');
            const passwordMatchFeedback = document.getElementById('password-match-feedback');
            const registerBtn = document.getElementById('registerBtn');

            // Username availability check
            let usernameTimeout;
            usernameInput.addEventListener('input', function() {
                clearTimeout(usernameTimeout);
                const username = this.value.trim();

                if (username.length < 3) {
                    usernameFeedback.innerHTML = '';
                    return;
                }

                usernameTimeout = setTimeout(() => {
                    checkUsernameAvailability(username);
                }, 500);
            });

            function checkUsernameAvailability(username) {
                // In a real application, you would make an AJAX call to check availability
                // For now, we'll simulate it
                usernameFeedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

                setTimeout(() => {
                    // Simulate some taken usernames
                    const takenUsernames = ['admin', 'test', 'user', 'demo'];
                    if (takenUsernames.includes(username.toLowerCase())) {
                        usernameFeedback.innerHTML =
                            '<span class="text-danger"><i class="fas fa-times"></i> Username is taken</span>';
                    } else {
                        usernameFeedback.innerHTML =
                            '<span class="text-success"><i class="fas fa-check"></i> Username is available</span>';
                    }
                }, 1000);
            }

            // Password confirmation check
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;

                if (confirmPassword.length === 0) {
                    passwordMatchFeedback.innerHTML = '';
                    return;
                }

                if (password === confirmPassword) {
                    passwordMatchFeedback.innerHTML =
                        '<span class="text-success"><i class="fas fa-check"></i> Passwords match</span>';
                } else {
                    passwordMatchFeedback.innerHTML =
                        '<span class="text-danger"><i class="fas fa-times"></i> Passwords do not match</span>';
                }
            }

            passwordInput.addEventListener('input', checkPasswordMatch);
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);

            // Form validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    passwordMatchFeedback.innerHTML =
                        '<span class="text-danger"><i class="fas fa-times"></i> Passwords do not match</span>';
                    return;
                }

                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
                registerBtn.disabled = true;
            });

            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = calculatePasswordStrength(password);

                // You can add visual feedback for password strength here
            });

            function calculatePasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                return strength;
            }
        });

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.parentElement.querySelector('.password-toggle i');

            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
                toggle.parentElement.title = 'Hide password';
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
                toggle.parentElement.title = 'Show password';
            }
        }
    </script>
@endpush
