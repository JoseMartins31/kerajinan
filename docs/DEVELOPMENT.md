# Development Guide

## Setting Up Development Environment

### Prerequisites

- PHP 8.1 or higher
- Composer 2.0+
- Node.js 16+ & NPM
- MySQL 8.0+
- XAMPP/WAMP (for local development)

### Initial Setup

```bash
# Clone the repository
git clone <repository-url>
cd kerajinan

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link
```

### Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE kerajinan;

# Configure .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kerajinan
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate
php artisan db:seed
```

## Code Architecture

### MVC Pattern

The application follows Laravel's MVC architecture:

#### Models (`app/Models/`)

- **User.php**: User authentication and profile management
- **Produk.php**: Product catalog management
- **Kategori.php**: Product categories
- **Pesanan.php**: Order management
- **Pembayaran.php**: Payment processing
- **Promosi.php**: Promotion system

#### Controllers (`app/Http/Controllers/`)

- **AdminController.php**: Main admin dashboard and analytics
- **AuthController.php**: Authentication handling
- **ProductController.php**: Product CRUD operations
- **OrderController.php**: Order management
- **PaymentController.php**: Payment verification

#### Views (`resources/views/`)

- **layouts/admin.blade.php**: Main admin layout template
- **admin/**: Admin interface views
- **auth/**: Authentication forms

### Service Layer Pattern

For complex business logic, create service classes:

```php
// app/Services/SalesReportService.php
<?php

namespace App\Services;

use App\Models\Pesanan;
use Carbon\Carbon;

class SalesReportService
{
    public function generateReport($startDate, $endDate)
    {
        return Pesanan::with(['pesananItems.produk', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
    }

    public function calculateMetrics($orders)
    {
        return [
            'total_revenue' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'average_order' => $orders->avg('total'),
            'top_products' => $this->getTopProducts($orders)
        ];
    }
}
```

## Coding Standards

### PHP Standards (PSR-12)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): Response
    {
        $products = Produk::with('kategori')
            ->when($request->search, function ($query, $search) {
                return $query->where('namaProduk', 'like', "%{$search}%");
            })
            ->paginate(15);

        return response()->view('admin.produk.index', compact('products'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'namaProduk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'idKategori' => 'required|exists:kategori,idKategori',
        ]);

        $product = Produk::create($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Product created successfully.');
    }
}
```

### Blade Template Standards

```blade
{{-- resources/views/admin/produk/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Product Management')
@section('page-title', 'Products')
@section('page-description', 'Manage your product catalog')

@section('breadcrumb')
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product List</h5>
        </div>
        <div class="card-body">
            {{-- Content here --}}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Page-specific JavaScript
</script>
@endpush
```

### JavaScript Standards

```javascript
// Use strict mode
"use strict";

// Namespace your code
const ProductManager = {
    init() {
        this.bindEvents();
        this.initDataTable();
    },

    bindEvents() {
        $(document).on("click", ".btn-delete", this.handleDelete.bind(this));
        $(document).on("submit", "#product-form", this.handleSubmit.bind(this));
    },

    initDataTable() {
        $("#products-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "/admin/produk/data",
            columns: [
                { data: "namaProduk", name: "namaProduk" },
                { data: "harga", name: "harga", render: this.formatPrice },
                { data: "stok", name: "stok" },
                { data: "actions", name: "actions", orderable: false },
            ],
        });
    },

    formatPrice(data) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
        }).format(data);
    },

    handleDelete(e) {
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform delete action
            }
        });
    },
};

// Initialize when DOM is ready
$(document).ready(() => {
    ProductManager.init();
});
```

## Database Conventions

### Migration Standards

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('idProduk');
            $table->foreignId('idKategori')
                  ->constrained('kategori', 'idKategori')
                  ->onDelete('cascade');
            $table->string('namaProduk');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes for performance
            $table->index(['idKategori', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
```

### Model Standards

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'idProduk';

    protected $fillable = [
        'namaProduk',
        'deskripsi',
        'harga',
        'stok',
        'idKategori',
        'gambar',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'status' => 'string'
    ];

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'idKategori', 'idKategori');
    }

    public function pesananItems(): HasMany
    {
        return $this->hasMany(PesananItem::class, 'idProduk', 'idProduk');
    }

    // Accessors
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }
}
```

## Frontend Development

### Asset Management with Vite

```javascript
// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
```

### CSS Organization

```scss
// resources/css/app.css
@import "bootstrap/scss/bootstrap";

// Custom variables
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --success-color: #27ae60;
    --danger-color: #e74c3c;
}

// Component styles
.admin-sidebar {
    background: linear-gradient(
        135deg,
        var(--primary-color),
        var(--secondary-color)
    );

    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;

        &:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        &.active {
            color: white;
            background: var(--danger-color);
        }
    }
}

// Responsive design
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;

        &.show {
            transform: translateX(0);
        }
    }
}
```

## Testing Guidelines

### Feature Tests

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/admin/produk', [
                'namaProduk' => 'Test Product',
                'harga' => 100000,
                'stok' => 10,
                'idKategori' => 1
            ]);

        $response->assertRedirect('/admin/produk');
        $this->assertDatabaseHas('produk', [
            'namaProduk' => 'Test Product'
        ]);
    }

