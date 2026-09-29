@extends('layouts.frontend')

@section('title', 'Profil Saya')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                    Profil Saya
                                </h2>
                                <p class="text-muted mb-0">Kelola informasi akun dan preferensi Anda</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Profile Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Informasi Profil
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Username -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="fas fa-user me-1 text-primary"></i>Username *
                                        </label>
                                        <input type="text" name="username" id="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', $user->username) }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Username hanya boleh berisi huruf, angka, dan
                                            underscore</small>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-1 text-primary"></i>Email *
                                        </label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="fas fa-phone me-1 text-primary"></i>Nomor Telepon
                                        </label>
                                        <input type="text" name="phone" id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label fw-semibold">
                                            <i class="fas fa-user me-1 text-primary"></i>Jenis Kelamin
                                        </label>
                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                            class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="laki-laki"
                                                {{ old('jenis_kelamin', $user->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="perempuan"
                                                {{ old('jenis_kelamin', $user->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Birth Date -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-primary"></i>Tanggal Lahir
                                        </label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                            max="{{ date('Y-m-d') }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="address" class="form-label fw-semibold">
                                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>Alamat
                                        </label>
                                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                            placeholder="Masukkan alamat lengkap Anda">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Password Change Section -->
                            <h6 class="mb-3">
                                <i class="fas fa-key me-2 text-warning"></i>
                                Ubah Password (Opsional)
                            </h6>

                            <div class="row">
                                <!-- Current Password -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label fw-semibold">
                                            Password Saat Ini
                                        </label>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label fw-semibold">
                                            Password Baru
                                        </label>
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            minlength="8">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Minimal 8 karakter</small>
                                    </div>
                                </div>

                                <!-- Confirm New Password -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password_confirmation" class="form-label fw-semibold">
                                            Konfirmasi Password Baru
                                        </label>
                                        <input type="password" name="new_password_confirmation"
                                            id="new_password_confirmation" class="form-control" minlength="8">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Akun
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="border-end">
                                    <i class="fas fa-calendar-alt text-primary fs-4"></i>
                                    <h6 class="mt-2 mb-1">Bergabung Sejak</h6>
                                    <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-end">
                                    <i class="fas fa-shopping-bag text-success fs-4"></i>
                                    <h6 class="mt-2 mb-1">Total Pesanan</h6>
                                    <small class="text-muted">{{ $user->pesanan()->count() ?? 0 }} pesanan</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <i class="fas fa-user-check text-info fs-4"></i>
                                <h6 class="mt-2 mb-1">Status Akun</h6>
                                <small class="badge bg-success">Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .form-label.fw-semibold {
            color: #495057;
        }

        .border-end {
            border-right: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .border-end {
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                margin-bottom: 1rem;
                padding-bottom: 1rem;
            }
        }
    </style>
@endpush
