@extends('layouts.frontend')

@section('title', 'Beranda - Kerajinan Indonesia')
@section('description',
    'Temukan kerajinan tangan Indonesia terbaik dengan kualitas premium. Koleksi lengkap keramik,
    tekstil, kayu, dan kerajinan tradisional lainnya.')
@section('keywords', 'kerajinan indonesia, handicraft, keramik, tekstil, kayu, bambu, rotan, seni tradisional')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">
                        Temukan Kerajinan<br>
                        <span style="color: var(--accent-color);">Indonesia Terbaik</span>
                    </h1>
                    <p class="lead mb-4">
                        Eksplorasi koleksi eksklusif kerajinan tangan Indonesia dari pengrajin terpilih.
                        Setiap produk menceritakan warisan budaya yang kaya dan keahlian turun temurun.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('shop') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-store me-2"></i>Mulai Belanja
                        </a>
                        <a href="{{ route('promotions') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-fire me-2"></i>Lihat Promosi
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="position-relative">
                        <div class="hero-image-container position-relative">
                            <img src="/images/hero-craft.jpg" alt="Indonesian Handicrafts" class="img-fluid rounded-3"
                                style="box-shadow: 0 20px 40px rgba(0,0,0,0.2); width: 100%; height: 400px; object-fit: cover;"
                                onerror="this.style.display='none'; this.parentElement.querySelector('.placeholder-hero').style.display='flex';">
                            <div class="placeholder-hero d-none align-items-center justify-content-center bg-primary text-white rounded-3"
                                style="width: 100%; height: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                                <div class="text-center">
                                    <i class="fas fa-palette fa-4x mb-3"></i>
                                    <h4>Indonesian Handicrafts</h4>
                                    <p>Authentic Traditional Arts</p>
                                </div>
                            </div>
                        </div>
                        <div class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 rounded-bottom-3">
                            <small class="fw-bold"><i class="fas fa-star me-1"></i>Premium Quality</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Search & Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <form action="{{ route('shop') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->idKategori }}"
                                        {{ request('category') == $category->idKategori ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="sort_by" class="form-select">
                                <option value="latest">Terbaru</option>
                                <option value="price_low">Harga Terendah</option>
                                <option value="price_high">Harga Tertinggi</option>
                                <option value="on_sale">Sedang Promo</option>
                                <option value="name_asc">Nama A-Z</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex justify-content-lg-end align-items-center gap-3">
                        <span class="text-muted">Filter cepat:</span>
                        <a href="{{ route('shop', ['on_sale' => 1]) }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-fire me-1"></i>Promo
                        </a>
                        <a href="{{ route('shop', ['sort_by' => 'latest']) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star me-1"></i>Terbaru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Navigation -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Jelajahi Kategori</h2>
            <div class="row g-4">
                @foreach ($categories->take(6) as $category)
                    <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('category', $category->idKategori) }}" class="text-decoration-none">
                            <div class="card h-100 text-center border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        @php
                                            $icons = [
                                                'Keramik' => 'fas fa-vase-flowers',
                                                'Tekstil' => 'fas fa-tshirt',
                                                'Kayu' => 'fas fa-tree',
                                                'Logam' => 'fas fa-ring',
                                                'Bambu' => 'fas fa-leaf',
                                                'Rotan' => 'fas fa-shopping-basket',
                                                'Kulit' => 'fas fa-shoe-prints',
                                                'Perhiasan' => 'fas fa-gem',
                                                'Seni Lukis' => 'fas fa-palette',
                                                'Patung' => 'fas fa-chess-rook',
                                            ];
                                        @endphp
                                        <i
                                            class="{{ $icons[$category->nama_kategori] ?? 'fas fa-cube' }} fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-1">{{ $category->nama_kategori }}</h6>
                                    <small class="text-muted">{{ $category->produk_count }} produk</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Promoted Products Section -->
    @if ($promotedProducts->count() > 0)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-md-6">
                        <h2 class="section-title mb-0" data-aos="fade-right">
                            <i class="fas fa-fire text-danger me-2"></i>Sedang Promo
                        </h2>
                        <p class="text-muted mt-2">Jangan lewatkan penawaran terbaik kami</p>
                    </div>
                    <div class="col-md-6 text-md-end" data-aos="fade-left">
                        <a href="{{ route('shop', ['on_sale' => 1]) }}" class="btn btn-outline-primary">
                            Lihat Semua Promo <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach ($promotedProducts as $product)
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="card product-card h-100">
                                @php
                                    $promotion = $product->promosi->first();
                                    $originalPrice = $product->Harga;
                                    $discountedPrice =
                                        $originalPrice - ($originalPrice * ($promotion->diskon ?? 0)) / 100;
                                @endphp

                                <div class="position-relative">
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
                                    <div class="product-badge bg-danger">
                                        -{{ $promotion->diskon ?? 0 }}%
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-gradient"
                                        style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <small>Berakhir:
                                                    {{ $promotion->tanggal_akhir ? \Carbon\Carbon::parse($promotion->tanggal_akhir)->format('d M') : '' }}</small>
                                            </div>
                                            <div class="text-white">
                                                <small><i class="fas fa-eye me-1"></i>Popular</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="mb-2">
                                        <small
                                            class="text-muted">{{ $product->kategori->nama_kategori ?? 'Kategori' }}</small>
                                    </div>
                                    <h6 class="card-title">{{ Str::limit($product->nama_produk, 50) }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($product->deskripsi, 80) }}</p>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="product-price">Rp
                                                {{ number_format($discountedPrice, 0, ',', '.') }}</div>
                                            <div class="product-price-original">Rp
                                                {{ number_format($originalPrice, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-success">
                                                <i class="fas fa-box me-1"></i>{{ $product->stok ?? 0 }} tersisa
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 bg-transparent pt-0">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('product.detail', $product->idProduk) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <h2 class="section-title mb-0" data-aos="fade-right">Produk Unggulan</h2>
                    <p class="text-muted mt-2">Koleksi terpilih dengan kualitas terbaik</p>
                </div>
                <div class="col-md-6 text-md-end" data-aos="fade-left">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="featured-filter" id="all" checked>
                        <label class="btn btn-outline-primary" for="all"
                            onclick="filterProducts('all')">Semua</label>

                        <input type="radio" class="btn-check" name="featured-filter" id="latest">
                        <label class="btn btn-outline-primary" for="latest"
                            onclick="filterProducts('latest')">Terbaru</label>

                        <input type="radio" class="btn-check" name="featured-filter" id="popular">
                        <label class="btn btn-outline-primary" for="popular"
                            onclick="filterProducts('popular')">Populer</label>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="featured-products">
                @foreach ($featuredProducts as $product)
                    <div class="col-lg-3 col-md-6 product-item"
                        data-category="{{ $product->kategori->nama_kategori ?? '' }}" data-price="{{ $product->Harga }}"
                        data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card product-card h-100">
                            @php
                                $promotion = $product->promosi->first();
                                $originalPrice = $product->Harga;
                                $finalPrice = $promotion
                                    ? $originalPrice - ($originalPrice * $promotion->diskon) / 100
                                    : $originalPrice;
                            @endphp

                            <div class="position-relative">
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

                                @if ($promotion)
                                    <div class="product-badge bg-danger">
                                        -{{ $promotion->diskon }}%
                                    </div>
                                @else
                                    <div class="product-badge bg-primary">
                                        Baru
                                    </div>
                                @endif

                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="fas fa-star text-warning me-1"></i>Featured
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i
                                            class="fas fa-tag me-1"></i>{{ $product->kategori->nama_kategori ?? 'Kategori' }}
                                    </small>
                                </div>
                                <h6 class="card-title">{{ Str::limit($product->nama_produk, 50) }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($product->deskripsi, 80) }}</p>

                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        @if ($promotion)
                                            <div class="product-price">Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                            </div>
                                            <div class="product-price-original">Rp
                                                {{ number_format($originalPrice, 0, ',', '.') }}</div>
                                        @else
                                            <div class="product-price">Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small
                                            class="text-{{ $product->stok > 10 ? 'success' : ($product->stok > 0 ? 'warning' : 'danger') }}">
                                            <i class="fas fa-box me-1"></i>
                                            @if ($product->stok > 10)
                                                Stok tersedia
                                            @elseif($product->stok > 0)
                                                {{ $product->stok }} tersisa
                                            @else
                                                Habis
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-user me-1"></i>
                                    <span>{{ $product->user->username ?? 'Pengrajin' }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock me-1"></i>
                                    <span>{{ $product->tanggal_upload ? \Carbon\Carbon::parse($product->tanggal_upload)->diffForHumans() : 'Baru' }}</span>
                                </div>
                            </div>

                            <div class="card-footer border-0 bg-transparent pt-0">
                                <div class="d-grid">
                                    <a href="{{ route('product.detail', $product->idProduk) }}" class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-th me-2"></i>Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

    <!-- Active Promotions Banner -->
    @if ($promotions->count() > 0)
        <section class="py-5 bg-primary text-white">
            <div class="container">
                <h2 class="section-title text-center text-white mb-5" data-aos="fade-up">Promosi Aktif</h2>
                <div class="row g-4">
                    @foreach ($promotions as $promotion)
                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}">
                            <div class="card bg-white text-dark h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-danger text-white rounded-circle p-2 me-3">
                                            <i class="fas fa-fire"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">
                                                {{ $promotion->produk->nama_produk ?? 'Produk Promosi' }}</h5>
                                            <p class="text-muted small mb-2">
                                                {{ $promotion->produk->kategori->nama_kategori ?? 'Kategori' }}</p>
                                        </div>
                                        <span class="badge bg-danger fs-6">-{{ $promotion->diskon ?? 0 }}%</span>
                                    </div>

                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <div class="h4 text-primary mb-0">
                                                    {{ \Carbon\Carbon::parse($promotion->tanggal_mulai)->format('d') }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($promotion->tanggal_mulai)->format('M Y') }}</small>
                                                <div class="small">Mulai</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="h4 text-danger mb-0">
                                                {{ \Carbon\Carbon::parse($promotion->tanggal_akhir)->format('d') }}</div>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($promotion->tanggal_akhir)->format('M Y') }}</small>
                                            <div class="small">Berakhir</div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('product.detail', $promotion->idProduk) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-shopping-cart me-1"></i>Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonials Section -->
    @if ($testimonials->count() > 0)
        <section class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">Kata Mereka</h2>
                <div class="row g-4">
                    @foreach ($testimonials->take(3) as $testimonial)
                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= ($testimonial->rating ?? 5) ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <blockquote class="mb-3">
                                        <p class="mb-0">
                                            "{{ Str::limit($testimonial->komentar ?? 'Produk berkualitas dengan pengerjaan yang sangat detail. Sangat puas dengan pembelian ini.', 120) }}"
                                        </p>
                                    </blockquote>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $testimonial->user->username ?? 'Pelanggan' }}</h6>
                                            <small
                                                class="text-muted">{{ $testimonial->produk->nama_produk ?? 'Produk' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Newsletter Section -->
    <section class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h3 class="mb-3">Dapatkan Update Terbaru</h3>
                    <p class="mb-0">Berlangganan newsletter untuk mendapatkan informasi produk baru, promosi eksklusif,
                        dan tips berbelanja kerajinan.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <form class="row g-3">
                        <div class="col-md-8">
                            <input type="email" class="form-control form-control-lg" placeholder="Masukkan email Anda">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-paper-plane me-1"></i>Berlangganan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Product filtering functionality
        function filterProducts(type) {
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                product.style.display = 'block';

                if (type === 'latest') {
                    // Show products uploaded in last 30 days
                    // This is a simplified approach - in production you'd want to pass more data
                } else if (type === 'popular') {
                    // Show popular products based on some criteria
                    // This is a simplified approach
                }
            });

            // Add animation
            setTimeout(() => {
                AOS.refresh();
            }, 100);
        }

        // Search suggestions (you can enhance this with AJAX)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                if (query.length > 2) {
                    // Here you could implement AJAX search suggestions
                    // fetch(`/api/search-suggestions?q=${query}`)
                }
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add to wishlist functionality (placeholder)
        function addToWishlist(productId) {
            // Implement wishlist functionality
            console.log('Added to wishlist:', productId);
        }

        // Quick view functionality (placeholder)
        function quickView(productId) {
            // Implement quick view modal
            console.log('Quick view:', productId);
        }
    </script>
@endpush
