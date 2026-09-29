@extends('layouts.frontend')

@section('title', 'Recently Viewed Products')
@section('description', 'Browse products you\'ve recently viewed and continue shopping your favorite Indonesian
    handicrafts.')

    @push('styles')
        <style>
            .recently-viewed-header {
                background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
                position: relative;
                overflow: hidden;
                margin-bottom: 40px;
            }

            .recently-viewed-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');
            }

            .product-card {
                background: #fff;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                transition: all 0.3s ease;
                position: relative;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }

            .product-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .product-card:hover .product-image {
                transform: scale(1.05);
            }

            .product-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 0.8rem;
                z-index: 10;
            }

            .product-actions {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 20;
            }

            .product-card:hover .product-actions {
                opacity: 1;
            }

            .empty-state {
                text-align: center;
                padding: 80px 20px;
                background: #f8f9fa;
                border-radius: 15px;
                margin: 40px 0;
            }

            .empty-state i {
                font-size: 4rem;
                color: #dee2e6;
                margin-bottom: 20px;
            }

            .breadcrumb {
                background: none;
                padding: 0;
                margin-bottom: 20px;
            }

            .breadcrumb-item+.breadcrumb-item::before {
                content: '›';
                font-weight: bold;
                color: #6c757d;
            }

            .breadcrumb-item a {
                color: var(--primary-color);
                text-decoration: none;
            }

            .breadcrumb-item.active {
                color: #6c757d;
            }

            .filter-sort-bar {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 30px;
            }

            .view-toggle {
                border: none;
                background: #e9ecef;
                color: #6c757d;
                padding: 8px 12px;
                margin: 0 2px;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .view-toggle.active {
                background: var(--primary-color);
                color: white;
            }

            .clear-history-btn {
                background: #dc3545;
                border: none;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .clear-history-btn:hover {
                background: #c82333;
                transform: translateY(-2px);
            }

            .list-view .product-card {
                display: flex;
                flex-direction: row;
                margin-bottom: 20px;
            }

            .list-view .product-image {
                width: 200px;
                height: 150px;
                flex-shrink: 0;
            }

            .list-view .card-body {
                flex: 1;
                padding: 20px;
            }

            .last-viewed {
                font-size: 0.85rem;
                color: #6c757d;
                margin-top: 10px;
            }

            @media (max-width: 768px) {
                .list-view .product-card {
                    flex-direction: column;
                }

                .list-view .product-image {
                    width: 100%;
                    height: 200px;
                }

                .filter-sort-bar {
                    padding: 15px;
                }

                .filter-sort-bar .row>* {
                    margin-bottom: 10px;
                }
            }
        </style>
    @endpush

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Recently Viewed</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="recently-viewed-header text-white py-5 px-4 rounded">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h1 class="display-5 mb-3">
                        <i class="fas fa-history me-3"></i>Recently Viewed Products
                    </h1>
                    <p class="lead mb-0">
                        Continue shopping from where you left off. Your recently viewed products are saved here for easy
                        access.
                    </p>
                </div>
                <div class="col-lg-4 text-center" data-aos="fade-left" data-aos-delay="100">
                    <div class="d-inline-block">
                        <div class="display-4 mb-2">{{ $products->count() }}</div>
                        <div class="h5">Products Viewed</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($products->count() > 0)
            <!-- Filter and Sort Bar -->
            <div class="filter-sort-bar" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-3 fw-semibold">View:</span>
                            <div class="btn-group" role="group">
                                <button type="button" class="view-toggle active" onclick="switchView('grid')"
                                    data-bs-toggle="tooltip" title="Grid View">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="view-toggle" onclick="switchView('list')"
                                    data-bs-toggle="tooltip" title="List View">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <span class="text-muted">{{ $products->count() }} items</span>
                            <button class="clear-history-btn" onclick="clearRecentlyViewed()" data-bs-toggle="tooltip"
                                title="Clear all recently viewed products">
                                <i class="fas fa-trash-alt me-2"></i>Clear History
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="productsContainer" class="grid-view">
                <div class="row g-4" id="productsGrid">
                    @foreach ($products as $product)
                        @php
                            $promotion = $product->promosi
                                ->where('status', 'aktif')
                                ->where('tanggal_mulai', '<=', now())
                                ->where('tanggal_akhir', '>=', now())
                                ->first();
                            $originalPrice = $product->Harga;
                            $finalPrice = $promotion
                                ? $originalPrice - ($originalPrice * $promotion->diskon) / 100
                                : $originalPrice;
                        @endphp

                        <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="card product-card h-100">
                                <div class="position-relative overflow-hidden">
                                    @if ($product->foto)
                                        <img src="{{ Storage::url($product->foto) }}" alt="{{ $product->nama_produk }}"
                                            class="product-image">
                                    @else
                                        <div
                                            class="product-image d-flex align-items-center justify-content-center bg-light text-muted">
                                            <div class="text-center">
                                                <i class="fas fa-image fa-2x mb-2"></i>
                                                <div class="small">{{ Str::limit($product->nama_produk, 20) }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Product Badge -->
                                    @if ($promotion)
                                        <div class="product-badge bg-danger">
                                            -{{ $promotion->diskon }}%
                                        </div>
                                    @elseif($product->created_at && $product->created_at->diffInDays() < 7)
                                        <div class="product-badge bg-primary">
                                            New
                                        </div>
                                    @endif

                                    <!-- Product Actions (on hover) -->
                                    <div class="product-actions">
                                        <a href="{{ route('product.detail', $product->idProduk) }}"
                                            class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip"
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-light btn-sm"
                                            onclick="removeFromRecentlyViewed({{ $product->idProduk }})"
                                            data-bs-toggle="tooltip" title="Remove from Recently Viewed">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span
                                            class="badge bg-secondary small">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                                    </div>

                                    <h6 class="card-title mb-2">
                                        <a href="{{ route('product.detail', $product->idProduk) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $product->nama_produk }}
                                        </a>
                                    </h6>

                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($product->deskripsi ?? 'Beautiful Indonesian handicraft', 80) }}
                                    </p>

                                    <div class="price-section mb-3">
                                        @if ($promotion)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted text-decoration-line-through small">
                                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                                </span>
                                                <span class="text-danger fw-bold">
                                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="text-success small">
                                                You save: Rp {{ number_format($originalPrice - $finalPrice, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="fw-bold text-primary">
                                                Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-auto">
                                        <div class="d-grid gap-2 mb-2">
                                            <a href="{{ route('product.detail', $product->idProduk) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-box me-1"></i>
                                                {{ $product->stok }} left
                                            </small>
                                            @if ($product->stok < 5)
                                                <span class="badge bg-warning text-dark small">Low Stock</span>
                                            @endif
                                        </div>

                                        <div class="last-viewed mt-2">
                                            <i class="fas fa-clock me-1"></i>
                                            <span>Recently viewed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Continue Shopping Section -->
            <div class="row mt-5">
                <div class="col-12" data-aos="fade-up">
                    <div class="text-center p-4 bg-light rounded">
                        <h4 class="mb-3">Continue Shopping</h4>
                        <p class="text-muted mb-4">Discover more amazing Indonesian handicrafts in our collections</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('shop') }}" class="btn btn-primary">
                                <i class="fas fa-store me-2"></i>Browse All Products
                            </a>
                            <a href="{{ route('promotions') }}" class="btn btn-outline-danger">
                                <i class="fas fa-fire me-2"></i>View Promotions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state" data-aos="fade-up">
                <i class="fas fa-history"></i>
                <h3 class="mb-3">No Recently Viewed Products</h3>
                <p class="text-muted mb-4">
                    You haven't viewed any products yet. Start exploring our collection of beautiful Indonesian handicrafts!
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-store me-2"></i>Start Shopping
                    </a>
                    <a href="{{ route('promotions') }}" class="btn btn-outline-danger btn-lg">
                        <i class="fas fa-fire me-2"></i>View Promotions
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });
        });

        // Switch between grid and list view
        function switchView(viewType) {
            const container = document.getElementById('productsContainer');
            const buttons = document.querySelectorAll('.view-toggle');

            // Remove active class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            event.target.closest('.view-toggle').classList.add('active');

            // Switch view
            if (viewType === 'list') {
                container.classList.remove('grid-view');
                container.classList.add('list-view');
            } else {
                container.classList.remove('list-view');
                container.classList.add('grid-view');
            }
        }

        // Clear recently viewed products
        function clearRecentlyViewed() {
            if (confirm('Are you sure you want to clear all recently viewed products? This action cannot be undone.')) {
                // Show loading state
                const btn = document.querySelector('.clear-history-btn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Clearing...';
                btn.disabled = true;

                // Make AJAX request to clear session
                fetch('/clear-recently-viewed', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page to show empty state
                            window.location.reload();
                        } else {
                            toastr.error('Failed to clear recently viewed products');
                            btn.innerHTML = originalContent;
                            btn.disabled = false;
                        }
                    })
                    .catch(error => {
                        toastr.error('An error occurred while clearing recently viewed products');
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    });
            }
        }

        // Remove single product from recently viewed
        function removeFromRecentlyViewed(productId) {
            if (confirm('Remove this product from recently viewed?')) {
                // Make AJAX request to remove product
                fetch('/remove-from-recently-viewed', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove product card with animation
                            const productCard = document.querySelector(`[data-product-id="${productId}"]`)?.closest(
                                '.col-lg-3, .col-md-4, .col-sm-6');
                            if (productCard) {
                                productCard.style.transition = 'all 0.3s ease';
                                productCard.style.opacity = '0';
                                productCard.style.transform = 'translateY(-20px)';

                                setTimeout(() => {
                                    productCard.remove();

                                    // If no products left, reload to show empty state
                                    if (document.querySelectorAll('#productsGrid > div').length === 0) {
                                        window.location.reload();
                                    }
                                }, 300);
                            }

                            toastr.success('Product removed from recently viewed');
                        } else {
                            toastr.error('Failed to remove product');
                        }
                    })
                    .catch(error => {
                        toastr.error('An error occurred while removing the product');
                    });
            }
        }
    </script>
@endpush
