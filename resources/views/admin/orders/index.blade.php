@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')
@section('page-description', 'Manajemen dan monitoring pesanan pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pesanan</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.payments.pending') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-clock me-1"></i>
            Konfirmasi Pembayaran
        </a>
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-info btn-sm">
            <i class="fas fa-chart-line me-1"></i>
            Laporan Penjualan
        </a>
    </div>
@endsection

@section('content')

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Pembayaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status_pesanan', 'waiting_payment')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Menunggu Konfirmasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status_pesanan', 'waiting_confirmation')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status_pesanan', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Pesanan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status Pesanan</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>
                            Menunggu Pembayaran
                        </option>
                        <option value="waiting_confirmation"
                            {{ request('status') == 'waiting_confirmation' ? 'selected' : '' }}>
                            Menunggu Konfirmasi
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                            Dikonfirmasi
                        </option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                            Diproses
                        </option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                            Dikirim
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Metode Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->idPesanan }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $order->user->username }}</div>
                                            <div class="text-muted small">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $order->tanggal_pesanan->format('d/m/Y') }}</div>
                                    <div class="text-muted small">{{ $order->tanggal_pesanan->format('H:i') }}</div>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'waiting_payment' => 'warning',
                                            'waiting_confirmation' => 'info',
                                            'confirmed' => 'primary',
                                            'processing' => 'primary',
                                            'shipped' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            'payment_rejected' => 'danger',
                                        ];
                                        $statusText = [
                                            'waiting_payment' => 'Menunggu Pembayaran',
                                            'waiting_confirmation' => 'Menunggu Konfirmasi',
                                            'confirmed' => 'Dikonfirmasi',
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            'payment_rejected' => 'Pembayaran Ditolak',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusClass[$order->status_pesanan] ?? 'secondary' }}">
                                        {{ $statusText[$order->status_pesanan] ?? $order->status_pesanan }}
                                    </span>
                                </td>
                                <td>
                                    @switch($order->metode_pembayaran)
                                        @case('transfer_bank')
                                            <i class="fas fa-university me-1"></i> Transfer
                                        @break

                                        @case('cod')
                                            <i class="fas fa-money-bill me-1"></i> COD
                                        @break

                                        @case('ewallet')
                                            <i class="fas fa-mobile-alt me-1"></i> E-Wallet
                                        @break

                                        @default
                                            {{ $order->metode_pembayaran }}
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order->idPesanan) }}"
                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($order->status_pesanan === 'waiting_confirmation')
                                            <a href="{{ route('admin.payments.show', $order->idPesanan) }}"
                                                class="btn btn-warning btn-sm" title="Verifikasi Pembayaran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Belum ada pesanan yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
        </div>
    @endsection
