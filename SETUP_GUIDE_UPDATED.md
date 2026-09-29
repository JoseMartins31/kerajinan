# Database Notification System & E-commerce Features - Setup Guide

## Step-by-Step Implementation Guide

### 1. **Database Setup**

```bash
# Run all migrations including notifications and nomor_resi
php artisan migrate
```

### 2. **Include Notification Bell in Layout**

**Option A: If you have a main layout file (e.g., `resources/views/layouts/app.blade.php`)**
Add this in your navigation section:

```blade
@auth
    @include('components.notification-dropdown')
@endauth
```

**Option B: If you need to add Alpine.js and Tailwind CSS**
Add these to your layout's `<head>` section:

```blade
<!-- Alpine.js for interactive components -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Tailwind CSS for styling -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- CSRF Token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### 3. **Frontend Routes (Already Added)**

These routes are configured for the complete e-commerce experience:

```php
// Homepage and product browsing
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
Route::get('/product/{idProduk}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/category/{idKategori}', [FrontendController::class, 'category'])->name('category');
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/promotions', [FrontendController::class, 'promotions'])->name('promotions');
Route::get('/recently-viewed', [FrontendController::class, 'recentlyViewed'])->name('recently.viewed');

// AJAX endpoints
Route::get('/api/search-suggestions', [FrontendController::class, 'searchSuggestions'])->name('api.search.suggestions');
Route::post('/api/filter-products', [FrontendController::class, 'filterProducts'])->name('api.filter.products');
```

### 3.1. **Admin Promotion Management Routes (Already Added)**

These routes handle promotion creation and management:

```php
// Main promotion CRUD
Route::get('/admin/promosi', [ProdukController::class, 'indexPromosi'])->name('admin.promosi.index');
Route::get('/admin/promosi/create', [ProdukController::class, 'createPromosi'])->name('admin.promosi.create');
Route::post('/admin/promosi', [ProdukController::class, 'storePromosi'])->name('admin.promosi.store');
Route::get('/admin/promosi/{id}', [ProdukController::class, 'showPromosi'])->name('admin.promosi.show');
Route::get('/admin/promosi/{id}/edit', [ProdukController::class, 'editPromosi'])->name('admin.promosi.edit');
Route::put('/admin/promosi/{id}', [ProdukController::class, 'updatePromosi'])->name('admin.promosi.update');
Route::delete('/admin/promosi/{id}', [ProdukController::class, 'destroyPromosi'])->name('admin.promosi.destroy');

// Quick promotion actions
Route::post('/admin/promosi/{id}/toggle', [ProdukController::class, 'togglePromotionStatus'])->name('admin.promosi.toggle');
Route::post('/admin/produk/{id}/promotion/apply', [ProdukController::class, 'quickApplyPromotion'])->name('admin.produk.promotion.apply');
Route::get('/admin/produk/{id}/promotions', [ProdukController::class, 'getProductPromotions'])->name('admin.produk.promotions');
Route::post('/admin/promosi/bulk-apply', [ProdukController::class, 'bulkApplyPromotion'])->name('admin.promosi.bulk-apply');
```

### 4. **Using Promotional Pricing in Views**

**Display Product Prices with Promotions:**

```blade
{{-- Product Card or Detail View --}}
<div class="product-price">
    @if($product->hasActivePromotion())
        {{-- Original price (crossed out) --}}
        <span class="original-price text-gray-500 line-through">
            {{ $product->getFormattedOriginalPrice() }}
        </span>

        {{-- Sale price --}}
        <span class="sale-price text-red-600 font-bold text-lg">
            {{ $product->getFormattedCurrentPrice() }}
        </span>

        {{-- Discount badge --}}
        <span class="discount-badge bg-red-500 text-white px-2 py-1 rounded text-sm">
            -{{ $product->getDiscountPercentage() }}%
        </span>

        {{-- Savings amount --}}
        <div class="savings text-green-600 text-sm">
            You save Rp {{ number_format($product->getDiscountAmount(), 0, ',', '.') }}
        </div>
    @else
        <span class="regular-price font-bold text-lg">
            {{ $product->getFormattedOriginalPrice() }}
        </span>
    @endif
