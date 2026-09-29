<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageHelper
{
    /**
     * Get product image URL with fallback to placeholder
     */
    public static function getImageUrl($product, $size = 'medium')
    {
        if ($product && $product->foto) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($product->foto)) {
                return Storage::url($product->foto);
            }
        }

        // Return placeholder URL based on product category
        return self::getPlaceholderUrl($product, $size);
    }

    /**
     * Get placeholder URL for product
     */
    public static function getPlaceholderUrl($product = null, $size = 'medium')
    {
        $category = 'default';

        if ($product && isset($product->kategori)) {
            $category = Str::slug($product->kategori->nama_kategori ?? 'default');
        }

        // You can extend this to use actual placeholder images from online services
        return "https://via.placeholder.com/400x400/667eea/ffffff?text=" .
            urlencode($product->nama_produk ?? 'Product');
    }

    /**
     * Get professional placeholder from Unsplash based on category
     */
    public static function getUnsplashPlaceholder($product = null, $width = 400, $height = 400)
    {
        $keywords = 'palm,leaves,wicker,basket';

        if ($product && isset($product->kategori)) {
            $categoryName = strtolower($product->kategori->nama_kategori ?? '');

            $keywords = match (true) {
                str_contains($categoryName, 'tas') || str_contains($categoryName, 'keranjang') => 'wicker,basket,palm,weaving',
                str_contains($categoryName, 'aksesoris') || str_contains($categoryName, 'perhiasan') => 'natural,jewelry,palm,handmade',
                str_contains($categoryName, 'dekorasi') => 'home,decor,natural,wicker',
                str_contains($categoryName, 'dapur') => 'kitchen,bamboo,natural,weaving',
                str_contains($categoryName, 'seni') || str_contains($categoryName, 'kerajinan') => 'craft,art,weaving,traditional',
                str_contains($categoryName, 'upacara') => 'traditional,ceremony,palm,cultural',
                default => 'palm,leaves,wicker,basket,handmade'
            };
        }

        return "https://source.unsplash.com/{$width}x{$height}/?" . urlencode($keywords);
    }

    /**
     * Get Lorem Picsum placeholder
     */
    public static function getLoremPicsumPlaceholder($seed = null, $width = 400, $height = 400)
    {
        $seedParam = $seed ? "seed/{$seed}/" : '';
        return "https://picsum.photos/{$seedParam}{$width}/{$height}";
    }

    /**
     * Check if product has real image
     */
    public static function hasRealImage($product)
    {
        return $product &&
            $product->foto &&
            Storage::disk('public')->exists($product->foto);
    }

    /**
     * Get image dimensions info
     */
    public static function getImageInfo($product)
    {
        if (!self::hasRealImage($product)) {
            return null;
        }

        $path = Storage::disk('public')->path($product->foto);
        if (file_exists($path)) {
            $info = getimagesize($path);
            return [
                'width' => $info[0] ?? null,
                'height' => $info[1] ?? null,
                'mime' => $info['mime'] ?? null,
                'size' => filesize($path),
            ];
        }

        return null;
    }

    /**
     * Generate responsive image srcset
     */
    public static function generateSrcSet($product)
    {
        if (!self::hasRealImage($product)) {
            return null;
        }

        $baseUrl = Storage::url($product->foto);

        // For now, return single image
        // This can be extended to generate multiple sizes
        return $baseUrl;
    }
}
