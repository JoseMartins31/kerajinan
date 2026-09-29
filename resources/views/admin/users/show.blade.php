@extends('layouts.admin')

@section('title', 'Detail Pengguna - ' . $user->username)
@section('page-title', 'Detail Pengguna')
@section('page-description', 'Informasi lengkap pengguna dan riwayat aktivitas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($user->username, 30) }}</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
        </a>
        @if ($user->role !== 'admin')
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i>Kelola Status
                </button>
                <ul class="dropdown-menu">
                    @if (($user->status ?? 'active') !== 'active')
                        <li>
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="dropdown-item text-success">
                                    <i class="fas fa-check me-2"></i>Aktifkan Pengguna
                                </button>
                            </form>
                        </li>
                    @endif
                    @if (($user->status ?? 'active') !== 'inactive')
                        <li>
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="dropdown-item text-warning">
                                    <i class="fas fa-pause me-2"></i>Nonaktifkan Pengguna
                                </button>
                            </form>
                        </li>
                    @endif
                    @if (($user->status ?? 'active') !== 'suspended')
                        <li>
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="suspended">
                                <button type="submit" class="dropdown-item text-danger"
                                    onclick="return confirm('Yakin ingin memblokir pengguna ini?')">
                                    <i class="fas fa-ban me-2"></i>Blokir Pengguna
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- User Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Profil Pengguna
                    </h6>
                </div>
                <div class="card-body text-center">
                    <!-- User Avatar -->
                    <div class="avatar mx-auto mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                            style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($user->username, 0, 2)) }}
                        </div>
                    </div>

                    <!-- User Info -->
                    <h5 class="mb-2">{{ $user->username }}</h5>

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

                    <span class="badge {{ $statusClass }} mb-3">{{ $statusText }}</span>

                    @if ($user->role === 'admin')
                        <div class="mb-3">
                            <span class="badge bg-danger">Administrator</span>
                        </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div class="text-start">
                                <small class="text-muted">Email</small>
                                <div>{{ $user->email }}</div>
                            </div>
                        </div>

                        @if ($user->jenis_kelamin)
                            <div class="list-group-item d-flex align-items-center">
                                <i class="fas fa-venus-mars text-primary me-3"></i>
                                <div class="text-start">
                                    <small class="text-muted">Jenis Kelamin</small>
                                    <div>{{ ucfirst($user->jenis_kelamin) }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($user->tanggal_lahir)
                            <div class="list-group-item d-flex align-items-center">
                                <i class="fas fa-birthday-cake text-primary me-3"></i>
                                <div class="text-start">
                                    <small class="text-muted">Tanggal Lahir</small>
                                    <div>{{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="list-group-item d-flex align-items-center">
                            <i class="fas fa-calendar text-primary me-3"></i>
                            <div class="text-start">
                                <small class="text-muted">Terdaftar</small>
                                <div>{{ $user->created_at ? $user->created_at->format('d F Y, H:i') : '-' }}</div>
                            </div>
                        </div>

                        @if ($user->email_verified_at)
                            <div class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <div class="text-start">
                                    <small class="text-muted">Email Terverifikasi</small>
                                    <div>{{ $user->email_verified_at->format('d F Y, H:i') }}</div>
                                </div>
                            </div>
                        @else
                            <div class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <div class="text-start">
                                    <small class="text-muted">Email</small>
                                    <div class="text-danger">Belum Terverifikasi</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $user->pesanan->count() }}</h4>
                                <small class="text-muted">Total Pesanan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">
                                Rp
                                {{ number_format($user->pesanan->where('status_pesanan', 'confirmed')->sum('total_harga'), 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Total Pembelian</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Riwayat Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    @if ($user->pesanan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->pesanan->sortByDesc('tanggal_pesanan') as $pesanan)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary">#{{ $pesanan->idPesanan }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    {{ $pesanan->tanggal_pesanan ? \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d/m/Y H:i') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    @foreach ($pesanan->detailPesanan->take(2) as $detail)
                                                        <small class="d-block">
                                                            {{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}
                                                            ({{ $detail->jumlah }}x)
                                                        </small>
                                                    @endforeach
                                                    @if ($pesanan->detailPesanan->count() > 2)
                                                        <small class="text-muted">
                                                            +{{ $pesanan->detailPesanan->count() - 2 }} item lainnya
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($pesanan->status_pesanan) {
                                                        'waiting_payment' => 'bg-warning text-dark',
                                                        'waiting_confirmation' => 'bg-info',
                                                        'confirmed' => 'bg-success',
                                                        'processing' => 'bg-primary',
                                                        'shipped' => 'bg-info',
                                                        'delivered' => 'bg-success',
                                                        'completed' => 'bg-success',
                                                        'cancelled' => 'bg-danger',
                                                        'payment_rejected' => 'bg-danger',
                                                        default => 'bg-secondary',
                                                    };
                                                    $statusText = match ($pesanan->status_pesanan) {
                                                        'waiting_payment' => 'Menunggu Pembayaran',
                                                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                                                        'confirmed' => 'Dikonfirmasi',
                                                        'processing' => 'Diproses',
                                                        'shipped' => 'Dikirim',
                                                        'delivered' => 'Diterima',
                                                        'completed' => 'Selesai',
                                                        'cancelled' => 'Dibatalkan',
                                                        'payment_rejected' => 'Pembayaran Ditolak',
                                                        default => ucfirst($pesanan->status_pesanan),
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $pesanan->idPesanan) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Pesanan</h5>
                            <p class="text-muted">Pengguna ini belum melakukan pemesanan.</p>
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

        .card-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem;
        }

        .list-group-item:last-child {
            border-bottom: none;
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

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .btn-group .btn {
            border-radius: 0.5rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .avatar {
            width: 80px;
            height: 80px;
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
