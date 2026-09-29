@extends('layouts.frontend')

@section('title', 'Belanja - ' . (request('search') ? 'Hasil Pencarian "' . request('search') . '"' : 'Semua Produk') .
    ' - Kerajinan Indonesia')
@section('description',
    'Belanja kerajinan tangan Indonesia terbaik dengan filter lengkap. Temukan produk keramik,
    tekstil, kayu, dan kerajinan tradisional lainnya.')
@section('keywords', 'belanja kerajinan, produk indonesia, filter produk, cari kerajinan, ' . (request('search') ?
    request('search') : ''))

    @push('styles')
        <style>
            .filter-sidebar {
                background: var(--bg-light);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow);
            }

            .filter-group {
                margin-bottom: 2rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid var(--border-color);
            }

            .filter-group:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }

            .filter-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 1rem;
                display: flex;
                align-items-center;
            }

            .price-range-slider {
                margin: 1rem 0;
            }

            .price-display {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: white;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                border: 1px solid var(--border-color);
                margin-bottom: 1rem;
            }

            .filter-checkbox {
                margin-bottom: 0.75rem;
            }

            .filter-checkbox input[type="checkbox"] {
                margin-right: 0.5rem;
                transform: scale(1.1);
            }

            .filter-checkbox label {
                font-weight: 400;
                color: var(--text-dark);
                cursor: pointer;
            }

            .active-filters {
                background: white;
                border-radius: 12px;
                padding: 1rem;
                margin-bottom: 1.5rem;
                box-shadow: var(--shadow);
            }

            .filter-badge {
                display: inline-block;
                background: var(--primary-color);
                color: white;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.875rem;
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .filter-badge .btn-close {
                background-size: 0.75rem;
                margin-left: 0.5rem;
                filter: invert(1);
            }

            .product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .product-card {
                transition: all 0.3s ease;
                border: 1px solid var(--border-color);
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-hover);
                border-color: var(--primary-color);
            }

            .product-image {
                height: 220px;
                object-fit: cover;
                width: 100%;
            }

            .product-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                z-index: 10;
            }

            .product-actions {
                position: absolute;
                top: 10px;
                left: 10px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .product-card:hover .product-actions {
                opacity: 1;
            }

            .view-toggle {
                background: white;
                border-radius: 8px;
                padding: 0.25rem;
                box-shadow: var(--shadow);
            }

            .view-toggle .btn {
                border: none;
                background: transparent;
                color: var(--text-light);
                padding: 0.5rem 1rem;
                border-radius: 6px;
                transition: all 0.3s ease;
            }

            .view-toggle .btn.active {
                background: var(--primary-color);
                color: white;
            }

            .list-view .product-grid {
                display: block;
            }

            .list-view .product-card {
                display: flex !important;
                margin-bottom: 1.5rem;
                height: auto !important;
            }

            .list-view .product-card .position-relative {
                width: 200px;
                min-width: 200px;
                height: 150px;
            }

            .list-view .product-card .product-image {
                width: 200px;
                height: 150px;
                object-fit: cover;
                border-radius: 8px 0 0 8px;
            }

            .list-view .product-card .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 1.5rem;
            }

            .list-view .product-card .product-badge {
                top: 5px;
                right: 5px;
            }

            .list-view .product-card .product-actions {
                top: 5px;
                left: 5px;
            }

            .sort-dropdown {
                min-width: 200px;
            }

            .results-info {
                background: var(--bg-light);
                padding: 1rem;
                border-radius: 8px;
                margin-bottom: 1.5rem;
            }

            @media (max-width: 768px) {
                .filter-sidebar {
                    margin-bottom: 1rem;
                }

                .product-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 1rem;
                }

                .list-view .product-card {
                    flex-direction: column;
                }

                .list-view .product-card .position-relative {
                    width: 100%;
                    height: 200px;
                }

                .list-view .product-card .product-image {
                    width: 100%;
                    height: 200px;
                    border-radius: 8px 8px 0 0;
                }
            }
        </style>
    @endpush

