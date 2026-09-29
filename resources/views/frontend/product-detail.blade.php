@extends('layouts.frontend')

@section('title', $product->nama_produk)
@section('description', Str::limit(strip_tags($product->deskripsi ?? 'Beautiful Indonesian handicraft'), 155))

@push('styles')
    <style>
        .product-gallery {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 15px;
            cursor: zoom-in;
            transition: all 0.3s ease;
        }

        .main-image:hover {
            transform: scale(1.02);
        }

        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .product-info {
            padding: 20px 0;
        }

        .product-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 1.1rem;
        }

        .rating-text {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .price-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0;
        }

        .original-price {
            font-size: 1.5rem;
            color: #6c757d;
            text-decoration: line-through;
            margin: 0;
        }

        .discount-badge {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 10px;
        }

        .savings-amount {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 10px;
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .stock-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
        }

        .stock-indicator.low {
            background: #ffc107;
        }

        .stock-indicator.out {
            background: #dc3545;
        }

        .product-actions {
            margin: 30px 0;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-input {
            width: 80px;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            border-radius: 8px;
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

        .product-details-tabs {
            margin: 40px 0;
        }

        .tab-content {
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .artisan-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
        }

        .artisan-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
        }

        .related-products {
            margin: 60px 0;
        }

        .related-product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .related-product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .review-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .review-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 10px;
        }

        .reviewer-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .review-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .review-stars {
            color: #ffc107;
            margin: 5px 0;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 20px 0;
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

        .zoom-modal .modal-body {
            padding: 0;
        }

        .zoom-modal img {
            width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.5rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .original-price {
                font-size: 1.2rem;
            }

            .main-image {
                height: 300px;
            }

            .quantity-selector {
                flex-direction: column;
                align-items: flex-start;
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
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('category', $product->idKategori) }}">{{ $product->kategori->nama_kategori ?? 'Category' }}</a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit($product->nama_produk, 50) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="product-gallery">
                    @if ($product->foto)
                        <img src="{{ Storage::url($product->foto) }}" alt="{{ $product->nama_produk }}" class="main-image"
                            id="mainImage" onclick="openImageZoom(this.src)">
                    @else
                        <div class="main-image d-flex align-items-center justify-content-center bg-light text-muted">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <h5>{{ $product->nama_produk }}</h5>
                                <p class="text-muted">Image not available</p>
                            </div>
                        </div>
                    @endif

                    <!-- Thumbnail Gallery (placeholder for multiple images) -->
                    <div class="thumbnail-gallery">
                        @if ($product->foto)
                            <img src="{{ Storage::url($product->foto) }}" alt="{{ $product->nama_produk }}"
                                class="thumbnail active" onclick="changeMainImage(this.src)">
                        @endif
                        <!-- Placeholder for additional images -->
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                <div class="product-info">
                    <!-- Title and Category -->
                    <h1 class="product-title">{{ $product->nama_produk }}</h1>
                    <div class="mb-3">
                        <span
                            class="badge bg-primary fs-6">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                    </div>

                    <!-- Rating -->
                    <div class="product-rating">
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">
                            {{ number_format($averageRating, 1) }} ({{ $totalReviews }}
                            {{ $totalReviews == 1 ? 'review' : 'reviews' }})
                        </span>
                    </div>

                    <!-- Pricing -->
                    <div class="price-section">
                        @php
                            $promotion = $product->promosi->first();
                            $originalPrice = $product->Harga;
                            $discountedPrice = $promotion
                                ? $originalPrice - ($originalPrice * $promotion->diskon) / 100
                                : $originalPrice;
                            $savings = $originalPrice - $discountedPrice;
                        @endphp

                        @if ($promotion)
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <p class="current-price mb-0">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</p>
                                <p class="original-price mb-0">Rp {{ number_format($originalPrice, 0, ',', '.') }}</p>
                            </div>
                            <span class="discount-badge">
                                <i class="fas fa-fire me-1"></i>{{ $promotion->diskon }}% OFF
                            </span>
                            <div class="savings-amount">
                                <i class="fas fa-tag me-2"></i>You save: Rp {{ number_format($savings, 0, ',', '.') }}
                            </div>
                            @if ($promotion->tanggal_akhir)
                                <div class="mt-2 text-danger">
                                    <i class="fas fa-clock me-1"></i>
                                    <small>Offer ends:
                                        {{ \Carbon\Carbon::parse($promotion->tanggal_akhir)->format('M d, Y') }}</small>
                                </div>
                            @endif
                        @else
                            <p class="current-price mb-0">Rp {{ number_format($originalPrice, 0, ',', '.') }}</p>
                        @endif
                    </div>

                    <!-- Stock Information -->
                    <div class="stock-info">
                        @if ($product->stok > 10)
                            <div class="stock-indicator"></div>
                            <span class="text-success fw-bold">In Stock ({{ $product->stok }} available)</span>
                        @elseif($product->stok > 0)
                            <div class="stock-indicator low"></div>
                            <span class="text-warning fw-bold">Only {{ $product->stok }} left in stock!</span>
                        @else
                            <div class="stock-indicator out"></div>
                            <span class="text-danger fw-bold">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Product Actions -->
                    @if ($product->stok > 0)
                        <div class="product-actions">
                            <div class="quantity-selector">
                                <label class="fw-bold">Quantity:</label>
                                <div class="d-flex align-items-center">
                                    <button class="quantity-btn" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" id="quantity" value="1"
                                        min="1" max="{{ $product->stok }}">
                                    <button class="quantity-btn" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                <button class="btn btn-primary btn-lg" onclick="addToCart({{ $product->idProduk }})">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                                <button class="btn btn-outline-danger btn-lg"
                                    onclick="addToWishlist({{ $product->idProduk }})">
                                    <i class="fas fa-heart me-2"></i>Add to Wishlist
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This product is currently out of stock. Please check back later.
                        </div>
                    @endif

                    <!-- Artisan Information -->
                    @if ($product->user)
                        <div class="artisan-info">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($product->user->username) }}&background=random"
                                    alt="{{ $product->user->username }}" class="artisan-avatar me-3">
                                <div>
                                    <h6 class="mb-1">Created by</h6>
                                    <h5 class="mb-0">{{ $product->user->username }}</h5>
                                    <small class="opacity-75">Indonesian Artisan</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-details-tabs" data-aos="fade-up" data-aos-delay="200">
            <ul class="nav nav-tabs nav-pills nav-justified" id="productTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#description">
                        <i class="fas fa-info-circle me-2"></i>Description
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specifications">
                        <i class="fas fa-list me-2"></i>Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#reviews">
                        <i class="fas fa-star me-2"></i>Reviews ({{ $totalReviews }})
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="mb-3">Product Description</h4>
                            <div class="product-description">
                                {!! nl2br(
                                    e(
                                        $product->deskripsi ??
                                            'This beautiful Indonesian handicraft is carefully crafted by skilled artisans using traditional techniques passed down through generations. Each piece is unique and represents the rich cultural heritage of Indonesia.',
                                    ),
                                ) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bg-light p-4 rounded">
                                <h6 class="mb-3">Product Features</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Authentic Indonesian
                                        handicraft</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Handmade with
                                        traditional techniques</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>High-quality
                                        materials</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unique cultural
                                        design</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Supporting local
                                        artisans</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div class="tab-pane fade" id="specifications">
                    <h4 class="mb-3">Product Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="fw-bold">Product Name</td>
                                    <td>{{ $product->nama_produk }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Category</td>
                                    <td>{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Artisan</td>
                                    <td>{{ $product->user->username ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Stock</td>
                                    <td>{{ $product->stok }} pieces available</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Added Date</td>
                                    <td>{{ $product->created_at ? $product->created_at->format('M d, Y') : 'Not specified' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded">
                                <h6 class="mb-3">Care Instructions</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-hand-paper text-primary me-2"></i>Handle with care
                                    </li>
                                    <li class="mb-2"><i class="fas fa-sun text-warning me-2"></i>Keep away from direct
                                        sunlight</li>
                                    <li class="mb-2"><i class="fas fa-tint text-info me-2"></i>Clean with dry cloth only
                                    </li>
                                    <li class="mb-2"><i class="fas fa-home text-success me-2"></i>Store in dry place
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="text-center p-4 bg-light rounded">
                                <div class="display-4 text-warning">{{ number_format($averageRating, 1) }}</div>
                                <div class="rating-stars fs-4 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted">Based on {{ $totalReviews }}
                                    {{ $totalReviews == 1 ? 'review' : 'reviews' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            @if ($testimonials->count() > 0)
                                @foreach ($testimonials as $testimonial)
                                    <div class="review-card">
                                        <div class="review-header">
                                            <div class="reviewer-name">{{ $testimonial->user->username ?? 'Anonymous' }}
                                            </div>
                                            <div class="review-date">{{ $testimonial->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <div class="review-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $testimonial->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="review-content">
                                            {{ $testimonial->komentar }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted p-5">
                                    <i class="fas fa-comments fa-3x mb-3"></i>
                                    <h5>No Reviews Yet</h5>
                                    <p>Be the first to review this product!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="related-products" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center mb-5">
                    <h2 class="section-title">Related Products</h2>
                    <p class="text-muted">Discover similar handcrafted items from the same category</p>
                </div>

                <div class="row g-4">
                    @foreach ($relatedProducts as $related)
                        @php
                            $relatedPromotion = $related->promosi->first();
                            $relatedOriginalPrice = $related->Harga;
                            $relatedFinalPrice = $relatedPromotion
                                ? $relatedOriginalPrice - ($relatedOriginalPrice * $relatedPromotion->diskon) / 100
                                : $relatedOriginalPrice;
                        @endphp

                        <div class="col-lg-3 col-md-6">
                            <div class="related-product-card">
                                <div class="position-relative">
                                    @if ($related->foto)
                                        <img src="{{ Storage::url($related->foto) }}" alt="{{ $related->nama_produk }}"
                                            class="related-product-image">
                                    @else
                                        <div
                                            class="related-product-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif

                                    @if ($relatedPromotion)
                                        <div
                                            class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 rounded-bottom-start">
                                            -{{ $relatedPromotion->diskon }}%
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <h6 class="card-title mb-2">
                                        <a href="{{ route('product.detail', $related->idProduk) }}"
                                            class="text-dark text-decoration-none">
                                            {{ Str::limit($related->nama_produk, 50) }}
                                        </a>
                                    </h6>

                                    <div class="price-info mb-3">
                                        @if ($relatedPromotion)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted text-decoration-line-through small">
                                                    Rp {{ number_format($relatedOriginalPrice, 0, ',', '.') }}
                                                </span>
                                                <span class="text-danger fw-bold">
                                                    Rp {{ number_format($relatedFinalPrice, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="fw-bold text-primary">
                                                Rp {{ number_format($relatedOriginalPrice, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('product.detail', $related->idProduk) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Image Zoom Modal -->
    <div class="modal fade zoom-modal" id="imageZoomModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $product->nama_produk }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="{{ $product->nama_produk }}" id="zoomedImage">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        });

        // Quantity controls
        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxStock = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
            }
        }

        // Change main image
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;

            // Update thumbnail active state
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Open image zoom modal
        function openImageZoom(src) {
            document.getElementById('zoomedImage').src = src;
            const modal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
            modal.show();
        }

        // Add to cart functionality
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;

            // Show loading state
            const btn = event.target;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding to Cart...';
            btn.disabled = true;

            // Make AJAX request to add product to cart
            csrfFetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    body: JSON.stringify({
                        idProduk: productId,
                        jumlah: parseInt(quantity),
                        foto: '{{ $product->foto ? Storage::url($product->foto) : '' }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Product added to cart successfully!', 'Success');

                        // Update cart count in navigation
                        if (typeof updateCartCount === 'function') {
                            updateCartCount();
                        }
                    } else {
                        toastr.error(data.message || 'Failed to add product to cart', 'Error');
                    }
                })
                .catch(error => {
                    toastr.error('An error occurred while adding to cart', 'Error');
                    console.error('Error:', error);
                })
                .finally(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
        }

        // Add to wishlist functionality
        function addToWishlist(productId) {
            const btn = event.target;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            btn.disabled = true;

            // Simulate API call (replace with actual implementation)
            setTimeout(() => {
                toastr.success('Product added to wishlist!', 'Success');
                btn.innerHTML = '<i class="fas fa-heart me-2"></i>Added to Wishlist';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
                btn.disabled = false;
            }, 1000);

            // TODO: Implement actual wishlist functionality
            console.log(`Adding product ${productId} to wishlist`);
        }
    </script>
@endpush
