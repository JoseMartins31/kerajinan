@props([
    'product' => null,
    'size' => 'medium', // small, medium, large
    'class' => '',
    'alt' => null,
    'showPlaceholder' => true,
    'placeholderType' => 'unsplash', // unsplash, picsum, gradient
])

@php
    use App\Helpers\ProductImageHelper;

    $imageUrl = null;
    $placeholderUrl = null;
    $productName = 'Product';
    $categoryClass = '';

    if ($product) {
        $productName = $product->nama_produk ?? 'Product';
        $alt = $alt ?? $productName;

        // Get category class for placeholder color
        if (isset($product->kategori)) {
            $categorySlug = Str::slug($product->kategori->nama_kategori ?? 'default');
            $categoryClass = 'category-' . $categorySlug;
        }

        // Check if product has real image
        if (ProductImageHelper::hasRealImage($product)) {
            $imageUrl = ProductImageHelper::getImageUrl($product, $size);
        } else {
            // Generate placeholder based on type
            $placeholderUrl = match ($placeholderType) {
                'unsplash' => ProductImageHelper::getUnsplashPlaceholder($product, 400, 400),
                'picsum' => ProductImageHelper::getLoremPicsumPlaceholder(
                    $product->idProduk ?? rand(1, 1000),
                    400,
                    400,
                ),
                default => null,
            };
        }
    }

    // Size classes
    $sizeClass = match ($size) {
        'small' => 'small',
        'large' => 'large',
        default => '',
    };

    // Dimensions based on size
    $dimensions = match ($size) {
        'small' => 'width: 100px; height: 100px;',
        'large' => 'width: 500px; height: 500px;',
        default => 'width: 300px; height: 300px;',
    };

    // Combine classes
    $imageClasses = trim("product-image {$class}");
    $placeholderClasses = trim("product-placeholder {$sizeClass} {$categoryClass}");
@endphp

@if ($imageUrl)
    {{-- Real product image --}}
    <img src="{{ $imageUrl }}" alt="{{ $alt }}" class="{{ $imageClasses }}" style="{{ $dimensions }}"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    @if ($showPlaceholder)
        <div class="{{ $placeholderClasses }}" style="display: none; {{ $dimensions }}">
            <div class="placeholder-content">
                <div class="placeholder-icon">
                    <i class="fas fa-image"></i>
                </div>
                <div class="placeholder-text">
                    {{ Str::limit($productName, 20) }}
                </div>
            </div>
        </div>
    @endif
@elseif($placeholderUrl)
    {{-- Online placeholder image --}}
    <img src="{{ $placeholderUrl }}" alt="{{ $alt }}" class="{{ $imageClasses }}" style="{{ $dimensions }}"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    @if ($showPlaceholder)
        <div class="{{ $placeholderClasses }}" style="display: none; {{ $dimensions }}">
            <div class="placeholder-content">
                <div class="placeholder-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="placeholder-text">
                    {{ Str::limit($productName, 20) }}
                </div>
            </div>
        </div>
    @endif
@elseif($showPlaceholder)
    {{-- CSS gradient placeholder --}}
    <div class="{{ $placeholderClasses }}" style="{{ $dimensions }}">
        <div class="placeholder-content">
            <div class="placeholder-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="placeholder-text">
                {{ Str::limit($productName, 20) }}
            </div>
        </div>
    </div>
@else
    {{-- Simple no image placeholder --}}
    <div class="product-image-missing {{ $class }}" style="{{ $dimensions }}">
        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
            <div class="text-center">
                <i class="fas fa-image fa-2x mb-2"></i>
                <div class="small">No Image</div>
            </div>
        </div>
    </div>
@endif
