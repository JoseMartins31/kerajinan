@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('page-description', 'Daftar semua pengguna terdaftar')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Semua Pengguna</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['status' => 'active']) }}">Aktif</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['status' => 'inactive']) }}">Tidak
                        Aktif</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['status' => 'suspended']) }}">Diblokir</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Daftar Pengguna
                        </h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary">{{ $users->total() }} Total</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                        <th><i class="fas fa-user me-1"></i>Nama</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-venus-mars me-1"></i>Gender</th>
                                        <th><i class="fas fa-birthday-cake me-1"></i>Tanggal Lahir</th>
                                        <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                        <th><i class="fas fa-calendar me-1"></i>Terdaftar</th>
                                        <th><i class="fas fa-cog me-1"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary">#{{ $user->id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2">
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                            style="width: 35px; height: 35px;">
                                                            {{ strtoupper(substr($user->username, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->username }}</strong>
                                                        @if ($user->role === 'admin')
                                                            <span class="badge bg-danger ms-1">Admin</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                <i class="fas fa-venus-mars text-muted me-1"></i>
                                                {{ $user->jenis_kelamin ? ucfirst($user->jenis_kelamin) : '-' }}
                                            </td>
                                            <td>
                                                <i class="fas fa-birthday-cake text-muted me-1"></i>
                                                {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($user->status ?? 'active') {
                                                        'active' => 'bg-success',
                                                        'inactive' => 'bg-secondary',
                                                        'suspended' => 'bg-danger',
                                                        default => 'bg-primary',
                                                    };
                                                    $statusText = match ($user->status ?? 'active') {
                                                        'active' => 'Aktif',
                                                        'inactive' => 'Tidak Aktif',
                                                        'suspended' => 'Diblokir',
                                                        default => 'Aktif',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                        class="btn btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($user->role !== 'admin')
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fas fa-cog"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if (($user->status ?? 'active') !== 'active')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('admin.users.update-status', $user->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="status"
                                                                                value="active">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-success">
                                                                                <i class="fas fa-check me-2"></i>Aktifkan
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                                @if (($user->status ?? 'active') !== 'inactive')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('admin.users.update-status', $user->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="status"
                                                                                value="inactive">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-warning">
                                                                                <i class="fas fa-pause me-2"></i>Nonaktifkan
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                                @if (($user->status ?? 'active') !== 'suspended')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('admin.users.update-status', $user->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="status"
                                                                                value="suspended">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger"
                                                                                onclick="return confirm('Yakin ingin memblokir pengguna ini?')">
                                                                                <i class="fas fa-ban me-2"></i>Blokir
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <span class="btn btn-sm btn-outline-secondary disabled">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                                dari {{ $users->total() }} pengguna
                            </div>
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-users fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Pengguna</h5>
                            <p class="text-muted">Belum ada pengguna terdaftar dalam sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.15);
            border: none;
            border-radius: 0.75rem;
        }

        .table th {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 1rem 0.75rem;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .avatar {
            flex-shrink: 0;
        }

        .btn-group-sm>.btn {
            padding: 0.375rem 0.5rem;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endpush
