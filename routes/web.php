<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AuthController;

// ====== AUTHENTICATION ROUTES ======
// Guest only routes (redirect if already authenticated)
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
});

// Authentication required routes
Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// AJAX endpoints for authentication (can be public)
Route::get('/api/auth/check-username', [AuthController::class, 'checkUsername'])->name('api.auth.check-username');
Route::get('/api/auth/status', [AuthController::class, 'status'])->name('api.auth.status');

// ====== PUBLIC ROUTES (No Authentication Required) ======
// Homepage and product browsing
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
Route::get('/product/{idProduk}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/category/{idKategori}', [FrontendController::class, 'category'])->name('category');
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/promotions', [FrontendController::class, 'promotions'])->name('promotions');

// AJAX endpoints for frontend functionality
Route::get('/api/search-suggestions', [FrontendController::class, 'searchSuggestions'])->name('api.search.suggestions');
Route::post('/api/filter-products', [FrontendController::class, 'filterProducts'])->name('api.filter.products');

// Cart API endpoints (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/api/cart/count', [TransaksiController::class, 'getCartCount'])->name('api.cart.count');
});

// Recently viewed products (can be public or require auth - choosing public for better UX)
Route::get('/recently-viewed', [FrontendController::class, 'recentlyViewed'])->name('recently.viewed');
Route::post('/clear-recently-viewed', [FrontendController::class, 'clearRecentlyViewed'])->name('clear.recently.viewed');
Route::post('/remove-from-recently-viewed', [FrontendController::class, 'removeFromRecentlyViewed'])->name('remove.recently.viewed');

