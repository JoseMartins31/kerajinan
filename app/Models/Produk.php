<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Produk Model - Enhanced with promotional business logic
 *
 * Core product model with extensive promotional pricing functionality.
 * Handles automatic price calculations based on active promotions.
 *
 * Key Features:
 * - Custom primary key (idProduk)
 * - Promotional price calculations
 * - Active promotion detection
 * - Currency formatting methods
 * - Relationship management
 *
 * Promotional Methods:
 * - getActivePromotions(): Get all active promotions for this product
 * - getBestActivePromotion(): Get highest discount active promotion
 * - getCurrentPrice(): Calculate price after applying best promotion
 * - hasActivePromotion(): Check if product has active promotions
 * - getDiscountAmount/Percentage(): Calculate savings
 * - getFormattedPrices(): Currency formatted prices
 *
 * Usage:
 * $product = Produk::find(1);
 * $discountedPrice = $product->getCurrentPrice();
 * $hasDiscount = $product->hasActivePromotion();
 */
class Produk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produk';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idProduk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_upload',
        'nama_produk',
        'deskripsi',
        'Harga',
        'foto',
        'status',
        'stok',
        'idKategori',
        'idUser',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_upload' => 'datetime',
            'Harga' => 'float',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'idKategori', 'idKategori');
    }

    /**
     * Get the user that uploaded the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    /**
     * Get the promotions for this product.
     */
    public function promosi(): HasMany
    {
        return $this->hasMany(Promosi::class, 'idProduk', 'idProduk');
    }

    /**
     * Get the testimonials for this product.
     */
    public function testimoni(): HasMany
    {
        return $this->hasMany(Testimoni::class, 'idProduk', 'idProduk');
    }

    /**
     * Get the order details for this product.
     */
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'idProduk', 'idProduk');
    }

    // ====== PROMOTION BUSINESS LOGIC ======

    /**
     * Get active promotions for this product.
     */
    public function getActivePromotions()
    {
        return $this->promosi()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now())
            ->get();
    }

    /**
     * Get the best active promotion (highest discount).
     */
    public function getBestActivePromotion()
    {
        return $this->getActivePromotions()
            ->sortByDesc('diskon')
            ->first();
    }

    /**
     * Calculate current price after applying promotions.
     */
    public function getCurrentPrice()
    {
        $activePromotion = $this->getBestActivePromotion();

        if (!$activePromotion) {
            return $this->Harga;
        }

        // Assuming diskon is percentage (0-100)
        $discountedPrice = $this->Harga * (1 - $activePromotion->diskon / 100);

        return max(0, $discountedPrice);
    }

    /**
     * Get discount amount.
     */
    public function getDiscountAmount()
    {
        return $this->Harga - $this->getCurrentPrice();
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentage()
    {
        $activePromotion = $this->getBestActivePromotion();
        return $activePromotion ? $activePromotion->diskon : 0;
    }

    /**
     * Check if product has active promotion.
     */
    public function hasActivePromotion()
    {
        return $this->getActivePromotions()->count() > 0;
    }

    /**
     * Get original price (alias for Harga for consistency).
     */
    public function getOriginalPrice()
    {
        return $this->Harga;
    }

    /**
     * Format price with currency.
     */
    public function getFormattedCurrentPrice()
    {
        return 'Rp ' . number_format($this->getCurrentPrice(), 0, ',', '.');
    }

    /**
     * Format original price with currency.
     */
    public function getFormattedOriginalPrice()
    {
        return 'Rp ' . number_format($this->getOriginalPrice(), 0, ',', '.');
    }
}
