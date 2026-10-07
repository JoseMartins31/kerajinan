@extends('layouts.frontend')

@section('title', 'Shopping Cart - Kerajinan Daun Lontar')
@section('description', 'Review and manage your shopping cart items before checkout')

@push('styles')
    <style>
        .cart-container {
            background-color: var(--bg-light);
            min-height: 70vh;
            padding: 2rem 0;
        }

        .cart-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .cart-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .cart-item {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: var(--bg-light);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .product-category {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1rem 0;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 60px;
            height: 35px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .quantity-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .remove-btn {
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            color: #c82333;
            transform: scale(1.1);
        }

        .cart-summary {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 2rem;
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

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-color);
        }

        .checkout-btn {
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

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .continue-shopping {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            color: var(--primary-dark);
            transform: translateX(-5px);
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-cart-icon {
            font-size: 5rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-cart h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .empty-cart p {
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .cart-stats {
            background: linear-gradient(135deg, var(--accent-color), #e76f51);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .cart-stats .row>div {
            text-align: center;
        }

        .cart-stats h4 {
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .cart-stats p {
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .cart-item .d-flex {
                flex-direction: column !important;
                align-items: center !important;
            }

            .product-image {
                margin-bottom: 1rem;
            }

            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="cart-container">
        <div class="container">
            <!-- Page Header -->
            <div class="cart-card">
                <div class="cart-header" data-aos="fade-down">
                    <h2><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h2>
                    <p class="mb-0">Review your selected items and proceed to checkout</p>
                </div>

                @if (empty($cart))
                    <!-- Empty Cart State -->
                    <div class="empty-cart" data-aos="fade-up">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Your cart is empty</h3>
                        <p>Looks like you haven't added any products to your cart yet.</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-store me-2"></i>Start Shopping
                        </a>
                    </div>
                @else
                    <div class="row">
                        <!-- Cart Items -->
                        <div class="col-lg-8">
                            <!-- Cart Statistics -->
                            <div class="cart-stats" data-aos="fade-right">
                                <div class="row">
                                    <div class="col-4">
                                        <h4>{{ count($cart) }}</h4>
                                        <p>Items in Cart</p>
                                    </div>
                                    <div class="col-4">
                                        <h4>{{ array_sum(array_column($cart, 'jumlah')) }}</h4>
                                        <p>Total Quantity</p>
                                    </div>
                                    <div class="col-4">
                                        <h4>Rp {{ number_format($total_harga, 0, ',', '.') }}</h4>
                                        <p>Total Value</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Items List -->
                            @foreach ($cart as $idProduk => $item)
                                <div class="cart-item p-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="d-flex align-items-center">
                                        <!-- Product Image -->
                                        <div class="me-4">
                                            @if (isset($item['foto']) && $item['foto'])
                                                @if (Str::startsWith($item['foto'], ['http', '/storage']))
                                                    {{-- Full URL or Storage URL --}}
                                                    <img src="{{ $item['foto'] }}"
                                                        alt="{{ $item['nama_produk'] ?? 'Product' }}" class="product-image">
                                                @else
                                                    {{-- Legacy path handling --}}
                                                    <img src="{{ Storage::url($item['foto']) }}"
                                                        alt="{{ $item['nama_produk'] ?? 'Product' }}" class="product-image">
                                                @endif
                                            @else
                                                <div
                                                    class="product-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="product-info">
                                            <h5 class="product-name">{{ $item['nama_produk'] ?? 'Product Name' }}</h5>
                                            <div class="product-category">
                                                <i class="fas fa-tag me-1"></i>{{ $item['kategori'] ?? 'Uncategorized' }}
                                            </div>
                                            <div class="product-price">Rp
                                                {{ number_format($item['Harga'] ?? 0, 0, ',', '.') }}
                                            </div>

                                            <!-- Quantity Controls -->
                                            <form action="{{ route('cart.update') }}" method="POST"
                                                class="update-cart-form">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="idProduk" value="{{ $idProduk }}">
                                                <div class="quantity-controls">
                                                    <button type="button" class="quantity-btn decrease-qty"
                                                        data-product="{{ $idProduk }}">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="jumlah" value="{{ $item['jumlah'] ?? 1 }}"
                                                        min="1" max="99" class="form-control quantity-input"
                                                        data-product="{{ $idProduk }}">
                                                    <button type="button" class="quantity-btn increase-qty"
                                                        data-product="{{ $idProduk }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                                        <i class="fas fa-sync-alt me-1"></i>Update
                                                    </button>
                                                </div>
                                            </form>

                                            <!-- Subtotal -->
                                            <div class="mt-2">
                                                <strong>Subtotal: Rp
                                                    {{ number_format(($item['Harga'] ?? 0) * ($item['jumlah'] ?? 0), 0, ',', '.') }}</strong>
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="ms-3">
                                            <form action="{{ route('cart.remove', $idProduk) }}" method="POST"
                                                class="remove-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 remove-btn"
                                                    onclick="return confirm('Are you sure you want to remove this item from cart?')"
                                                    title="Remove from cart">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Cart Summary -->
                        <div class="col-lg-4">
                            <div class="cart-summary" data-aos="fade-left">
                                <h4 class="mb-4"><i class="fas fa-calculator me-2"></i>Order Summary</h4>

                                <div class="summary-row">
                                    <span>Items ({{ count($cart) }})</span>
                                    <span>Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                                </div>

                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span class="text-success">Free</span>
                                </div>

                                <div class="summary-row">
                                    <span>Tax</span>
                                    <span>Rp 0</span>
                                </div>

                                <div class="summary-row">
                                    <strong>Total</strong>
                                    <strong>Rp {{ number_format($total_harga, 0, ',', '.') }}</strong>
                                </div>

                                <!-- Checkout Button -->
                                @auth
                                    <button type="button" class="checkout-btn" onclick="proceedToCheckout()">
                                        <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="checkout-btn text-center text-decoration-none d-block">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login to Checkout
                                    </a>
                                @endauth

                                <!-- Continue Shopping -->
                                <div class="text-center">
                                    <a href="{{ route('shop') }}" class="continue-shopping">
                                        <i class="fas fa-arrow-left"></i>Continue Shopping
                                    </a>
                                </div>

                                <!-- Security Info -->
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Secure checkout guaranteed
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recently Viewed Products -->
            @if (!empty($cart))
                <div class="row mt-5" data-aos="fade-up">
                    <div class="col-12">
                        <div class="cart-card">
                            <div class="p-4">
                                <h4 class="mb-4"><i class="fas fa-eye me-2"></i>You might also like</h4>
                                <div class="row">
                                    <!-- This section can be populated with related products -->
                                    <div class="col-12 text-center text-muted">
                                        <p>Related products will appear here</p>
                                        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-search me-2"></i>Browse All Products
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Quantity control handlers
            $('.increase-qty').on('click', function() {
                const productId = $(this).data('product');
                const input = $(`input[data-product="${productId}"]`);
                const currentValue = parseInt(input.val());
                const maxValue = parseInt(input.attr('max'));

                if (currentValue < maxValue) {
                    input.val(currentValue + 1);
                    updateQuantity(productId, currentValue + 1);
                }
            });

            $('.decrease-qty').on('click', function() {
                const productId = $(this).data('product');
                const input = $(`input[data-product="${productId}"]`);
                const currentValue = parseInt(input.val());
                const minValue = parseInt(input.attr('min'));

                if (currentValue > minValue) {
                    input.val(currentValue - 1);
                    updateQuantity(productId, currentValue - 1);
                }
            });

            // Direct input change handler
            $('.quantity-input').on('change', function() {
                const productId = $(this).data('product');
                const newValue = parseInt($(this).val());
                const minValue = parseInt($(this).attr('min'));
                const maxValue = parseInt($(this).attr('max'));

                // Validate input
                if (newValue < minValue) {
                    $(this).val(minValue);
                    updateQuantity(productId, minValue);
                } else if (newValue > maxValue) {
                    $(this).val(maxValue);
                    updateQuantity(productId, maxValue);
                } else {
                    updateQuantity(productId, newValue);
                }
            });

            // Auto-update cart without form submission
            function updateQuantity(productId, quantity) {
                // Optional: Add auto-update functionality via AJAX
                console.log(`Product ${productId} quantity changed to ${quantity}`);
            }

            // Smooth form submissions
            $('.update-cart-form').on('submit', function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
                submitBtn.prop('disabled', true);

                // Allow form to submit normally
                setTimeout(() => {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }, 1000);
            });

            // Remove item confirmation
            $('.remove-form').on('submit', function(e) {
                const confirmed = confirm('Are you sure you want to remove this item from your cart?');
                if (!confirmed) {
                    e.preventDefault();
                    return false;
                }

                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i>');
                submitBtn.prop('disabled', true);
            });
        });

        // Proceed to checkout function
        function proceedToCheckout() {
            // Show loading state
            const btn = event.target;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            btn.disabled = true;

            // Simulate processing time
            setTimeout(() => {
                window.location.href = '{{ route('checkout') }}';
            }, 1000);
        }

        // Update cart count in navigation
        function updateCartCount() {
            @auth
            csrfFetch('/api/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartBadge = document.getElementById('cartCount');
                    if (cartBadge && data.count !== undefined) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = data.count > 0 ? 'inline' : 'none';
                    }
                })
                .catch(error => {
                    console.log('Error fetching cart count:', error);
                });
        @endauth
        }

        // Update cart count on page load
        $(document).ready(function() {
            updateCartCount();
        });
    </script>
@endpush