</div>

{{-- Promotion indicator --}}
@if($product->hasActivePromotion())
    <div class="promotion-indicator">
        <span class="sale-badge bg-red-500 text-white px-3 py-1 rounded-full text-xs">
            SALE
        </span>
    </div>
@endif
```

**Product Grid with Promotions:**

```blade
<div class="product-grid grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($products as $product)
        <div class="product-card bg-white rounded-lg shadow-md p-4">
            {{-- Product Image --}}
            <img src="{{ $product->foto }}" alt="{{ $product->nama_produk }}" class="w-full h-48 object-cover rounded-lg">

            {{-- Product Name --}}
            <h3 class="mt-2 font-semibold">{{ $product->nama_produk }}</h3>

            {{-- Pricing --}}
            <div class="mt-2">
                @if($product->hasActivePromotion())
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500 line-through text-sm">
                            {{ $product->getFormattedOriginalPrice() }}
                        </span>
                        <span class="text-red-600 font-bold">
                            {{ $product->getFormattedCurrentPrice() }}
                        </span>
                    </div>
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                        -{{ $product->getDiscountPercentage() }}%
                    </span>
                @else
                    <span class="font-bold">{{ $product->getFormattedOriginalPrice() }}</span>
                @endif
            </div>

            {{-- Add to Cart Button --}}
            <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Add to Cart
            </button>
        </div>
    @endforeach
