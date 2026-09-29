@extends('layouts.frontend')

@section('title', 'Detail Pesanan #' . $pesanan->idPesanan . ' - Kerajinan Indonesia')
@section('description', 'Detail lengkap pesanan dan status pembayaran')

@push('styles')
    <style>
        .order-detail-container {
            background-color: var(--bg-light);
            min-height: 70vh;
            padding: 2rem 0;
        }

        .detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .detail-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
        }

        .order-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 1rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .status-waiting_payment {
            background: rgba(255, 193, 7, 0.9);
            color: #856404;
        }

        .status-waiting_confirmation {
            background: rgba(0, 123, 255, 0.9);
            color: white;
        }

        .status-confirmed {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .status-shipped {
            background: rgba(111, 66, 193, 0.9);
            color: white;
        }

        .status-delivered {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .status-cancelled {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .section-header {
            background: var(--bg-light);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-dark);
        }

        .section-body {
            padding: 1.5rem;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 1.5rem;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .product-category {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            color: var(--primary-color);
            font-weight: 600;
        }

        .product-quantity {
            color: var(--text-dark);
            font-weight: 600;
        }

        .product-subtotal {
            text-align: right;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .total-section {
            background: var(--bg-light);
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .total-row.final {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            border-top: 2px solid var(--border-color);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .btn-secondary-custom {
            background: var(--border-color);
            border: none;
            color: var(--text-dark);
            padding: 0.75rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--text-light);
            color: white;
            text-decoration: none;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 6px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--border-color);
            border: 3px solid white;
            box-shadow: var(--shadow);
        }

        .timeline-item.active::before {
            background: var(--primary-color);
        }

        .timeline-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .timeline-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .timeline-date {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .info-value {
            color: var(--text-dark);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .product-item {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                margin-bottom: 1rem;
                margin-right: 0;
            }

            .action-buttons {
                flex-direction: column;
            }

            .order-meta {
                gap: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-detail-container">
        <div class="container">
            <!-- Order Header -->
            <div class="detail-card">
                <div class="detail-header" data-aos="fade-down">
                    <div class="order-number">Pesanan #{{ $pesanan->idPesanan }}</div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Tanggal Pesanan</span>
                            <span class="meta-value">{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge status-{{ $pesanan->status_pesanan }}">
                                @switch($pesanan->status_pesanan)
                                    @case('waiting_payment')
                                        <i class="fas fa-clock me-2"></i>Menunggu Pembayaran
                                    @break

                                    @case('waiting_confirmation')
                                        <i class="fas fa-hourglass-half me-2"></i>Menunggu Konfirmasi
                                    @break

                                    @case('confirmed')
                                        <i class="fas fa-check-circle me-2"></i>Dikonfirmasi
                                    @break

                                    @case('shipped')
                                        <i class="fas fa-shipping-fast me-2"></i>Dikirim
                                    @break

                                    @case('delivered')
                                        <i class="fas fa-box-check me-2"></i>Selesai
                                    @break

                                    @case('cancelled')
                                        <i class="fas fa-times-circle me-2"></i>Dibatalkan
                                    @break

                                    @default
                                        {{ ucfirst(str_replace('_', ' ', $pesanan->status_pesanan)) }}
                                @endswitch
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Metode Pembayaran</span>
                            <span class="meta-value">Transfer Bank</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Pembayaran</span>
                            <span class="meta-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="section-card" data-aos="fade-right">
                        <div class="section-header">
                            <i class="fas fa-shopping-bag me-2"></i>Produk yang Dipesan
                        </div>
                        <div class="section-body">
                            @foreach ($pesanan->detailPesanan as $detail)
                                <div class="product-item">
                                    @if ($detail->produk->foto && file_exists(public_path('images/' . $detail->produk->foto)))
                                        <img src="{{ asset('images/' . $detail->produk->foto) }}"
                                            alt="{{ $detail->produk->nama_produk }}" class="product-image">
                                    @else
                                        <div
                                            class="product-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                    <div class="product-info">
                                        <div class="product-name">
                                            <a href="{{ route('product.detail', $detail->produk->idProduk) }}"
                                                class="text-decoration-none text-dark fw-semibold">
                                                {{ $detail->produk->nama_produk }}
                                            </a>
                                        </div>
                                        <div class="product-category">
                                            {{ $detail->produk->kategori->nama_kategori ?? 'Kategori' }}</div>
                                        <div class="product-price">Rp
                                            {{ number_format($detail->produk->Harga, 0, ',', '.') }} per item</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="product-quantity">Jumlah: {{ $detail->jumlah }}</div>
                                        <div class="product-subtotal">Rp
                                            {{ number_format($detail->sub_total, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="total-section">
                            <div class="total-row">
                                <span>Subtotal Produk</span>
                                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="total-row">
                                <span>Ongkos Kirim</span>
                                <span class="text-success">Gratis</span>
                            </div>
                            <div class="total-row final">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="section-card" data-aos="fade-right" data-aos-delay="100">
                        <div class="section-header">
                            <i class="fas fa-truck me-2"></i>Informasi Pengiriman
                        </div>
                        <div class="section-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Alamat Pengiriman</span>
                                    <span class="info-value">{{ $pesanan->alamat_pengiriman }}</span>
                                </div>
                                @if ($pesanan->no_telepon)
                                    <div class="info-item">
                                        <span class="info-label">Nomor Telepon</span>
                                        <span class="info-value">{{ $pesanan->no_telepon }}</span>
                                    </div>
                                @endif
                                @if ($pesanan->catatan)
                                    <div class="info-item">
                                        <span class="info-label">Catatan Pesanan</span>
                                        <span class="info-value">{{ $pesanan->catatan }}</span>
                                    </div>
                                @endif
                                @if ($pesanan->nomor_resi)
                                    <div class="info-item">
                                        <span class="info-label">Nomor Resi</span>
                                        <span class="info-value">{{ $pesanan->nomor_resi }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Review Section for Completed Orders -->
                    @if ($pesanan->status_pesanan === 'completed')
                        <div class="section-card" data-aos="fade-right" data-aos-delay="200">
                            <div class="section-header">
                                <i class="fas fa-star me-2"></i>Berikan Ulasan
                            </div>
                            <div class="section-body">
                                <p class="mb-4">Bagaimana pengalaman Anda dengan produk ini? Ulasan Anda sangat membantu
                                    pembeli lain.</p>

                                @foreach ($pesanan->detailPesanan as $detail)
                                    @php
                                        $hasReviewed = App\Models\Testimoni::where('idUser', Auth::id())
                                            ->where('idProduk', $detail->idProduk)
                                            ->exists();
                                    @endphp

                                    <div class="review-item mb-4 p-3 border rounded">
                                        <div class="d-flex align-items-start gap-3">
                                            @if ($detail->produk->foto && file_exists(public_path('images/' . $detail->produk->foto)))
                                                <img src="{{ asset('images/' . $detail->produk->foto) }}"
                                                    alt="{{ $detail->produk->nama_produk }}" class="rounded"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif

                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('product.detail', $detail->produk->idProduk) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ $detail->produk->nama_produk }}
                                                    </a>
                                                </h6>
                                                <small
                                                    class="text-muted">{{ $detail->produk->kategori->nama_kategori ?? 'Kategori' }}</small>

                                                @if ($hasReviewed)
                                                    <div class="mt-2">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Sudah diulas
                                                        </span>
                                                    </div>
                                                @else
                                                    <!-- Review Form -->
                                                    <form action="{{ route('testimonial.store') }}" method="POST"
                                                        class="mt-3 review-form-{{ $detail->idProduk }}">
                                                        @csrf
                                                        <input type="hidden" name="idProduk"
                                                            value="{{ $detail->idProduk }}">

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Rating *</label>
                                                                <select name="rating"
                                                                    class="form-select @error('rating') is-invalid @enderror"
                                                                    required>
                                                                    <option value="">Pilih Rating</option>
                                                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus</option>
                                                                    <option value="4">⭐⭐⭐⭐ Bagus</option>
                                                                    <option value="3">⭐⭐⭐ Cukup</option>
                                                                    <option value="2">⭐⭐ Kurang</option>
                                                                    <option value="1">⭐ Sangat Kurang</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Ulasan Anda *</label>
                                                            <textarea name="komentar" class="form-control @error('komentar') is-invalid @enderror" rows="3" required
                                                                placeholder="Ceritakan pengalaman Anda dengan produk ini..."></textarea>
                                                            <small class="form-text text-muted">Minimal 10 karakter</small>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <!-- Order Status Timeline -->
                    <div class="section-card" data-aos="fade-left">
                        <div class="section-header">
                            <i class="fas fa-history me-2"></i>Status Pesanan
                        </div>
                        <div class="section-body">
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-content">
                                        <div class="timeline-title">Pesanan Dibuat</div>
                                        <div class="timeline-date">{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                @if (in_array($pesanan->status_pesanan, ['waiting_confirmation', 'confirmed', 'shipped', 'delivered', 'completed']))
                                    <div class="timeline-item active">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pembayaran Diterima</div>
                                            <div class="timeline-date">Bukti pembayaran telah diupload</div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array($pesanan->status_pesanan, ['confirmed', 'shipped', 'delivered', 'completed']))
                                    <div class="timeline-item active">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pesanan Dikonfirmasi</div>
                                            <div class="timeline-date">Pesanan telah dikonfirmasi admin</div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array($pesanan->status_pesanan, ['shipped', 'delivered', 'completed']))
                                    <div class="timeline-item active">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pesanan Dikirim</div>
                                            <div class="timeline-date">Produk sedang dalam perjalanan</div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array($pesanan->status_pesanan, ['delivered', 'completed']))
                                    <div class="timeline-item active">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pesanan Selesai</div>
                                            <div class="timeline-date">Produk telah diterima</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons" data-aos="fade-left" data-aos-delay="100">
                        @if ($pesanan->status_pesanan === 'waiting_payment')
                            <a href="{{ route('payment.upload', $pesanan->idPesanan) }}" class="btn-primary-custom">
                                <i class="fas fa-upload me-2"></i>Upload Pembayaran
                            </a>
                        @endif
                        <a href="{{ route('user.orders.index') }}" class="btn-secondary-custom">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
                        </a>
                    </div>

                    <!-- Help Section -->
                    <div class="section-card mt-3" data-aos="fade-left" data-aos-delay="200">
                        <div class="section-body">
                            <h6><i class="fas fa-question-circle me-2"></i>Butuh Bantuan?</h6>
                            <p class="small text-muted mb-3">
                                Jika ada pertanyaan tentang pesanan Anda, jangan ragu untuk menghubungi customer service
                                kami.
                            </p>
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-phone me-2"></i>Hubungi Customer Service
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
