<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kerajinan Indonesia - Authentic Indonesian Handicrafts')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Discover authentic Indonesian handicrafts including keramik, tekstil, kayu, and more. Premium quality handmade products from skilled artisans.')">
    <meta name="keywords" content="@yield('keywords', 'kerajinan, handicrafts, indonesia, keramik, tekstil, kayu, bambu, rotan')">
    <meta property="og:title" content="@yield('og-title', 'Kerajinan Indonesia')">
    <meta property="og:description" content="@yield('og-description', 'Authentic Indonesian Handicrafts')">
    <meta property="og:image" content="@yield('og-image', asset('images/logo-og.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Product Placeholder CSS -->
    <link href="{{ asset('css/product-placeholders.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #c8102e;
            --primary-dark: #a00d24;
            --primary-light: #d32f2f;
            --secondary-color: #8b4513;
            --accent-color: #f4a261;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --border-color: #e9ecef;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-white);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            line-height: 1.3;
        }

        /* Enhanced Typography */
        p {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .lead {
            font-size: 1.125rem;
            font-weight: 400;
            line-height: 1.7;
            letter-spacing: -0.01em;
        }

        small,
        .small {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .text-muted {
            color: var(--text-light) !important;
        }

        /* Button text improvements */
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-4px);
        }

        .navbar {
            background-color: var(--bg-white) !important;
            box-shadow: var(--shadow);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            letter-spacing: -0.02em;
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .search-bar {
            border-radius: 25px;
            border: 2px solid var(--border-color);
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-bar:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
            outline: none;
        }

        /* Responsive Navigation Improvements */
        @media (max-width: 1199.98px) {
            .search-bar {
                width: 180px !important;
                min-width: 160px !important;
            }
        }

        @media (max-width: 991.98px) {
            .search-bar {
                width: 160px !important;
                min-width: 140px !important;
            }

            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-collapse {
                border-top: 1px solid var(--border-color);
                margin-top: 1rem;
                padding-top: 1rem;
            }

            .search-bar {
                width: 200px !important;
            }

            .navbar-nav .nav-item {
                text-align: center;
            }
        }

        /* Cart badge positioning improvements */
        .nav-link .badge {
            z-index: 10;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 4rem 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .product-card {
            position: relative;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-price-original {
            text-decoration: line-through;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .filter-sidebar {
            background-color: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .footer {
            background-color: var(--text-dark);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .hero-section {
                padding: 2rem 0;
            }

            .filter-sidebar {
                margin-bottom: 1rem;
            }
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-palette me-2"></i>Kerajinan Indonesia
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i><span class="d-none d-sm-inline">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">
                            <i class="fas fa-store me-1"></i><span class="d-none d-sm-inline">Belanja</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-th-large me-1"></i><span class="d-none d-sm-inline">Kategori</span>
                        </a>
                        <ul class="dropdown-menu">
                            @forelse($categories as $category)
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center"
                                        href="{{ route('category', $category->idKategori) }}">
                                        <span>
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
                                                class="{{ $icons[$category->nama_kategori] ?? 'fas fa-cube' }} me-2 text-primary"></i>
                                            {{ $category->nama_kategori }}
                                        </span>
                                        <small class="badge bg-secondary">{{ $category->produk_count }}</small>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">Tidak ada kategori</span></li>
                            @endforelse
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="{{ route('shop') }}"><strong>Lihat Semua
                                        Produk</strong></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('promotions') ? 'active' : '' }}"
                            href="{{ route('promotions') }}">
                            <i class="fas fa-fire me-1"></i><span class="d-none d-sm-inline">Promosi</span>
                        </a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-2" action="{{ route('search') }}" method="GET">
                    <div class="position-relative">
                        <input type="search" name="search" class="form-control search-bar"
                            placeholder="Cari produk..." value="{{ request('search') }}"
                            style="width: 220px; min-width: 180px;">
                        <button class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                            type="submit" style="margin-right: 10px;">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </div>
                </form>

                <!-- User Menu -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i><span class="d-none d-lg-inline">Masuk</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i><span class="d-none d-sm-inline">Daftar</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart') }}" data-bs-toggle="tooltip"
                                title="Keranjang Belanja">
                                <i class="fas fa-shopping-cart"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    id="cartCount" style="font-size: 0.6rem; margin-left: -8px;">
                                    0
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('recently.viewed') }}" data-bs-toggle="tooltip"
                                title="Baru Dilihat">
                                <i class="fas fa-history"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.orders.index') }}" data-bs-toggle="tooltip"
                                title="Pesanan Saya">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            @include('components.notification-dropdown')
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                data-bs-toggle="tooltip" title="Menu Pengguna">
                                <i class="fas fa-user me-1"></i>
                                <span class="d-none d-sm-inline">{{ Str::limit(Auth::user()->username, 10) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i
                                            class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders.index') }}"><i
                                            class="fas fa-file-invoice me-2"></i>Pesanan Saya</a></li>
                                @if (Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                                                class="fas fa-tachometer-alt me-2"></i>Admin Panel</a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logoutForm" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"
                                            onclick="confirmLogout(event)" title="Logout from your account">
                                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-palette me-2"></i>Kerajinan Indonesia</h5>
                    <p class="text-muted">Platform terpercaya untuk kerajinan tangan Indonesia berkualitas tinggi. Kami
                        menghubungkan pengrajin lokal dengan pecinta seni di seluruh dunia.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Kategori</h5>
                    <ul class="list-unstyled">
                        @foreach ($categories->take(5) as $category)
                            <li><a
                                    href="{{ route('category', $category->idKategori) }}">{{ $category->nama_kategori }}</a>
                            </li>
                        @endforeach
                        @if ($categories->count() > 5)
                            <li><a href="{{ route('shop') }}" class="text-decoration-underline">Lihat Semua</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Layanan</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Panduan Belanja</a></li>
                        <li><a href="#">Kebijakan Return</a></li>
                        <li><a href="#">Pengiriman</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Hubungi Kami</h5>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Kerajinan No. 123, Jakarta, Indonesia</p>
                        <p><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
                        <p><i class="fas fa-envelope me-2"></i>info@kerajinanidonesia.com</p>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2026 Kerajinan Indonesia. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3">Syarat & Ketentuan</a>
                    <a href="#" class="text-muted">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr for notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Alternative setup for fetch API
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Helper function for CSRF-protected fetch requests
        function csrfFetch(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || window.Laravel.csrfToken
                }
            };

            // Merge headers with provided options
            if (options.headers) {
                options.headers = {
                    ...defaultOptions.headers,
                    ...options.headers
                };
            } else {
                options.headers = defaultOptions.headers;
            }

            return fetch(url, options);
        }

        // Logout confirmation function
        function confirmLogout(event) {
            event.preventDefault();

            if (confirm('Are you sure you want to logout?')) {
                // Show loading indicator
                const button = event.target;
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging out...';
                button.disabled = true;

                // Submit the form
                document.getElementById('logoutForm').submit();
            }
        }

        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        // Handle success/error messages with Toastr
        @if (session('success'))
            $(document).ready(function() {
                toastr.success('{{ session('success') }}', 'Success!');
            });
        @endif

        @if (session('error'))
            $(document).ready(function() {
                toastr.error('{{ session('error') }}', 'Error!');
            });
        @endif

        @if (session('warning'))
            $(document).ready(function() {
                toastr.warning('{{ session('warning') }}', 'Warning!');
            });
        @endif

        @if (session('info'))
            $(document).ready(function() {
                toastr.info('{{ session('info') }}', 'Info!');
            });
        @endif

        // Cart functionality
        function updateCartCount() {
            @auth
            // Only update cart count for authenticated users
            csrfFetch('/api/cart/count', {
                    method: 'GET'
                })
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

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
