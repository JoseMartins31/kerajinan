<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Testimoni;
use App\Models\Promosi;
use Illuminate\Support\Facades\Session;

/**
 * FrontendController - E-commerce customer-facing interface
 *
 * Handles all public and customer-facing functionality:
 * - Homepage with featured products and promotions
 * - Product catalog with advanced filtering and search
 * - Category browsing and product discovery
 * - Promotional product displays
 * - Search functionality with autocomplete
 * - Recently viewed products tracking
 *
 * Key Features:
 * - Advanced product filtering (category, price, rating, promotion status)
 * - Search with autocomplete suggestions (AJAX)
 * - Session-based recently viewed products
 * - Promotional pricing integration
 * - Responsive product catalog
 * - AJAX-powered filtering for better UX
 *
 * Recent additions:
 * - Complete promotional system integration
 * - Advanced search and filtering
 * - Recently viewed products tracking
 */
class FrontendController extends Controller
{
    // ====== HOME PAGE ======

    /**
     * Display homepage with featured products and promotions.
     */
    public function index()
    {
        // Get featured products with promotions loaded
        $featuredProducts = Produk::with(['kategori', 'user', 'promosi' => function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now());
        }])
            ->where('stok', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Get products on promotion (separate section)
        $promotedProducts = $this->getPromotedProducts(6);

        // Get active promotions for banners/announcements
        $promotions = Promosi::with('produk')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now())
            ->where('status', 'aktif')
            ->latest()
            ->take(3)
            ->get();

        // Get recent testimonials
        $testimonials = Testimoni::with(['user', 'produk'])
            ->latest()
            ->take(6)
            ->get();

        // Get categories for navigation
        $categories = Kategori::withCount('produk')->get();

        return view('frontend.home', compact('featuredProducts', 'promotedProducts', 'promotions', 'testimonials', 'categories'));
    }

    // ====== PRODUCT CATALOG ======

    /**
     * Display products with filtering, searching, and pagination.
     */
    public function shop(Request $request)
    {
        $query = Produk::with(['kategori', 'user'])->where('stok', '>', 0);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('idKategori', $request->category);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            // Note: This is a simplified approach. For production, you might want to use a computed column or raw SQL
            $query->where('Harga', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('Harga', '<=', $request->max_price);
        }

        // Stock filter
        if ($request->has('in_stock') && $request->in_stock) {
            $query->where('stok', '>', 0);
        }

        // Promotion filter
        if ($request->has('on_sale') && $request->on_sale) {
            $query->whereHas('promosi', function ($q) {
                $q->where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_akhir', '>=', now());
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('Harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('Harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            case 'on_sale':
                // Show products with promotions first
                $query->leftJoin('promosi', function ($join) {
                    $join->on('produk.idProduk', '=', 'promosi.idProduk')
                        ->where('promosi.status', 'aktif')
                        ->where('promosi.tanggal_mulai', '<=', now())
                        ->where('promosi.tanggal_akhir', '>=', now());
                })->orderByRaw('CASE WHEN promosi.idProduk IS NOT NULL THEN 0 ELSE 1 END')
                    ->orderBy('promosi.diskon', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default: // latest
                $query->latest();
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Get categories for filter sidebar
        $categories = Kategori::withCount('produk')->get();

        // Get price range for filter
        $priceRange = [
            'min' => Produk::where('stok', '>', 0)->min('Harga') ?? 0,
            'max' => Produk::where('stok', '>', 0)->max('Harga') ?? 0
        ];

        // Get current filters for display
        $currentFilters = [
            'search' => $request->search,
            'category' => $request->category,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'on_sale' => $request->on_sale,
            'sort_by' => $sortBy,
            'per_page' => $perPage
        ];

        return view('frontend.shop', compact('products', 'categories', 'priceRange', 'currentFilters'));
    }

    // ====== PRODUCT DETAILS ======

    /**
     * Display single product details.
     */
    public function productDetail($idProduk)
    {
        $product = Produk::with(['kategori', 'user', 'promosi' => function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now());
        }])->findOrFail($idProduk);

        // Get related products from same category (with promotions)
        $relatedProducts = Produk::with(['kategori', 'user', 'promosi' => function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now());
        }])
            ->where('idKategori', $product->idKategori)
            ->where('idProduk', '!=', $idProduk)
            ->where('stok', '>', 0)
            ->take(4)
            ->get();

        // Get product reviews/testimonials
        $testimonials = Testimoni::with('user')
            ->where('idProduk', $idProduk)
            ->latest()
            ->take(5)
            ->get();

        // Calculate average rating if testimonials exist
        $averageRating = $testimonials->avg('rating') ?? 0;
        $totalReviews = $testimonials->count();

        // Store in recently viewed (session)
        $this->addToRecentlyViewed($idProduk);

        return view('frontend.product-detail', compact('product', 'relatedProducts', 'testimonials', 'averageRating', 'totalReviews'));
    }

    // ====== CATEGORY PAGES ======

    /**
     * Display products by category.
     */
    public function category($idKategori, Request $request)
    {
        $category = Kategori::findOrFail($idKategori);

        $query = Produk::with(['kategori', 'user'])
            ->where('idKategori', $idKategori)
            ->where('stok', '>', 0);

        // Search within category
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('Harga', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('Harga', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('Harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('Harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Get price range for this category
        $priceRange = [
            'min' => Produk::where('idKategori', $idKategori)->where('stok', '>', 0)->min('Harga') ?? 0,
            'max' => Produk::where('idKategori', $idKategori)->where('stok', '>', 0)->max('Harga') ?? 0
        ];

        $currentFilters = [
            'search' => $request->search,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'sort_by' => $sortBy,
            'per_page' => $perPage
        ];

        return view('frontend.category', compact('category', 'products', 'priceRange', 'currentFilters'));
    }

    // ====== SEARCH FUNCTIONALITY ======

    /**
     * Advanced search with filters.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $searchTerm = $request->q;

        $query = Produk::with(['kategori', 'user'])
            ->where('stok', '>', 0)
            ->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('kategori', function ($q) use ($searchTerm) {
                        $q->where('nama_kategori', 'LIKE', "%{$searchTerm}%");
                    });
            });

        // Additional filters
        if ($request->has('category') && $request->category) {
            $query->where('idKategori', $request->category);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('harga', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'relevance');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'latest':
                $query->latest();
                break;
            default: // relevance - products with name matching first
                $query->orderByRaw("CASE WHEN nama_produk LIKE '%{$searchTerm}%' THEN 1 ELSE 2 END")
                    ->orderBy('nama_produk', 'asc');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        $categories = Kategori::withCount('produk')->get();

        return view('frontend.search-results', compact('products', 'searchTerm', 'categories'));
    }

    // ====== PROMOTIONS ======

    /**
     * Display active promotions.
     */
    public function promotions()
    {
        // Get all active promotions with product and category data
        $promotions = Promosi::with(['produk.kategori'])
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now())
            ->where('status', 'aktif')
            ->orderBy('diskon', 'desc') // Best discounts first
            ->paginate(9);

        // Get flash sales (ending within 24 hours)
        $flashSales = Promosi::with(['produk.kategori'])
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now())
            ->where('tanggal_akhir', '<=', now()->addHours(24))
            ->orderBy('tanggal_akhir', 'asc')
            ->take(6)
            ->get();

        // Get hot deals (30% or more discount)
        $hotDeals = Promosi::with(['produk.kategori'])
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now())
            ->where('diskon', '>=', 30)
            ->orderBy('diskon', 'desc')
            ->take(6)
            ->get();

        // Get categories with active promotions
        $categoriesWithPromotions = Kategori::whereHas('produk.promosi', function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now());
        })->withCount(['produk' => function ($query) {
            $query->whereHas('promosi', function ($q) {
                $q->where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_akhir', '>=', now());
            });
        }])->get();

        return view('frontend.promotions', compact('promotions', 'flashSales', 'hotDeals', 'categoriesWithPromotions'));
    }

    // ====== RECENTLY VIEWED ======

    /**
     * Display recently viewed products.
     */
    public function recentlyViewed()
    {
        $recentlyViewedIds = Session::get('recently_viewed', []);

        if (empty($recentlyViewedIds)) {
            return view('frontend.recently-viewed', ['products' => collect()]);
        }

        // Get products in the order they were viewed (most recent first)
        $products = collect();
        foreach (array_reverse($recentlyViewedIds) as $productId) {
            $product = Produk::with(['kategori', 'user', 'promosi' => function ($query) {
                $query->where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_akhir', '>=', now());
            }])->find($productId);
            if ($product && $product->stok > 0) {
                $products->push($product);
            }
        }

        return view('frontend.recently-viewed', compact('products'));
    }

    /**
     * Clear all recently viewed products.
     */
    public function clearRecentlyViewed()
    {
        try {
            Session::forget('recently_viewed');

            return response()->json([
                'success' => true,
                'message' => 'Recently viewed products cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear recently viewed products'
            ], 500);
        }
    }

    /**
     * Remove single product from recently viewed.
     */
    public function removeFromRecentlyViewed(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $recentlyViewed = Session::get('recently_viewed', []);

            // Remove the product ID from the array
            $recentlyViewed = array_filter($recentlyViewed, function ($id) use ($productId) {
                return $id != $productId;
            });

            // Re-index the array
            $recentlyViewed = array_values($recentlyViewed);

            Session::put('recently_viewed', $recentlyViewed);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from recently viewed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from recently viewed'
            ], 500);
        }
    }

    // ====== AJAX ENDPOINTS ======

    /**
     * Get product suggestions for search autocomplete.
     */
    public function searchSuggestions(Request $request)
    {
        $term = $request->get('term', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $suggestions = Produk::where('stok', '>', 0)
            ->where('nama_produk', 'LIKE', "%{$term}%")
            ->select('idProduk', 'nama_produk', 'harga', 'gambar_produk')
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->idProduk,
                    'name' => $product->nama_produk,
                    'price' => number_format($product->harga, 0, ',', '.'),
                    'image' => $product->gambar_produk,
                    'url' => route('product.detail', $product->idProduk)
                ];
            });

        return response()->json($suggestions);
    }

    /**
     * Get filtered products (AJAX for dynamic filtering).
     */
    public function filterProducts(Request $request)
    {
        $query = Produk::with(['kategori', 'user'])->where('stok', '>', 0);

        // Apply filters
        if ($request->category) {
            $query->where('idKategori', $request->category);
        }

        if ($request->min_price) {
            $query->where('harga', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('harga', '<=', $request->max_price);
        }

        if ($request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Sorting
        switch ($request->sort_by) {
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate($request->per_page ?? 12);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }

    // ====== HELPER METHODS ======

    /**
     * Add product to recently viewed list.
     */
    private function addToRecentlyViewed($productId)
    {
        $recentlyViewed = Session::get('recently_viewed', []);

        // Remove if already exists
        $recentlyViewed = array_filter($recentlyViewed, function ($id) use ($productId) {
            return $id != $productId;
        });

        // Add to beginning of array
        array_unshift($recentlyViewed, $productId);

        // Keep only last 10 items
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);

        Session::put('recently_viewed', $recentlyViewed);
    }

    /**
     * Get trending/popular products based on views or orders.
     */
    public function getTrendingProducts($limit = 8)
    {
        // This would ideally use view counts or order frequency
        // For now, get latest products as trending (with promotions)
        return Produk::with(['kategori', 'user', 'promosi' => function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now());
        }])
            ->where('stok', '>', 0)
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get products currently on promotion.
     */
    public function getPromotedProducts($limit = 8)
    {
        return Produk::with(['kategori', 'user', 'promosi' => function ($query) {
            $query->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now())
                ->orderBy('diskon', 'desc'); // Best discounts first
        }])
            ->whereHas('promosi', function ($query) {
                $query->where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_akhir', '>=', now());
            })
            ->where('stok', '>', 0)
            ->take($limit)
            ->get();
    }
}
