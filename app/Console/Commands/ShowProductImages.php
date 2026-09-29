<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;
use App\Helpers\ProductImageHelper;

class ShowProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:show-images
                          {--limit=10 : Number of products to show}
                          {--category= : Filter by category name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show products and their image status (real images vs placeholders)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $category = $this->option('category');

        $query = Produk::with(['kategori']);

        if ($category) {
            $query->whereHas('kategori', function ($q) use ($category) {
                $q->where('nama_kategori', 'like', "%{$category}%");
            });
        }

        $products = $query->take($limit)->get();

        if ($products->isEmpty()) {
            $this->warn('No products found.');
            return;
        }

        $this->info("🛍️  Product Image Status Report");
        $this->line("=====================================");

        $realImages = 0;
        $placeholders = 0;

        $headers = ['ID', 'Product Name', 'Category', 'Price', 'Image Status', 'Image Path'];
        $rows = [];

        foreach ($products as $product) {
            $hasRealImage = ProductImageHelper::hasRealImage($product);
            $imageStatus = $hasRealImage ? '✅ Real Image' : '🎨 Placeholder';

            if ($hasRealImage) {
                $realImages++;
            } else {
                $placeholders++;
            }

            $rows[] = [
                $product->idProduk,
                \Str::limit($product->nama_produk, 30),
                $product->kategori->nama_kategori ?? 'N/A',
                'Rp ' . number_format($product->Harga, 0, ',', '.'),
                $imageStatus,
                $product->foto ?? 'None'
            ];
        }

        $this->table($headers, $rows);

        $this->line("");
        $this->info("📊 Summary:");
        $this->line("• Total products shown: " . $products->count());
        $this->line("• Products with real images: {$realImages}");
        $this->line("• Products with placeholders: {$placeholders}");

        $this->line("");
        $this->info("🌐 Available placeholder sources:");
        $this->line("• Unsplash (professional photos): https://source.unsplash.com/400x400/?craft");
        $this->line("• Lorem Picsum (random images): https://picsum.photos/400/400");
        $this->line("• CSS gradients (beautiful fallbacks)");

        if ($placeholders > 0) {
            $this->line("");
            $this->comment("💡 To add real images:");
            $this->line("1. Go to Admin Panel → Produk");
            $this->line("2. Edit any product");
            $this->line("3. Upload a real image");
            $this->line("4. The placeholder will be automatically replaced!");
        }
    }
}
