@extends('layouts.frontend')

@section('title', 'Pesanan Saya - Kerajinan Daun Lontar')
@section('description', 'Lihat riwayat pesanan dan status pembayaran Anda')

@push('styles')
    <style>
        .orders-container {
            background-color: var(--bg-light);
            min-height: 70vh;
            padding: 2rem 0;
        }

        .orders-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .order-header {
            background: var(--bg-light);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .order-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .order-date {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-waiting_payment {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.5);
        }

        .status-waiting_confirmation {
            background: rgba(0, 123, 255, 0.2);
            color: #004085;
            border: 1px solid rgba(0, 123, 255, 0.5);
        }

        .status-confirmed {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.5);
        }

        .status-shipped {
            background: rgba(111, 66, 193, 0.2);
            color: #4c1d95;
            border: 1px solid rgba(111, 66, 193, 0.5);
        }

        .status-delivered {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.5);
        }

        .status-cancelled {
            background: rgba(220, 53, 69, 0.2);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.5);
        }

        .order-body {
            padding: 1.5rem;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 1rem;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .item-quantity {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .item-price {
            color: var(--primary-color);
            font-weight: 600;
            text-align: right;
        }

        .order-total {
            background: var(--bg-light);
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .total-amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-payment {
            background: linear-gradient(135deg, var(--accent-color), #e76f51);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-payment:hover {
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        .btn-detail {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        .empty-orders {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .empty-orders-icon {
            font-size: 5rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .filter-tabs {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .filter-tab {
            background: none;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            margin-right: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-dark);
        }

        .filter-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .filter-tab:hover {
            background: var(--bg-light);
        }

        .filter-tab.active:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                margin-bottom: 1rem;
                margin-right: 0;
            }

            .order-actions {
                flex-direction: column;
            }

            .order-total {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="orders-header">
        <div class="container">
            <div class="text-center" data-aos="fade-down">
                <h1><i class="fas fa-file-invoice me-3"></i>Pesanan Saya</h1>
                <p class="mb-0">Kelola dan pantau status pesanan Anda</p>
            </div>
        </div>
    </div>

    <div class="orders-container">
        <div class="container">
            <!-- Filter Tabs -->
            <div class="filter-tabs" data-aos="fade-up">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-list me-2"></i>Semua Pesanan
                </button>
                <button class="filter-tab" data-filter="waiting_payment">
                    <i class="fas fa-clock me-2"></i>Menunggu Pembayaran
                </button>
                <button class="filter-tab" data-filter="waiting_confirmation">
                    <i class="fas fa-hourglass-half me-2"></i>Menunggu Konfirmasi
                </button>
                <button class="filter-tab" data-filter="confirmed">
                    <i class="fas fa-check-circle me-2"></i>Dikonfirmasi
                </button>
                <button class="filter-tab" data-filter="shipped">
                    <i class="fas fa-shipping-fast me-2"></i>Dikirim
                </button>
                <button class="filter-tab" data-filter="delivered">
                    <i class="fas fa-box-check me-2"></i>Selesai
                </button>
            </div>

            @if ($orders->count() > 0)
                <!-- Orders List -->
                @foreach ($orders as $order)
                    <div class="order-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}"
                        data-status="{{ $order->status_pesanan }}">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="order-number">Pesanan #{{ $order->idPesanan }}</div>
                                    <div class="order-date">{{ $order->tanggal_pesanan->format('d F Y, H:i') }}</div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <span class="order-status status-{{ $order->status_pesanan }}">
                                        @switch($order->status_pesanan)
                                            @case('waiting_payment')
                                                <i class="fas fa-clock me-1"></i>Menunggu Pembayaran
                                            @break

                                            @case('waiting_confirmation')
                                                <i class="fas fa-hourglass-half me-1"></i>Menunggu Konfirmasi
                                            @break

                                            @case('confirmed')
                                                <i class="fas fa-check-circle me-1"></i>Dikonfirmasi
                                            @break

                                            @case('shipped')
                                                <i class="fas fa-shipping-fast me-1"></i>Dikirim
                                            @break

                                            @case('delivered')
                                                <i class="fas fa-box-check me-1"></i>Selesai
                                            @break

                                            @case('cancelled')
                                                <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                            @break

                                            @default
                                                {{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="order-body">
                            @foreach ($order->detailPesanan->take(3) as $detail)
                                <div class="order-item">
                                    @if ($detail->produk->foto && file_exists(public_path('images/' . $detail->produk->foto)))
                                        <img src="{{ asset('images/' . $detail->produk->foto) }}"
                                            alt="{{ $detail->produk->nama_produk }}" class="item-image">
                                    @else
                                        <div class="item-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-image text-muted fa-2x"></i>
                                        </div>
                                    @endif
                                    <div class="item-info">
                                        <div class="item-name">{{ $detail->produk->nama_produk }}</div>
                                        <div class="item-quantity">Jumlah: {{ $detail->jumlah }}</div>
                                    </div>
                                    <div class="item-price">
                                        Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            @if ($order->detailPesanan->count() > 3)
                                <div class="text-center pt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-plus me-1"></i>
                                        {{ $order->detailPesanan->count() - 3 }} produk lainnya
                                    </small>
                                </div>
                            @endif
                        </div>

                        <!-- Order Total & Actions -->
                        <div class="order-total">
                            <div class="row w-100 align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">Total:</span>
                                        <span class="total-amount">Rp
                                            {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="order-actions">
                                        @if ($order->status_pesanan === 'waiting_payment')
                                            <a href="{{ route('payment.upload', $order->idPesanan) }}" class="btn-payment">
                                                <i class="fas fa-upload me-2"></i>Upload Pembayaran
                                            </a>
                                        @endif
                                        <a href="{{ route('user.orders.show', $order->idPesanan) }}" class="btn-detail">
                                            <i class="fas fa-eye me-2"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center" data-aos="fade-up">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-orders" data-aos="fade-up">
                    <div class="empty-orders-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-store me-2"></i>Mulai Berbelanja
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Filter functionality
            $('.filter-tab').on('click', function() {
                const filter = $(this).data('filter');

                // Update active tab
                $('.filter-tab').removeClass('active');
                $(this).addClass('active');

                // Filter orders
                if (filter === 'all') {
                    $('.order-card').fadeIn();
                } else {
                    $('.order-card').hide();
                    $(`.order-card[data-status="${filter}"]`).fadeIn();
                }
            });

            // Auto-refresh for pending payments (every 30 seconds)
            @if ($orders->where('status_pesanan', 'waiting_confirmation')->count() > 0)
                setInterval(function() {
                    // Check for status updates
                    console.log('Checking for order updates...');
                }, 30000);
            @endif
        });
    </script>
@endpush
