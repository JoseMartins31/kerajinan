@extends('layouts.frontend')

@section('title', 'Order Success')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Header -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="text-success mb-3">Pesanan Berhasil Dibuat!</h1>
                    <p class="text-muted lead">
                        @if ($pesanan->status_pesanan === 'waiting_confirmation')
                            Bukti pembayaran Anda telah diterima dan sedang diverifikasi oleh admin.
                        @else
                            Terima kasih telah berbelanja. Silahkan lanjutkan dengan proses pembayaran.
                        @endif
                    </p>
                </div>

                <!-- Order Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            Informasi Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">ID Pesanan</label>
                                    <div class="fw-bold">#{{ $pesanan->idPesanan }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Tanggal Pesanan</label>
                                    <div>{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Status Pesanan</label>
                                    <div>
                                        @php
                                            $statusClass = [
                                                'waiting_payment' => 'warning',
                                                'waiting_confirmation' => 'info',
                                                'processing' => 'primary',
                                                'shipped' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusText = [
                                                'waiting_payment' => 'Menunggu Pembayaran',
                                                'waiting_confirmation' => 'Menunggu Konfirmasi',
                                                'processing' => 'Diproses',
                                                'shipped' => 'Dikirim',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusClass[$pesanan->status_pesanan] ?? 'secondary' }}">
                                            {{ $statusText[$pesanan->status_pesanan] ?? $pesanan->status_pesanan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Metode Pembayaran</label>
                                    <div>
                                        @switch($pesanan->metode_pembayaran)
                                            @case('transfer_bank')
                                                <i class="fas fa-university me-1"></i> Transfer Bank
                                            @break

                                            @case('cod')
                                                <i class="fas fa-money-bill me-1"></i> Cash on Delivery
                                            @break

                                            @case('ewallet')
                                                <i class="fas fa-mobile-alt me-1"></i> E-Wallet
                                            @break

                                            @default
                                                {{ $pesanan->metode_pembayaran }}
                                        @endswitch
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Total Pembayaran</label>
                                    <div class="h5 text-primary fw-bold">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                                @if ($pesanan->no_telepon)
                                    <div class="mb-3">
                                        <label class="text-muted small">No. Telepon</label>
                                        <div>{{ $pesanan->no_telepon }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($pesanan->alamat_pengiriman)
                            <div class="mt-3 pt-3 border-top">
                                <label class="text-muted small">Alamat Pengiriman</label>
                                <div>{{ $pesanan->alamat_pengiriman }}</div>
                            </div>
                        @endif

                        @if ($pesanan->catatan)
                            <div class="mt-3 pt-3 border-top">
                                <label class="text-muted small">Catatan</label>
                                <div>{{ $pesanan->catatan }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Produk yang Dipesan ({{ $pesanan->detailPesanan->count() }} item)
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($pesanan->detailPesanan as $detail)
                            <div class="d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="flex-shrink-0 me-3">
                                    @if ($detail->produk->foto)
                                        <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                            alt="{{ $detail->produk->nama_produk }}" class="rounded"
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $detail->produk->nama_produk }}</h6>
                                    <div class="text-muted small">
                                        {{ $detail->jumlah }} x Rp
                                        {{ number_format($detail->produk->Harga, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="fw-bold">
                                    Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Status & Next Steps -->
                @if ($pesanan->status_pesanan === 'waiting_payment')
                    <div class="card shadow-sm border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Lanjutkan Pembayaran
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">
                                Pesanan Anda belum dibayar. Silahkan lakukan pembayaran dan upload bukti pembayaran untuk
                                memproses pesanan Anda.
                            </p>
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="{{ route('payment.upload', $pesanan->idPesanan) }}"
                                    class="btn btn-warning flex-grow-1">
                                    <i class="fas fa-upload me-2"></i>
                                    Upload Bukti Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($pesanan->status_pesanan === 'waiting_confirmation')
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Menunggu Konfirmasi
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">
                                Bukti pembayaran Anda telah diterima dan sedang diverifikasi oleh tim kami.
                                Kami akan menginformasikan status pesanan melalui email atau notifikasi.
                            </p>
                            <div class="small text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Proses verifikasi biasanya memakan waktu 1-24 jam kerja.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>
                            Lihat Semua Pesanan
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Lanjut Belanja
                        </a>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="text-center mt-4 pt-4 border-top">
                    <p class="text-muted small mb-2">
                        <i class="fas fa-question-circle me-1"></i>
                        Butuh bantuan dengan pesanan Anda?
                    </p>
                    <div class="small">
                        <a href="mailto:support@kerajinan.com" class="text-decoration-none me-3">
                            <i class="fas fa-envelope me-1"></i>
                            Email Support
                        </a>
                        <a href="https://wa.me/6281234567890" class="text-decoration-none" target="_blank">
                            <i class="fab fa-whatsapp me-1"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        @media print {

            .btn,
            .border-top:last-child {
                display: none !important;
            }
        }
    </style>
@endsection
