@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')
@section('page-title', 'Konfirmasi Pembayaran')
@section('page-description', 'Verifikasi dan konfirmasi pembayaran pesanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
    <li class="breadcrumb-item active">Konfirmasi Pembayaran</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-list me-1"></i>
        Semua Pesanan
    </a>
@endsection

@section('content')

    <!-- Alert Info -->
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Info:</strong> Berikut adalah pesanan yang menunggu konfirmasi pembayaran.
        Silahkan verifikasi bukti pembayaran sebelum mengkonfirmasi pesanan.
    </div>

    <!-- Pending Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Pembayaran Menunggu Konfirmasi
                <span class="badge bg-warning text-dark">{{ $orders->total() }}</span>
            </h6>
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
                            <th>Metode</th>
                            <th>Bukti Bayar</th>
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
                                    <div class="small">
                                        <span class="badge bg-secondary">
                                            {{ $order->tanggal_pesanan->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </strong>
                                    <div class="small text-muted">
                                        {{ $order->detailPesanan->count() }} item
                                    </div>
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
                                <td class="text-center">
                                    @if ($order->bukti_pembayaran)
                                        <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}"
                                            alt="Bukti Pembayaran" class="img-thumbnail"
                                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                            onclick="showImageModal('{{ asset('storage/' . $order->bukti_pembayaran) }}', 'Bukti Pembayaran #{{ $order->idPesanan }}')">
                                        <div class="small text-success mt-1">
                                            <i class="fas fa-check-circle"></i> Ada
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-times-circle"></i><br>
                                            <small>Belum upload</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order->idPesanan) }}"
                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($order->bukti_pembayaran)
                                            <a href="{{ route('admin.payments.show', $order->idPesanan) }}"
                                                class="btn btn-warning btn-sm" title="Verifikasi">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-check-double fa-3x mb-3 text-success"></i>
                                            <h5>Tidak Ada Pembayaran yang Menunggu</h5>
                                            <p>Semua pembayaran sudah dikonfirmasi atau belum ada pesanan baru.</p>
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
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        @if ($orders->count() > 0)
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Menunggu
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $orders->total() }} Pesanan
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Sudah Upload Bukti
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $orders->filter(function ($order) {return $order->bukti_pembayaran;})->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-image fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Nilai
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Rata-rata Nilai
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp {{ number_format($orders->avg('total_harga'), 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showImageModal(imageSrc, title) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModalLabel').textContent = title;
                new bootstrap.Modal(document.getElementById('imageModal')).show();
            }
        </script>

        <style>
            .img-thumbnail:hover {
                transform: scale(1.05);
                transition: transform 0.2s ease-in-out;
            }
        </style>
    @endsection