// ====== USER ROUTES (Authentication Required - Role: user) ======
Route::group(['middleware' => ['auth', 'role:user']], function () {

    // Cart Management
    Route::post('/cart/add', [TransaksiController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [TransaksiController::class, 'viewCart'])->name('cart');
    Route::put('/cart/update', [TransaksiController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{idProduk}', [TransaksiController::class, 'removeFromCart'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [TransaksiController::class, 'checkout'])->name('checkout');
    Route::post('/order/place', [TransaksiController::class, 'placeOrder'])->name('order.place');
    Route::get('/payment/upload/{idPesanan}', [TransaksiController::class, 'showPaymentUpload'])->name('payment.upload');
    Route::post('/payment/upload/{idPesanan}', [TransaksiController::class, 'uploadPaymentProof'])->name('payment.store');
    Route::get('/order/success/{idPesanan}', [TransaksiController::class, 'orderSuccess'])->name('order.success');
    Route::get('/my-orders', [TransaksiController::class, 'myOrders'])->name('user.orders.index');
    Route::get('/order/{idPesanan}', [TransaksiController::class, 'orderDetail'])->name('user.orders.show');

    // Testimonials
    Route::post('/testimonial', [TransaksiController::class, 'storeTestimonial'])->name('testimonial.store');

    // ====== NOTIFICATIONS ======
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');

    // Test notification route (development only)
    Route::get('/test-notification', function () {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Create a test order for notification
        $user = Auth::user();
        $user->notify(new \App\Notifications\OrderStatusChanged(
            (object)['idPesanan' => 'TEST-001', 'nomor_resi' => null, 'total_harga' => 150000],
            'waiting_payment',
            'confirmed'
        ));

        return response()->json(['message' => 'Test notification sent!']);
    })->name('test.notification');
});

// ====== ADMIN ROUTES (Authentication Required - Role: admin) ======
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ====== KATEGORI MANAGEMENT ======
    Route::get('/categories', [ProdukController::class, 'indexKategori'])->name('admin.categories.index');
    Route::get('/categories/create', [ProdukController::class, 'createKategori'])->name('admin.categories.create');
    Route::post('/categories', [ProdukController::class, 'storeKategori'])->name('admin.categories.store');
    Route::get('/categories/{id}', [ProdukController::class, 'showKategori'])->name('admin.categories.show');
    Route::get('/categories/{id}/edit', [ProdukController::class, 'editKategori'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [ProdukController::class, 'updateKategori'])->name('admin.categories.update');
    Route::patch('/categories/{id}', [ProdukController::class, 'updateKategori'])->name('admin.categories.patch');
    Route::delete('/categories/{id}', [ProdukController::class, 'destroyKategori'])->name('admin.categories.destroy');

    // Legacy kategori routes for backward compatibility
    Route::get('/kategori', [ProdukController::class, 'indexKategori'])->name('admin.kategori.index');
    Route::get('/kategori/create', [ProdukController::class, 'createKategori'])->name('admin.kategori.create');
    Route::post('/kategori', [ProdukController::class, 'storeKategori'])->name('admin.kategori.store');
    Route::get('/kategori/{id}', [ProdukController::class, 'showKategori'])->name('admin.kategori.show');
    Route::get('/kategori/{id}/edit', [ProdukController::class, 'editKategori'])->name('admin.kategori.edit');
    Route::put('/kategori/{id}', [ProdukController::class, 'updateKategori'])->name('admin.kategori.update');
    Route::patch('/kategori/{id}', [ProdukController::class, 'updateKategori'])->name('admin.kategori.patch');
    Route::delete('/kategori/{id}', [ProdukController::class, 'destroyKategori'])->name('admin.kategori.destroy');

    // ====== PRODUK MANAGEMENT ======
    Route::get('/products', [ProdukController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProdukController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProdukController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}', [ProdukController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{id}/edit', [ProdukController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProdukController::class, 'update'])->name('admin.products.update');
    Route::patch('/products/{id}', [ProdukController::class, 'update'])->name('admin.products.patch');
    Route::delete('/products/{id}', [ProdukController::class, 'destroy'])->name('admin.products.destroy');

    // Legacy produk routes for backward compatibility
    Route::get('/produk', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('admin.produk.show');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::patch('/produk/{id}', [ProdukController::class, 'update'])->name('admin.produk.patch');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

    // ====== ADDITIONAL PRODUK MANAGEMENT ======
    Route::get('/produk/category/{categoryId}', [ProdukController::class, 'getByCategory'])->name('admin.produk.by-category');
    Route::get('/produk-search', [ProdukController::class, 'searchProducts'])->name('admin.produk.search');

    // ====== PROMOTION MANAGEMENT ======
    Route::get('/promotions', [ProdukController::class, 'indexPromosi'])->name('admin.promotions.index');
    Route::get('/promotions/create', [ProdukController::class, 'createPromosi'])->name('admin.promotions.create');
    Route::post('/promotions', [ProdukController::class, 'storePromosi'])->name('admin.promotions.store');
    Route::get('/promotions/{id}', [ProdukController::class, 'showPromosi'])->name('admin.promotions.show');
    Route::get('/promotions/{id}/edit', [ProdukController::class, 'editPromosi'])->name('admin.promotions.edit');
    Route::put('/promotions/{id}', [ProdukController::class, 'updatePromosi'])->name('admin.promotions.update');
    Route::delete('/promotions/{id}', [ProdukController::class, 'destroyPromosi'])->name('admin.promotions.destroy');
    Route::post('/promotions/{id}/toggle', [ProdukController::class, 'togglePromotionStatus'])->name('admin.promotions.toggle');

    // Legacy promosi routes for backward compatibility
    Route::get('/promosi', [ProdukController::class, 'indexPromosi'])->name('admin.promosi.index');
    Route::get('/promosi/create', [ProdukController::class, 'createPromosi'])->name('admin.promosi.create');
    Route::post('/promosi', [ProdukController::class, 'storePromosi'])->name('admin.promosi.store');
    Route::get('/promosi/{id}', [ProdukController::class, 'showPromosi'])->name('admin.promosi.show');
    Route::get('/promosi/{id}/edit', [ProdukController::class, 'editPromosi'])->name('admin.promosi.edit');
    Route::put('/promosi/{id}', [ProdukController::class, 'updatePromosi'])->name('admin.promosi.update');
    Route::delete('/promosi/{id}', [ProdukController::class, 'destroyPromosi'])->name('admin.promosi.destroy');
    Route::post('/promosi/{id}/toggle', [ProdukController::class, 'togglePromotionStatus'])->name('admin.promosi.toggle');

    // Quick promotion actions
    Route::post('/produk/{id}/promotion/apply', [ProdukController::class, 'quickApplyPromotion'])->name('admin.produk.promotion.apply');
    Route::get('/produk/{id}/promotions', [ProdukController::class, 'getProductPromotions'])->name('admin.produk.promotions');
    Route::post('/promosi/bulk-apply', [ProdukController::class, 'bulkApplyPromotion'])->name('admin.promosi.bulk-apply');

    // ====== PAYMENT CONFIRMATION MANAGEMENT ======
    Route::get('/payments/pending', [AdminController::class, 'pendingPayments'])->name('admin.payments.pending');
    Route::get('/payments/{idPesanan}/proof', [AdminController::class, 'showPaymentProof'])->name('admin.payments.show');
    Route::post('/payments/{idPesanan}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.payments.confirm');

    // ====== ORDER MANAGEMENT ======
    Route::get('/orders', [AdminController::class, 'indexOrders'])->name('admin.orders');
    Route::get('/orders/index', [AdminController::class, 'indexOrders'])->name('admin.orders.index');
    Route::get('/orders/{idPesanan}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{idPesanan}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');

    // ====== USER MANAGEMENT ======
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('admin.users');
    Route::get('/users/index', [AdminController::class, 'indexUsers'])->name('admin.users.index');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::put('/users/{id}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.update-status');

    // ====== ADMIN NOTIFICATIONS ======
    Route::get('/notifications', [NotificationController::class, 'adminIndex'])->name('admin.notifications');
    Route::get('/notifications/index', [NotificationController::class, 'adminIndex'])->name('admin.notifications.index');

    // ====== REPORTS & ANALYTICS ======
    Route::get('/reports/sales', [AdminController::class, 'salesReport'])->name('admin.reports.sales');
});

// ====== SHARED AUTHENTICATED ROUTES (Both admin and user can access) ======
Route::group(['middleware' => ['auth']], function () {

    // API endpoints for admin functionality (requires authentication)
    Route::get('/admin/api/produk/search', [ProdukController::class, 'apiSearchProducts'])->name('admin.api.produk.search');

    // Storage testing routes (development only)
    if (app()->environment(['local', 'testing'])) {
        Route::get('/storage/info', [App\Http\Controllers\StorageController::class, 'info'])->name('storage.info');
        Route::post('/storage/test-upload', [App\Http\Controllers\StorageController::class, 'testUpload'])->name('storage.test');
        Route::delete('/storage/cleanup', [App\Http\Controllers\StorageController::class, 'cleanup'])->name('storage.cleanup');
    }
});
