@extends('layouts.frontend')

@section('title', 'Pembayaran - ')
@section('description', 'Selesaikan pesanan Anda dengan transfer bank')

@push('styles')
    <style>
        .checkout-container {
            background-color: var(--bg-light);
            min-height: 70vh;
            padding: 2rem 0;
        }

        .checkout-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--border-color);
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .step.active .step-number {
            background: var(--primary-color);
            color: white;
        }

        .step.completed .step-number {
            background: var(--accent-color);
            color: white;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .section-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 0.5rem;
            color: var(--primary-color);
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
            border-radius: 8px;
            margin-right: 1rem;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .item-price {
            color: var(--primary-color);
            font-weight: 600;
        }

        .item-quantity {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
        }

        .payment-method {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            background-color: var(--bg-light);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background-color: rgba(200, 16, 46, 0.05);
        }

        .payment-method input[type="radio"] {
            margin-right: 0.75rem;
        }

        .payment-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: var(--primary-color);
        }

        .order-summary {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            position: sticky;
            top: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-row:last-of-type {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-color);
        }

        .place-order-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .place-order-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .security-info {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }

        .security-info i {
            color: #28a745;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-direction: column;
                align-items: center;
            }

            .step {
                margin: 0.5rem 0;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        .loading-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            max-width: 300px;
        }

        .loading-spinner {
            font-size: 3rem;
            color: var(--primary-color);
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <div class="container">
            <!-- Page Header -->
            <div class="checkout-card">
                <div class="checkout-header" data-aos="fade-down">
                    <h2><i class="fas fa-credit-card me-2"></i>Pembayaran</h2>
                    <p class="mb-0">Selesaikan pesanan Anda dengan transfer bank</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator" data-aos="fade-up">
                    <div class="step completed">
                        <div class="step-number">1</div>
                        <span>Review Keranjang</span>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <span>Pembayaran</span>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <span>Transfer Bank</span>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <span>Konfirmasi</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('order.place') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row">
                    <!-- Checkout Form -->
                    <div class="col-lg-8">
                        <!-- Order Items Review -->
                        <div class="section-card" data-aos="fade-right">
                            <h4 class="section-title">
                                <i class="fas fa-shopping-bag"></i>Item Pesanan ({{ count($cart) }} item)
                            </h4>
                            @foreach ($cart as $idProduk => $item)
                                <div class="order-item">
                                    @if (isset($item['foto']) && $item['foto'] && file_exists(public_path('images/' . $item['foto'])))
                                        <img src="{{ asset('images/' . $item['foto']) }}"
                                            alt="{{ $item['nama_produk'] ?? 'Product' }}" class="item-image">
                                    @else
                                        <div class="item-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-image text-muted fa-2x"></i>
                                        </div>
                                    @endif
                                    <div class="item-info">
                                        <div class="item-name">{{ $item['nama_produk'] ?? 'Product Name' }}</div>
                                        <div class="item-price">Rp {{ number_format($item['Harga'] ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="item-quantity">Jumlah: {{ $item['jumlah'] ?? 1 }}</div>
                                    </div>
                                    <div class="item-total">
                                        <strong>Rp
                                            {{ number_format(($item['Harga'] ?? 0) * ($item['jumlah'] ?? 1), 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Shipping Information -->
                        <div class="section-card" data-aos="fade-right" data-aos-delay="100">
                            <h4 class="section-title">
                                <i class="fas fa-truck"></i>Informasi Pengiriman
                            </h4>
                            <div class="form-group">
                                <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman <span
                                        class="text-danger">*</span></label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control" rows="4" required
                                    placeholder="Masukkan alamat lengkap untuk pengiriman...">{{ old('alamat_pengiriman') }}</textarea>
                                @error('alamat_pengiriman')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telepon" class="form-label">Nomor Telepon</label>
                                        <input type="tel" name="no_telepon" id="no_telepon" class="form-control"
                                            value="{{ Auth::user()->no_telp ?? old('no_telepon') }}"
                                            placeholder="Masukkan nomor telepon Anda">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="catatan" class="form-label">Catatan Pesanan (Opsional)</label>
                                        <input type="text" name="catatan" id="catatan" class="form-control"
                                            value="{{ old('catatan') }}" placeholder="Instruksi khusus...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="section-card" data-aos="fade-right" data-aos-delay="200">
                            <h4 class="section-title">
                                <i class="fas fa-university"></i>Metode Pembayaran
                            </h4>
                            <div class="payment-methods">
                                <div class="payment-method selected" onclick="selectPayment('transfer_bank')">
                                    <input type="radio" name="metode_pembayaran" value="transfer_bank" id="transfer_bank"
                                        required checked>
                                    <i class="fas fa-university payment-icon"></i>
                                    <div class="payment-info">
                                        <strong>Transfer Bank</strong>
                                        <p class="mb-0 text-muted">Transfer ke rekening bank kami dan upload bukti transfer
                                        </p>
                                    </div>
                                </div>

                                <!-- Bank Account Information -->
                                <div class="alert alert-info mt-3">
                                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Rekening Bank</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Bank BCA</strong><br>
                                            <span>No. Rek: 1234567890</span><br>
                                            <span>a/n PT </span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Bank Mandiri</strong><br>
                                            <span>No. Rek: 0987654321</span><br>
                                            <span>a/n PT </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <small class="text-muted">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Setelah melakukan pembayaran, Anda akan diminta untuk mengupload bukti transfer.
                                    </small>
                                </div>
                            </div>
                            @error('metode_pembayaran')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="order-summary" data-aos="fade-left">
                            <h4 class="mb-4"><i class="fas fa-calculator me-2"></i>Ringkasan Pesanan</h4>

                            <div class="summary-row">
                                <span>Item ({{ count($cart) }})</span>
                                <span>Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Ongkos Kirim</span>
                                <span class="text-success">Gratis</span>
                            </div>

                            <div class="summary-row">
                                <span>Biaya Layanan</span>
                                <span>Rp 0</span>
                            </div>

                            <div class="summary-row">
                                <strong>Total Pembayaran</strong>
                                <strong>Rp {{ number_format($total_harga, 0, ',', '.') }}</strong>
                            </div>

                            <button type="submit" class="place-order-btn" id="placeOrderBtn">
                                <i class="fas fa-shopping-cart me-2"></i>Buat Pesanan
                            </button>

                            <div class="security-info">
                                <i class="fas fa-shield-alt"></i>
                                <small>Informasi pembayaran Anda aman dan terenkripsi</small>
                            </div>

                            <!-- Back to Cart -->
                            <div class="text-center mt-3">
                                <a href="{{ route('cart') }}" class="text-decoration-none text-muted">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <i class="fas fa-spinner"></i>
            </div>
            <h5>Memproses Pesanan Anda...</h5>
            <p class="text-muted mb-0">Mohon tunggu sebentar</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            $('#checkoutForm').on('submit', function(e) {
                const deliveryAddress = $('#alamat_pengiriman').val().trim();
                const paymentMethod = $('input[name="metode_pembayaran"]:checked').val();

                if (!deliveryAddress) {
                    e.preventDefault();
                    toastr.error('Harap masukkan alamat pengiriman Anda', 'Error');
                    $('#alamat_pengiriman').focus();
                    return;
                }

                if (!paymentMethod) {
                    e.preventDefault();
                    toastr.error('Harap pilih metode pembayaran', 'Error');
                    return;
                }

                // Show loading overlay
                $('#loadingOverlay').fadeIn();
                $('#placeOrderBtn').html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...').prop(
                    'disabled', true);
            });

            // Auto-fill user data if available
            @auth
            @if (Auth::user()->alamat)
                $('#alamat_pengiriman').val('{{ Auth::user()->alamat }}');
            @endif
        @endauth
        });

        function selectPayment(method) {
            // Remove selected class from all payment methods
            $('.payment-method').removeClass('selected');

            // Add selected class to clicked method
            $(`#${method}`).prop('checked', true);
            $(`#${method}`).closest('.payment-method').addClass('selected');
        }

        // Address validation
        $('#alamat_pengiriman').on('blur', function() {
            const address = $(this).val().trim();
            if (address.length < 20) {
                toastr.warning('Harap berikan alamat yang lebih detail untuk pengiriman yang akurat',
                    'Pemberitahuan Alamat');
            }
        });

        // Phone number formatting
        $('#no_telepon').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }
            if (value.length > 13) {
                value = value.substring(0, 13);
            }
            $(this).val(value);
        });

        // Prevent accidental page leave
        let formSubmitted = false;
        $('#checkoutForm').on('submit', function() {
            formSubmitted = true;
        });

        $(window).on('beforeunload', function(e) {
            if (!formSubmitted && ($('#alamat_pengiriman').val().trim() !== '' || $(
                    'input[name="metode_pembayaran"]:checked').length > 0)) {
                return 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman?';
            }
        });
    </script>
@endpush