@section('content')
    <!-- Page Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Belanja</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 mt-2">
                        @if (request('search'))
                            Hasil Pencarian "{{ request('search') }}"
                        @elseif(request('category'))
                            @php
                                $selectedCategory = $categories->where('idKategori', request('category'))->first();
                            @endphp
                            {{ $selectedCategory ? $selectedCategory->nama_kategori : 'Kategori' }}
                        @else
                            Semua Produk
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <!-- Quick Search -->
                    <form action="{{ route('shop') }}" method="GET" class="d-inline-block">
                        @foreach (request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="input-group" style="max-width: 300px; margin-left: auto;">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <!-- Active Filters -->
        @if (array_filter($currentFilters))
            <div class="active-filters">
                <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Aktif:</h6>
                <div class="d-flex flex-wrap align-items-center">
                    @if ($currentFilters['search'])
                        <span class="filter-badge">
                            Pencarian: "{{ $currentFilters['search'] }}"
                            <a href="{{ request()->fullUrlWithoutQuery('search') }}"
                                class="btn-close btn-close-white btn-sm ms-1"></a>
                        </span>
                    @endif

                    @if ($currentFilters['category'])
                        @php
                            $selectedCategory = $categories->where('idKategori', $currentFilters['category'])->first();
                        @endphp
                        <span class="filter-badge">
                            Kategori: {{ $selectedCategory->nama_kategori ?? 'Unknown' }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}"
                                class="btn-close btn-close-white btn-sm ms-1"></a>
                        </span>
                    @endif

                    @if ($currentFilters['min_price'] || $currentFilters['max_price'])
                        <span class="filter-badge">
                            Harga: Rp {{ number_format($currentFilters['min_price'] ?? $priceRange['min'], 0, ',', '.') }}
                            -
                            Rp {{ number_format($currentFilters['max_price'] ?? $priceRange['max'], 0, ',', '.') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}"
                                class="btn-close btn-close-white btn-sm ms-1"></a>
                        </span>
                    @endif

                    @if ($currentFilters['on_sale'])
                        <span class="filter-badge">
                            Sedang Promo
                            <a href="{{ request()->fullUrlWithoutQuery('on_sale') }}"
                                class="btn-close btn-close-white btn-sm ms-1"></a>
                        </span>
                    @endif

                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Hapus Semua Filter
                    </a>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <form action="{{ route('shop') }}" method="GET" id="filterForm">
                        <!-- Preserve search term -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- Category Filter -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <i class="fas fa-th-large me-2"></i>Kategori
                            </h6>
                            <div class="filter-checkbox">
                                <input type="radio" name="category" value="" id="cat_all"
                                    {{ !request('category') ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                <label for="cat_all">Semua Kategori</label>
                            </div>
                            @foreach ($categories as $category)
                                <div class="filter-checkbox">
                                    <input type="radio" name="category" value="{{ $category->idKategori }}"
                                        id="cat_{{ $category->idKategori }}"
                                        {{ request('category') == $category->idKategori ? 'checked' : '' }}
                                        onchange="document.getElementById('filterForm').submit()">
                                    <label for="cat_{{ $category->idKategori }}">
                                        {{ $category->nama_kategori }}
                                        <small class="text-muted">({{ $category->produk_count }})</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <i class="fas fa-dollar-sign me-2"></i>Rentang Harga
                            </h6>
                            <div class="price-display">
                                <span>Rp {{ number_format($priceRange['min'], 0, ',', '.') }}</span>
                                <span>-</span>
                                <span>Rp {{ number_format($priceRange['max'], 0, ',', '.') }}</span>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label small">Min</label>
                                    <input type="number" name="min_price" class="form-control form-control-sm"
                                        value="{{ request('min_price') }}" min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}" placeholder="{{ $priceRange['min'] }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Max</label>
                                    <input type="number" name="max_price" class="form-control form-control-sm"
                                        value="{{ request('max_price') }}" min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}" placeholder="{{ $priceRange['max'] }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="fas fa-filter me-1"></i>Terapkan Harga
                            </button>
                        </div>

                        <!-- Special Filters -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <i class="fas fa-star me-2"></i>Filter Khusus
                            </h6>
                            <div class="filter-checkbox">
                                <input type="checkbox" name="on_sale" value="1" id="on_sale"
                                    {{ request('on_sale') ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                <label for="on_sale">
                                    <i class="fas fa-fire text-danger me-1"></i>Sedang Promo
                                </label>
                            </div>
                            <div class="filter-checkbox">
                                <input type="checkbox" name="in_stock" value="1" id="in_stock"
                                    {{ request('in_stock') ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                <label for="in_stock">
                                    <i class="fas fa-check text-success me-1"></i>Tersedia
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Section -->
            <div class="col-lg-9">
                <!-- Results Info & Controls -->
                <div class="results-info">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>{{ $products->total() }}</strong> produk ditemukan
                                @if ($products->hasPages())
                                    (halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }})
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end align-items-center gap-3 mt-2 mt-md-0">
                                <!-- View Toggle -->
                                <div class="view-toggle">
                                    <button class="btn active" onclick="setGridView()" id="gridViewBtn">
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button class="btn" onclick="setListView()" id="listViewBtn">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>

                                <!-- Sort Options -->
                                <form action="{{ route('shop') }}" method="GET" class="d-inline-block">
                                    @foreach (request()->except('sort_by') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="sort_by" class="form-select sort-dropdown"
                                        onchange="this.form.submit()">
                                        <option value="latest"
                                            {{ $currentFilters['sort_by'] === 'latest' ? 'selected' : '' }}>Terbaru
                                        </option>
                                        <option value="price_low"
                                            {{ $currentFilters['sort_by'] === 'price_low' ? 'selected' : '' }}>Harga
                                            Terendah</option>
                                        <option value="price_high"
                                            {{ $currentFilters['sort_by'] === 'price_high' ? 'selected' : '' }}>Harga
                                            Tertinggi</option>
                                        <option value="name_asc"
                                            {{ $currentFilters['sort_by'] === 'name_asc' ? 'selected' : '' }}>Nama A-Z
                                        </option>
                                        <option value="name_desc"
                                            {{ $currentFilters['sort_by'] === 'name_desc' ? 'selected' : '' }}>Nama Z-A
                                        </option>
                                        <option value="on_sale"
                                            {{ $currentFilters['sort_by'] === 'on_sale' ? 'selected' : '' }}>Sedang Promo
                                        </option>
                                    </select>
                                </form>

                                <!-- Per Page -->
                                <form action="{{ route('shop') }}" method="GET" class="d-inline-block">
                                    @foreach (request()->except('per_page') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="per_page" class="form-select" style="width: 80px;"
                                        onchange="this.form.submit()">
                                        <option value="12" {{ $currentFilters['per_page'] == 12 ? 'selected' : '' }}>
                                            12</option>
                                        <option value="24" {{ $currentFilters['per_page'] == 24 ? 'selected' : '' }}>
                                            24</option>
                                        <option value="48" {{ $currentFilters['per_page'] == 48 ? 'selected' : '' }}>
                                            48</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="productsContainer" class="grid-view">
                    @if ($products->count() > 0)
                        <div class="product-grid">
                            @foreach ($products as $product)
                                <div class="card product-card h-100">
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

                                    <div class="position-relative">
                                        @if ($product->foto)
                                            <img src="{{ Storage::url($product->foto) }}"
                                                alt="{{ $product->nama_produk }}" class="card-img-top product-image">
                                        @else
                                            <div
                                                class="card-img-top product-image d-flex align-items-center justify-content-center bg-light text-muted">
                                                <div class="text-center">
                                                    <i class="fas fa-image fa-2x mb-2"></i>
                                                    <div class="small">{{ Str::limit($product->nama_produk, 20) }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Product Badge -->
                                        @if ($promotion)
                                            <span class="badge bg-danger product-badge">
                                                -{{ $promotion->diskon }}%
                                            </span>
                                        @elseif($product->tanggal_upload && $product->tanggal_upload > now()->subDays(7))
                                            <span class="badge bg-primary product-badge">Baru</span>
                                        @endif

                                        <!-- Product Actions -->
                                        <div class="product-actions">
                                            <button class="btn btn-sm btn-light rounded-circle me-1"
                                                onclick="addToWishlist({{ $product->idProduk }})"
                                                title="Tambah ke Wishlist">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light rounded-circle"
                                                onclick="quickView({{ $product->idProduk }})" title="Quick View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i
                                                    class="fas fa-tag me-1"></i>{{ $product->kategori->nama_kategori ?? 'Kategori' }}
                                            </small>
                                        </div>

                                        <h6 class="card-title mb-2">
                                            <a href="{{ route('product.detail', $product->idProduk) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $product->nama_produk }}
                                            </a>
                                        </h6>

                                        <p class="card-text text-muted small flex-grow-1">
                                            {{ Str::limit($product->deskripsi, 80) }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                @if ($promotion)
                                                    <div class="fw-bold text-primary">
                                                        Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-muted small text-decoration-line-through">
                                                        Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                                    </div>
                                                @else
                                                    <div class="fw-bold text-primary">
                                                        Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <small
                                                    class="text-{{ $product->stok > 10 ? 'success' : ($product->stok > 0 ? 'warning' : 'danger') }}">
                                                    <i class="fas fa-box me-1"></i>
                                                    @if ($product->stok > 10)
                                                        Tersedia
                                                    @elseif($product->stok > 0)
                                                        {{ $product->stok }} tersisa
                                                    @else
                                                        Habis
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex align-items-center justify-content-between text-muted small mb-3">
                                            <span>
                                                <i
                                                    class="fas fa-user me-1"></i>{{ $product->user->username ?? 'Pengrajin' }}
                                            </span>
                                            <span>
                                                <i
                                                    class="fas fa-clock me-1"></i>{{ $product->tanggal_upload ? \Carbon\Carbon::parse($product->tanggal_upload)->diffForHumans() : 'Baru' }}
                                            </span>
                                        </div>

                                        <div class="d-grid">
                                            <a href="{{ route('product.detail', $product->idProduk) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-eye me-1"></i>Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @else
                        <!-- No Results -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-search fa-4x text-muted"></i>
                            </div>
                            <h4>Tidak Ada Produk Ditemukan</h4>
                            <p class="text-muted mb-4">
                                Maaf, tidak ada produk yang sesuai dengan kriteria pencarian Anda.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('shop') }}" class="btn btn-primary">
                                    <i class="fas fa-refresh me-1"></i>Lihat Semua Produk
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-1"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // View Toggle Functions
        function setGridView() {
            document.getElementById('productsContainer').className = 'grid-view';
            document.getElementById('gridViewBtn').classList.add('active');
            document.getElementById('listViewBtn').classList.remove('active');
            localStorage.setItem('viewMode', 'grid');
        }

        function setListView() {
            document.getElementById('productsContainer').className = 'list-view';
            document.getElementById('listViewBtn').classList.add('active');
            document.getElementById('gridViewBtn').classList.remove('active');
            localStorage.setItem('viewMode', 'list');
        }

        // Load saved view mode
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('viewMode');
            if (savedView === 'list') {
                setListView();
            }
        });

        // Wishlist functionality (placeholder)
        function addToWishlist(productId) {
            // Show toast notification
            const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-heart me-2"></i>Produk ditambahkan ke wishlist!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        `;

            // You would implement actual wishlist functionality here
            console.log('Added to wishlist:', productId);

            // For now, just show an alert
            alert('Fitur wishlist akan segera tersedia!');
        }

        // Quick view functionality (placeholder)
        function quickView(productId) {
            // You would implement modal quick view here
            console.log('Quick view:', productId);

            // For now, redirect to product detail
            window.location.href = `/product/${productId}`;
        }

        // Auto-submit form when price inputs change (with debounce)
        let priceTimeout;
        document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(priceTimeout);
                priceTimeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 1000); // Wait 1 second after user stops typing
            });
        });

        // Search suggestions (enhance with AJAX)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                if (query.length > 2) {
                    // Implement AJAX search suggestions here
                    // fetch(`/api/search-suggestions?q=${query}`)
                }
            });
        }

        // Smooth scroll to top when pagination links are clicked
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(() => {
                    document.querySelector('.results-info').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            });
        });
    </script>
@endpush
