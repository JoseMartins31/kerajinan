@extends('layouts.frontend')

@section('title', 'Promotions & Deals')
@section('description', 'Discover amazing deals and promotions on Indonesian handicrafts. Flash sales, hot deals, and
    exclusive discounts.')

    @push('styles')
        <style>
            .hero-promotions {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
                overflow: hidden;
                margin-bottom: 60px;
            }

            .hero-promotions::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');
            }

            .promotion-card {
                background: #fff;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                position: relative;
            }

            .promotion-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }

            .promotion-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #e74c3c;
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: bold;
                font-size: 0.9rem;
                z-index: 10;
            }

            .product-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .promotion-card:hover .product-image {
                transform: scale(1.05);
            }

            .countdown-timer {
                background: rgba(231, 76, 60, 0.1);
                border: 2px solid #e74c3c;
                border-radius: 10px;
                padding: 15px;
                text-align: center;
                margin-bottom: 20px;
            }

            .countdown-item {
                display: inline-block;
                margin: 0 10px;
                text-align: center;
            }

            .countdown-number {
                display: block;
                font-size: 1.5rem;
                font-weight: bold;
                color: #e74c3c;
            }

            .countdown-label {
                display: block;
                font-size: 0.8rem;
                color: #666;
                text-transform: uppercase;
            }

            .section-header {
                text-align: center;
                margin-bottom: 50px;
                position: relative;
            }

            .section-header h2 {
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 15px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .section-header::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 2px;
            }

            .category-filter {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 20px;
                margin-bottom: 40px;
            }

            .category-chip {
                display: inline-block;
                background: #fff;
                border: 2px solid #e9ecef;
                color: #6c757d;
                padding: 8px 20px;
                border-radius: 25px;
                text-decoration: none;
                margin: 5px;
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }

            .category-chip:hover,
            .category-chip.active {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-color: transparent;
                color: white;
                transform: translateY(-2px);
            }

            .flash-sale-section {
                background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
                border-radius: 20px;
                padding: 40px;
                margin-bottom: 60px;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .flash-sale-section::before {
                content: '⚡';
                position: absolute;
                top: -20px;
                right: -20px;
                font-size: 8rem;
                opacity: 0.1;
                animation: pulse 2s infinite;
            }

            .hot-deals-section {
                background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
                border-radius: 20px;
                padding: 40px;
                margin-bottom: 60px;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .hot-deals-section::before {
                content: '🔥';
                position: absolute;
                top: -20px;
                right: -20px;
                font-size: 8rem;
                opacity: 0.2;
                animation: bounce 2s infinite;
            }

            .price-display {
                margin: 15px 0;
            }

            .original-price {
                text-decoration: line-through;
                color: #999;
                font-size: 0.9rem;
            }

            .discounted-price {
                color: #e74c3c;
                font-weight: bold;
                font-size: 1.3rem;
            }

            .savings {
                color: #27ae60;
                font-weight: bold;
                font-size: 0.9rem;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 0.1;
                }

                50% {
                    opacity: 0.2;
                }
            }

            @keyframes bounce {

                0%,
                20%,
                50%,
                80%,
                100% {
                    transform: translateY(0);
                }

                40% {
                    transform: translateY(-10px);
                }

                60% {
                    transform: translateY(-5px);
                }
            }

            .promotion-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin-bottom: 40px;
            }

            @media (max-width: 768px) {
                .promotion-grid {
                    grid-template-columns: 1fr;
                }

                .section-header h2 {
                    font-size: 2rem;
                }

                .flash-sale-section,
                .hot-deals-section {
                    padding: 25px;
                }
            }
        </style>
    @endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-promotions py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h1 class="display-4 font-weight-bold mb-4" data-aos="fade-up">
                        🎉 Amazing Promotions & Deals!
                    </h1>
                    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                        Discover incredible savings on authentic Indonesian handicrafts. Limited time offers you don't want
                        to miss!
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="200">
                        <div class="text-center">
                            <div class="display-6 font-weight-bold">{{ $promotions->total() }}</div>
                            <small>Active Deals</small>
                        </div>
                        <div class="text-center mx-4">
                            <div class="display-6 font-weight-bold">{{ $flashSales->count() }}</div>
                            <small>Flash Sales</small>
                        </div>
                        <div class="text-center">
                            <div class="display-6 font-weight-bold">{{ $hotDeals->count() }}</div>
                            <small>Hot Deals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Category Filter -->
        @if ($categoriesWithPromotions->count() > 0)
            <div class="category-filter" data-aos="fade-up">
                <h5 class="text-center mb-3">Filter by Category</h5>
                <div class="text-center">
                    <a href="{{ route('promotions') }}" class="category-chip {{ !request('category') ? 'active' : '' }}">
                        All Categories
                    </a>
                    @foreach ($categoriesWithPromotions as $category)
                        <a href="{{ route('promotions', ['category' => $category->idKategori]) }}"
                            class="category-chip {{ request('category') == $category->idKategori ? 'active' : '' }}">
                            {{ $category->nama_kategori }}
                            <span class="badge badge-light ms-1">{{ $category->produk_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Flash Sales Section -->
        @if ($flashSales->count() > 0)
            <div class="flash-sale-section" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3 class="mb-3">⚡ Flash Sales - Limited Time!</h3>
                        <p class="mb-4">These amazing deals are ending soon. Don't miss out!</p>

                        <!-- Countdown Timer for nearest expiring flash sale -->
                        @if ($flashSales->first())
                            @php
                                $nearestExpiry = $flashSales->first()->tanggal_akhir;
                            @endphp
                            <div class="countdown-timer bg-white text-dark">
                                <h6 class="mb-3 text-danger">⏰ Time Left:</h6>
                                <div class="countdown-display" data-countdown="{{ $nearestExpiry }}">
                                    <div class="countdown-item">
                                        <span class="countdown-number hours">00</span>
                                        <span class="countdown-label">Hours</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-number minutes">00</span>
                                        <span class="countdown-label">Minutes</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-number seconds">00</span>
                                        <span class="countdown-label">Seconds</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            @foreach ($flashSales->take(2) as $flashSale)
                                <div class="col-6">
                                    <div class="card bg-white text-dark border-0">
                                        <div class="position-relative">
                                            @if ($flashSale->produk->foto)
                                                <img src="{{ Storage::url($flashSale->produk->foto) }}"
                                                    alt="{{ $flashSale->produk->nama_produk }}"
                                                    class="card-img-top product-image" style="height: 120px;">
                                            @else
                                                <div
                                                    class="card-img-top product-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div
                                                class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 rounded-bottom-start">
                                                -{{ $flashSale->diskon }}%
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <h6 class="card-title small mb-1">
                                                {{ Str::limit($flashSale->produk->nama_produk, 25) }}</h6>
                                            <div class="small">
                                                @php
                                                    $original = $flashSale->produk->Harga;
                                                    $discounted = $original - ($original * $flashSale->diskon) / 100;
                                                @endphp
                                                <span class="text-muted text-decoration-line-through">Rp
                                                    {{ number_format($original, 0, ',', '.') }}</span><br>
                                                <span class="text-danger fw-bold">Rp
                                                    {{ number_format($discounted, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Hot Deals Section -->
        @if ($hotDeals->count() > 0)
            <div class="hot-deals-section" data-aos="fade-up" data-aos-delay="100">
                <h3 class="mb-4">🔥 Hot Deals - Up to 70% Off!</h3>
                <div class="row g-4">
                    @foreach ($hotDeals->take(3) as $hotDeal)
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-white text-dark h-100">
                                <div class="position-relative">
                                    @if ($hotDeal->produk->foto)
                                        <img src="{{ Storage::url($hotDeal->produk->foto) }}"
                                            alt="{{ $hotDeal->produk->nama_produk }}" class="card-img-top product-image">
                                    @else
                                        <div
                                            class="card-img-top product-image d-flex align-items-center justify-content-center bg-light">
                                            <div class="text-center">
                                                <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                                <div class="small text-muted">
                                                    {{ Str::limit($hotDeal->produk->nama_produk, 20) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div
                                        class="position-absolute top-0 end-0 bg-danger text-white px-3 py-2 rounded-bottom-start">
                                        <strong>-{{ $hotDeal->diskon }}%</strong>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ $hotDeal->produk->nama_produk }}</h6>
                                    <p class="card-text small text-muted">
                                        {{ $hotDeal->produk->kategori->nama_kategori ?? 'Uncategorized' }}</p>
                                    @php
                                        $original = $hotDeal->produk->Harga;
                                        $discounted = $original - ($original * $hotDeal->diskon) / 100;
                                        $savings = $original - $discounted;
                                    @endphp
                                    <div class="price-display">
                                        <div class="original-price">Rp {{ number_format($original, 0, ',', '.') }}</div>
                                        <div class="discounted-price">Rp {{ number_format($discounted, 0, ',', '.') }}
                                        </div>
                                        <div class="savings">You save: Rp {{ number_format($savings, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="{{ route('product.detail', $hotDeal->produk->idProduk) }}"
                                        class="btn btn-outline-danger btn-sm w-100">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- All Promotions Section -->
        <div class="section-header" data-aos="fade-up">
            <h2>All Active Promotions</h2>
            <p class="lead text-muted">Browse all our current deals and find your perfect handicraft at unbeatable prices
            </p>
        </div>

        <!-- Promotions Grid -->
        @if ($promotions->count() > 0)
            <div class="promotion-grid">
                @foreach ($promotions as $promotion)
                    <div class="promotion-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="promotion-badge">
                            -{{ $promotion->diskon }}% OFF
                        </div>

                        <div class="position-relative overflow-hidden">
                            @if ($promotion->produk->foto)
                                <img src="{{ Storage::url($promotion->produk->foto) }}"
                                    alt="{{ $promotion->produk->nama_produk }}" class="product-image">
                            @else
                                <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <div class="text-muted">{{ Str::limit($promotion->produk->nama_produk, 25) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    class="badge bg-primary">{{ $promotion->produk->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Ends {{ \Carbon\Carbon::parse($promotion->tanggal_akhir)->diffForHumans() }}
                                </small>
                            </div>

                            <h5 class="mb-3">{{ $promotion->produk->nama_produk }}</h5>

                            <p class="text-muted small mb-3">
                                {{ Str::limit($promotion->produk->deskripsi ?? 'Beautiful Indonesian handicraft', 100) }}
                            </p>

                            @php
                                $original = $promotion->produk->Harga;
                                $discounted = $original - ($original * $promotion->diskon) / 100;
                                $savings = $original - $discounted;
                            @endphp

                            <div class="price-display mb-3">
                                <div class="original-price">Rp {{ number_format($original, 0, ',', '.') }}</div>
                                <div class="discounted-price">Rp {{ number_format($discounted, 0, ',', '.') }}</div>
                                <div class="savings">Save Rp {{ number_format($savings, 0, ',', '.') }}</div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('product.detail', $promotion->produk->idProduk) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>View Product
                                </a>
                            </div>

                            <!-- Stock & Urgency Indicators -->
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $promotion->produk->stok }} left in stock
                                </small>
                                @if (\Carbon\Carbon::parse($promotion->tanggal_akhir)->diffInHours() < 24)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-fire me-1"></i>Limited Time!
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fas fa-percentage fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No Active Promotions</h4>
                <p class="text-muted">Check back soon for amazing deals on Indonesian handicrafts!</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Browse All Products
                </a>
            </div>
        @endif
    </div>

    <!-- Newsletter Signup -->
    <section class="py-5 mt-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white" data-aos="fade-up">
                    <h3 class="mb-3">🎁 Never Miss a Deal!</h3>
                    <p class="mb-4">Subscribe to our newsletter and be the first to know about flash sales, exclusive
                        promotions, and new arrivals.</p>
                    <form class="d-flex justify-content-center gap-2 flex-wrap">
                        <input type="email" class="form-control" style="max-width: 300px;"
                            placeholder="Enter your email address">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-envelope me-1"></i>Subscribe
                        </button>
                    </form>
                    <small class="d-block mt-2 opacity-75">No spam, unsubscribe at any time</small>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize AOS animations
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Countdown Timer
            function initCountdown() {
                const countdownElement = document.querySelector('[data-countdown]');
                if (!countdownElement) return;

                const endDate = new Date(countdownElement.getAttribute('data-countdown')).getTime();

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = endDate - now;

                    if (distance < 0) {
                        countdownElement.innerHTML = '<div class="text-center">🎉 Sale Ended!</div>';
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownElement.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
                    countdownElement.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
                    countdownElement.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
                }

                // Update countdown every second
                updateCountdown();
                const interval = setInterval(updateCountdown, 1000);

                // Clear interval when countdown ends
                setTimeout(() => {
                    if (endDate - new Date().getTime() < 0) {
                        clearInterval(interval);
                    }
                }, endDate - new Date().getTime());
            }

            initCountdown();

            // Newsletter subscription
            $('form').on('submit', function(e) {
                e.preventDefault();
                const email = $(this).find('input[type="email"]').val();
                if (email) {
                    // Here you would typically send to your backend
                    alert(
                    'Thank you for subscribing! You\'ll receive updates about our latest promotions.');
                    $(this).find('input[type="email"]').val('');
                }
            });

            // Add smooth scrolling for internal links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                const target = $($(this).attr('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 500);
                }
            });
        });
    </script>
@endpush
