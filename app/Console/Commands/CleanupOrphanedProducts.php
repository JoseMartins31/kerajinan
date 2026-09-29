<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:cleanup-orphaned
                          {--dry-run : Show what would be deleted without actually deleting}
                          {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up products that have NULL idUser (orphaned products)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        // Get products without idUser
        $orphanedProducts = Produk::whereNull('idUser')->get();
        $totalProducts = Produk::count();

        $this->info("🔍 Product Cleanup Analysis");
        $this->line("=========================");
        $this->line("Total products: {$totalProducts}");
        $this->line("Orphaned products (no idUser): {$orphanedProducts->count()}");

        if ($orphanedProducts->isEmpty()) {
            $this->info("✅ No orphaned products found. Database is clean!");
            return;
        }

        $this->line("");
        $this->warn("📋 Orphaned Products Found:");

        $headers = ['ID', 'Product Name', 'Category', 'Price', 'Status', 'Upload Date'];
        $rows = [];

        foreach ($orphanedProducts as $product) {
            $rows[] = [
                $product->idProduk,
                \Str::limit($product->nama_produk, 30),
                $product->kategori->nama_kategori ?? 'N/A',
                'Rp ' . number_format($product->Harga, 0, ',', '.'),
                $product->status,
                $product->tanggal_upload ? $product->tanggal_upload->format('Y-m-d H:i') : 'N/A'
            ];
        }

        $this->table($headers, $rows);

        if ($dryRun) {
            $this->comment("🔍 DRY RUN MODE - No products will be deleted");
            $this->info("These products would be deleted if you run without --dry-run");
            return;
        }

        // Confirmation prompt
        if (!$force) {
            if (!$this->confirm("⚠️  Are you sure you want to delete these {$orphanedProducts->count()} orphaned products?")) {
                $this->info("❌ Operation cancelled.");
                return;
            }
        }

        // Delete orphaned products
        $deletedCount = 0;
        $deletedImages = 0;

        foreach ($orphanedProducts as $product) {
            $productName = $product->nama_produk;
            $imageFile = $product->foto;

            // Delete associated image file if exists
            if ($imageFile && Storage::disk('public')->exists($imageFile)) {
                Storage::disk('public')->delete($imageFile);
                $deletedImages++;
                $this->line("🗑️  Deleted image: {$imageFile}");
            }

            // Delete the product
            $product->delete();
            $deletedCount++;
            $this->line("✅ Deleted product: {$productName}");
        }

        $this->line("");
        $this->info("🎉 Cleanup Complete!");
        $this->line("• Deleted products: {$deletedCount}");
        $this->line("• Deleted images: {$deletedImages}");
        $this->line("• Remaining products: " . (Produk::count()));

        $this->comment("💡 Database is now clean. All remaining products have valid users.");
    }
}