    public function test_customer_cannot_access_admin_product_page(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)
            ->get('/admin/produk');

        $response->assertStatus(403);
    }
}
```

### Unit Tests

```php
<?php

namespace Tests\Unit;

use App\Models\Produk;
use App\Services\SalesReportService;
use Tests\TestCase;

class SalesReportServiceTest extends TestCase
{
    public function test_calculate_metrics_returns_correct_data(): void
    {
        $orders = collect([
            (object)['total' => 100000],
            (object)['total' => 200000],
            (object)['total' => 150000],
        ]);

        $service = new SalesReportService();
        $metrics = $service->calculateMetrics($orders);

        $this->assertEquals(450000, $metrics['total_revenue']);
        $this->assertEquals(3, $metrics['total_orders']);
        $this->assertEquals(150000, $metrics['average_order']);
    }
}
```

## Performance Optimization

### Database Optimization

```php
// Use eager loading to prevent N+1 queries
$products = Produk::with(['kategori', 'promosi'])->get();

// Use database indexes for frequent queries
Schema::table('produk', function (Blueprint $table) {
    $table->index(['status', 'idKategori']);
});

// Cache expensive queries
$topProducts = Cache::remember('top_products', 3600, function () {
    return Produk::selectRaw('produk.*, SUM(pesanan_items.kuantitas) as total_sold')
        ->join('pesanan_items', 'produk.idProduk', '=', 'pesanan_items.idProduk')
        ->groupBy('produk.idProduk')
        ->orderBy('total_sold', 'desc')
        ->limit(10)
        ->get();
});
```

### Frontend Optimization

```javascript
// Lazy load DataTables
$(document).ready(function () {
    $("#products-table").DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: "/admin/produk/data",
    });
});

// Debounce search inputs
const searchInput = document.getElementById("search");
let searchTimeout;

searchInput.addEventListener("input", function (e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(e.target.value);
    }, 300);
});
```

## Security Best Practices

### Input Validation

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'namaProduk' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\_]+$/',
        'harga' => 'required|numeric|min:0|max:999999999',
        'stok' => 'required|integer|min:0|max:9999',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
    ]);

    // Additional business logic validation
    if ($validated['stok'] > 1000) {
        return back()->withErrors(['stok' => 'Stock cannot exceed 1000 items']);
    }

    // Sanitize input
    $validated['namaProduk'] = strip_tags($validated['namaProduk']);

    Produk::create($validated);
}
```

### File Upload Security

```php
public function uploadProductImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
    ]);

    $file = $request->file('image');

    // Generate secure filename
    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

    // Store in secure location
    $path = $file->storeAs('products', $filename, 'public');

    return response()->json(['filename' => $filename]);
}
```

## Deployment Checklist

### Pre-deployment

- [ ] Run all tests: `php artisan test`
- [ ] Update dependencies: `composer update --no-dev`
- [ ] Build assets: `npm run build`
- [ ] Clear caches: `php artisan cache:clear`
- [ ] Optimize: `php artisan optimize`

### Production Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-production-host
DB_PORT=3306
DB_DATABASE=kerajinan_production
DB_USERNAME=production_user
DB_PASSWORD=secure_password
```

### Server Configuration

```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/kerajinan
sudo chmod -R 755 /var/www/kerajinan/storage
sudo chmod -R 755 /var/www/kerajinan/bootstrap/cache

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting Common Issues

### Database Connection Issues

```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Check MySQL service
sudo systemctl status mysql
```

### Permission Issues

```bash
# Fix storage permissions
sudo chmod -R 775 storage/
sudo chown -R www-data:www-data storage/
```

### Asset Loading Problems

```bash
# Clear and rebuild assets
npm run build
php artisan view:clear
```

## Git Workflow

### Branch Naming

- `feature/product-management`
- `bugfix/payment-validation`
- `hotfix/security-patch`

### Commit Messages

```
feat: add product export functionality

- Implement Excel export for product catalog
- Add filtering options for export
- Include product images in export data

Closes #123
```

### Pre-commit Hooks

```json
// package.json
{
    "husky": {
        "hooks": {
            "pre-commit": "php artisan test && npm run lint"
        }
    }
}
```

This development guide provides comprehensive guidelines for maintaining and extending the Kerajinan e-commerce system. Follow these standards to ensure code quality, security, and maintainability.
