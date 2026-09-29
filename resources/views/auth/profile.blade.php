@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-circle me-2"></i>My Profile
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-user me-1"></i>Username *
                                    </label>
                                    <input type="text" name="username" id="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="username-feedback" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-shield-alt me-1"></i>Role
                                    </label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                    <div class="form-text">Your account role cannot be changed</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-key me-1"></i>Change Password (Optional)
                        </h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror">
                                        <button type="button"
                                            class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent"
                                            onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror" minlength="8">
                                        <button type="button"
                                            class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent"
                                            onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="new_password_confirmation"
                                            id="new_password_confirmation" class="form-control" minlength="8">
                                        <button type="button"
                                            class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent"
                                            onclick="togglePassword('new_password_confirmation')">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                    <div id="password-match-feedback" class="form-text"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home') }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="mt-2 mb-0">{{ $user->username }}</h5>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} mt-1">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-calendar-alt text-primary fa-2x mb-2"></i>
                                <h6 class="mb-1">Joined</h6>
                                <small>{{ $user->created_at->format('M Y') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i
                                    class="fas fa-{{ $user->status === 'active' ? 'check-circle text-success' : 'pause-circle text-warning' }} fa-2x mb-2"></i>
                                <h6 class="mb-1">Status</h6>
                                <small>{{ ucfirst($user->status) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Security Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use a strong, unique password
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Update your password regularly
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Don't share your account credentials
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Log out from shared computers
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('new_password_confirmation');
            const usernameFeedback = document.getElementById('username-feedback');
            const passwordMatchFeedback = document.getElementById('password-match-feedback');

            // Username availability check
            let usernameTimeout;
            usernameInput.addEventListener('input', function() {
                clearTimeout(usernameTimeout);
                const username = this.value.trim();
                const currentUsername = '{{ $user->username }}';

                if (username === currentUsername) {
                    usernameFeedback.innerHTML = '';
                    return;
                }

                if (username.length < 3) {
                    usernameFeedback.innerHTML = '';
                    return;
                }

                usernameTimeout = setTimeout(() => {
                    checkUsernameAvailability(username);
                }, 500);
            });

            function checkUsernameAvailability(username) {
                usernameFeedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking availability...';

                fetch(`{{ route('api.auth.check-username') }}?username=${username}&user_id={{ $user->id }}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            usernameFeedback.innerHTML =
                                '<span class="text-success"><i class="fas fa-check"></i> Username is available</span>';
                        } else {
                            usernameFeedback.innerHTML =
                                '<span class="text-danger"><i class="fas fa-times"></i> ' + data.message +
                                '</span>';
                        }
                    })
                    .catch(() => {
                        usernameFeedback.innerHTML =
                            '<span class="text-muted">Could not check availability</span>';
                    });
            }

            // Password confirmation check
            function checkPasswordMatch() {
                const password = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;

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

            newPasswordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Form submission
            document.getElementById('profileForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endpush