</div>
```

### 5. **Advanced Shop Features**

**Filter and Sort Options:**

```blade
<div class="shop-filters flex flex-wrap gap-4 mb-6">
    {{-- Search --}}
    <input type="text" name="search" placeholder="Search products..."
           value="{{ $currentFilters['search'] }}" class="border rounded px-3 py-2">

    {{-- Category Filter --}}
    <select name="category" class="border rounded px-3 py-2">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->idKategori }}"
                    {{ $currentFilters['category'] == $category->idKategori ? 'selected' : '' }}>
                {{ $category->nama_kategori }}
            </option>
        @endforeach
    </select>

    {{-- Price Range --}}
    <input type="number" name="min_price" placeholder="Min Price"
           value="{{ $currentFilters['min_price'] }}" class="border rounded px-3 py-2 w-24">
    <input type="number" name="max_price" placeholder="Max Price"
           value="{{ $currentFilters['max_price'] }}" class="border rounded px-3 py-2 w-24">

    {{-- On Sale Filter --}}
    <label class="flex items-center">
        <input type="checkbox" name="on_sale" value="1"
               {{ $currentFilters['on_sale'] ? 'checked' : '' }} class="mr-2">
        On Sale Only
    </label>

    {{-- Sort Options --}}
    <select name="sort_by" class="border rounded px-3 py-2">
        <option value="latest" {{ $currentFilters['sort_by'] == 'latest' ? 'selected' : '' }}>Latest</option>
        <option value="price_low" {{ $currentFilters['sort_by'] == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
        <option value="price_high" {{ $currentFilters['sort_by'] == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
        <option value="on_sale" {{ $currentFilters['sort_by'] == 'on_sale' ? 'selected' : '' }}>Best Deals</option>
        <option value="name_asc" {{ $currentFilters['sort_by'] == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
    </select>
</div>
```

### 6. **Homepage with Promotions**

```blade
{{-- Featured Products Section --}}
<section class="featured-products mb-12">
    <h2 class="text-2xl font-bold mb-6">Featured Products</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
            {{-- Use product card template from above --}}
        @endforeach
    </div>
</section>

{{-- Products on Sale Section --}}
<section class="promoted-products mb-12">
    <h2 class="text-2xl font-bold mb-6 text-red-600">🔥 Special Deals</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($promotedProducts as $product)
            {{-- Highlighted promotion cards --}}
            <div class="product-card bg-gradient-to-br from-red-50 to-pink-50 border-2 border-red-200 rounded-lg p-4">
                {{-- Product content with prominent sale indicators --}}
                <div class="sale-banner bg-red-500 text-white text-center py-1 -mx-4 -mt-4 mb-4 rounded-t-lg">
                    SAVE {{ $product->getDiscountPercentage() }}%
                </div>
                {{-- Rest of product card --}}
            </div>
        @endforeach
    </div>
</section>
```

### 7. **Search with Autocomplete**

```javascript
// Add this to your layout for search suggestions
document.getElementById("search-input").addEventListener("input", function () {
    const term = this.value;
    if (term.length < 2) return;

    fetch(`/api/search-suggestions?term=${term}`)
        .then((response) => response.json())
        .then((suggestions) => {
            // Display suggestions dropdown
            const dropdown = document.getElementById("search-suggestions");
            dropdown.innerHTML = "";

            suggestions.forEach((product) => {
                const item = document.createElement("a");
                item.href = product.url;
                item.className = "block px-4 py-2 hover:bg-gray-100";
                item.innerHTML = `
                    <div class="flex items-center">
                        <img src="${product.image}" class="w-8 h-8 rounded mr-3">
                        <div>
                            <div class="font-medium">${product.name}</div>
                            <div class="text-sm text-gray-600">Rp ${product.price}</div>
                        </div>
                    </div>
                `;
                dropdown.appendChild(item);
            });
        });
});
```

### 8. **Notification System Usage**

**Check notification bell appears:**

- Login as user
- Look for bell icon in navigation
- Should show unread count if there are notifications

**Test notification workflow:**

1. Create order as user → Upload payment proof
2. Login as admin → Confirm/reject payment
3. Change order status (processing → shipped with nomor_resi)
4. Check user receives notifications with tracking number

### 9. **Available Product Methods**

The Produk model now includes these promotional methods:

```php
// Check for promotions
$product->hasActivePromotion()                    // Returns boolean
$product->getActivePromotions()                   // Returns collection of active promotions
$product->getBestActivePromotion()                // Returns promotion with highest discount

// Price calculations
$product->getCurrentPrice()                       // Price after promotions
$product->getOriginalPrice()                      // Original price (same as Harga)
$product->getDiscountAmount()                     // Discount amount in currency
$product->getDiscountPercentage()                 // Discount percentage

// Formatted prices
$product->getFormattedCurrentPrice()              // "Rp 150,000"
$product->getFormattedOriginalPrice()             // "Rp 200,000"
```

### 10. **Frontend Controller Features**

**Available Routes and Features:**

- **Homepage**: Featured products + promoted products sections
- **Shop**: Advanced filtering (category, price, on-sale), multiple sorting options
- **Product Detail**: Full product info with promotions and related products
- **Category Pages**: Category-specific browsing with filters
- **Search**: Advanced search with autocomplete suggestions
- **Recently Viewed**: Session-based browsing history
- **Promotions**: Dedicated promotions page

### 11. **Admin Promotion Management System**

**Create and Manage Promotions:**

**Basic Promotion Creation:**

```php
// Create promotion form fields
$promotion = [
    'idProduk' => 123,                    // Product ID
    'tanggal_mulai' => '2026-01-21',      // Start date (today or later)
    'tanggal_berakhir' => '2026-01-31',   // End date (must be after start)
    'diskon' => 25,                       // Discount percentage (0-100)
    'status' => 'aktif'                   // Status: 'aktif' or 'tidak_aktif'
];
```

**Quick Apply Promotion (AJAX):**

```javascript
// Apply promotion to single product
function applyQuickPromotion(productId, discount, endDate) {
    fetch(`/admin/produk/${productId}/promotion/apply`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            diskon: discount, // e.g., 20 (for 20% discount)
            tanggal_berakhir: endDate, // e.g., '2026-01-27'
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Promotion applied successfully!");
            } else {
                alert("Error: " + data.message);
            }
        });
}

// Usage example
applyQuickPromotion(123, 20, "2026-01-27"); // 20% off until Jan 27
```

**Bulk Apply Promotions:**

```php
// Apply same promotion to multiple products
$bulkPromotion = [
    'product_ids' => [1, 2, 3, 4, 5],        // Array of product IDs
    'tanggal_mulai' => '2026-01-22',          // Start date
    'tanggal_berakhir' => '2026-01-31',       // End date
    'diskon' => 15,                           // 15% discount
    'status' => 'aktif'
];
```

**Admin Promotion Management Interface:**

```blade
{{-- Promotion List View --}}
<div class="promotions-list">
    @foreach($promosi as $promotion)
        <div class="promotion-card border rounded-lg p-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold">{{ $promotion->produk->nama_produk }}</h3>
                    <p class="text-gray-600">{{ $promotion->diskon }}% OFF</p>
                    <p class="text-sm">{{ $promotion->tanggal_mulai }} to {{ $promotion->tanggal_berakhir }}</p>
                </div>
                <span class="px-2 py-1 rounded text-sm {{
                    $promotion->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }}">
                    {{ ucfirst($promotion->status) }}
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('admin.promosi.edit', $promotion->idPromosi) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</a>

                <form method="POST" action="{{ route('admin.promosi.toggle', $promotion->idPromosi) }}" class="inline">
                    @csrf
                    <button class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                        {{ $promotion->status === 'aktif' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.promosi.destroy', $promotion->idPromosi) }}"
                      class="inline" onsubmit="return confirm('Delete this promotion?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
```

**Promotion Business Logic:**

- **Conflict Detection**: Prevents overlapping active promotions for same product
- **Date Validation**: Start date must be today or later, end date after start
- **Discount Validation**: 0-100% discount percentage
- **Bulk Operations**: Apply to multiple products with individual conflict checking

### 12. **Admin Features**

**Order Management with Shipping:**

- Add `nomor_resi` when updating order status to "shipped"
- Users receive notifications with tracking numbers
- Payment confirmation/rejection with admin notes

### 13. **Troubleshooting**

**If promotional prices don't display:**

1. Check promosi table has active promotions with valid dates
2. Verify `status = 'aktif'` in promosi records
3. Check date ranges (tanggal_mulai <= now() <= tanggal_berakhir)

**If promotion conflicts occur:**

1. Check for overlapping date ranges on same product
2. Verify only one active promotion per product per time period
3. Use promotion management interface to resolve conflicts

**If quick apply promotion fails:**

1. Ensure product exists and is valid
2. Check date is in future
3. Verify no existing active promotion for that period
4. Check CSRF token in AJAX requests

**If notifications don't work:**

1. Run `php artisan migrate` to create notifications table
2. Verify CSRF token in layout
3. Check notification routes are registered

**If search suggestions fail:**

1. Check AJAX endpoint is accessible
2. Verify JavaScript console for errors
3. Ensure products have proper data

### 14. **Production Checklist**

- [ ] Run `php artisan migrate` (notifications + nomor_resi)
- [ ] Add notification bell to main layout
- [ ] Include CSS/JS dependencies (Alpine.js, Tailwind)
- [ ] Test promotional pricing display
- [ ] Verify search and filtering work
- [ ] Test notification workflow
- [ ] Check admin shipping number functionality
- [ ] **Test promotion management system**
- [ ] **Create sample promotions and verify conflict detection**
- [ ] **Test quick apply and bulk promotion features**
- [ ] Optimize product queries for large datasets

## Quick Start Checklist

- [ ] Run migrations
- [ ] Update main layout with notification bell
- [ ] Test homepage with featured/promoted products
- [ ] Verify shop filtering and search work
- [ ] Test promotional pricing display
- [ ] Confirm notification system works
- [ ] Test order tracking with nomor_resi
- [ ] **Set up admin promotion management**
- [ ] **Test promotion creation and conflict detection**
- [ ] **Verify promotional prices display correctly on frontend**

## Promotion Management Quick Reference

**Access Promotion Management:** `/admin/promosi`

**Create New Promotion:** `/admin/promosi/create`

- Select product
- Set start/end dates
- Set discount percentage (0-100%)
- Choose status (aktif/tidak_aktif)

**Quick Apply Promotion:** Use AJAX on product management pages

```javascript
applyQuickPromotion(productId, discountPercent, endDate);
```

**Bulk Apply:** `/admin/promosi/bulk-apply`

- Select multiple products
- Apply same promotion settings
- System handles conflicts automatically

**Toggle Status:** Click toggle button on promotion list

- Activates/deactivates without deleting
- Preserves promotion data for future use

Your comprehensive e-commerce platform with full promotion management is now ready!
