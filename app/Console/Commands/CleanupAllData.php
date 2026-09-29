<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Testimoni;
use App\Models\Promosi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanupAllData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:cleanup-all
                          {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up all data (products, categories, transactions) to prepare for palm leaf handicraft focus';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');

        $this->info("🧹 Complete Data Cleanup for Palm Leaf Handicrafts");
        $this->line("=================================================");

        // Show current data counts
        $this->showCurrentCounts();

        if (!$force) {
            $this->line("");
            $this->warn("⚠️  This will permanently delete ALL:");
            $this->line("• Products and their images");
            $this->line("• Categories");
            $this->line("• Orders and order details");
            $this->line("• Testimonials");
            $this->line("• Promotions");
            $this->line("");

            if (!$this->confirm("Are you sure you want to proceed with complete cleanup?")) {
                $this->info("❌ Operation cancelled.");
                return;
            }
        }

        $this->line("");
        $this->info("🚀 Starting cleanup process...");

        // Start transaction for data integrity
        DB::beginTransaction();

        try {
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Clean up testimonials first (has foreign keys to products)
            $this->cleanupTestimonials();

            // 2. Clean up promotions (has foreign keys to products)
            $this->cleanupPromotions();

            // 3. Clean up order details (has foreign keys to products and orders)
            $this->cleanupOrderDetails();

            // 4. Clean up orders/transactions
            $this->cleanupOrders();

            // 5. Clean up products and their images
            $this->cleanupProducts();

            // 6. Clean up categories
            $this->cleanupCategories();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();

            $this->line("");
            $this->info("🎉 Cleanup Complete!");
            $this->showFinalCounts();

            $this->line("");
            $this->comment("💡 Next steps:");
            $this->line("1. Run: php artisan db:seed --class=PalmLeafSeeder");
            $this->line("2. Your website is now ready for palm leaf handicrafts!");
        } catch (\Exception $e) {
            // Re-enable foreign key checks even on failure
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::rollBack();
            $this->error("❌ Cleanup failed: " . $e->getMessage());
            return 1;
        }
    }

    private function showCurrentCounts()
    {
        $this->line("📊 Current Data:");
        $this->line("• Products: " . Produk::count());
        $this->line("• Categories: " . Kategori::count());
        $this->line("• Orders: " . Pesanan::count());
        $this->line("• Order Details: " . DetailPesanan::count());
        $this->line("• Testimonials: " . Testimoni::count());
        $this->line("• Promotions: " . Promosi::count());
    }

    private function showFinalCounts()
    {
        $this->line("📊 After Cleanup:");
        $this->line("• Products: " . Produk::count());
        $this->line("• Categories: " . Kategori::count());
        $this->line("• Orders: " . Pesanan::count());
        $this->line("• Order Details: " . DetailPesanan::count());
        $this->line("• Testimonials: " . Testimoni::count());
        $this->line("• Promotions: " . Promosi::count());
    }

    private function cleanupTestimonials()
    {
        $count = Testimoni::count();
        if ($count > 0) {
            Testimoni::truncate();
            $this->line("✅ Cleaned up {$count} testimonials");
        }
    }

    private function cleanupPromotions()
    {
        $count = Promosi::count();
        if ($count > 0) {
            Promosi::truncate();
            $this->line("✅ Cleaned up {$count} promotions");
        }
    }

    private function cleanupOrderDetails()
    {
        $count = DetailPesanan::count();
        if ($count > 0) {
            DetailPesanan::truncate();
            $this->line("✅ Cleaned up {$count} order details");
        }
    }

    private function cleanupOrders()
    {
        $orders = Pesanan::all();
        $deletedImages = 0;

        foreach ($orders as $order) {
            // Delete payment proof if exists
            if ($order->bukti_pembayaran && Storage::disk('public')->exists($order->bukti_pembayaran)) {
                Storage::disk('public')->delete($order->bukti_pembayaran);
                $deletedImages++;
            }
        }

        $count = Pesanan::count();
        if ($count > 0) {
            Pesanan::truncate();
            $this->line("✅ Cleaned up {$count} orders and {$deletedImages} payment proof images");
        }
    }

    private function cleanupProducts()
    {
        $products = Produk::all();
        $deletedImages = 0;

        foreach ($products as $product) {
            // Delete product image if exists
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
                $deletedImages++;
            }
        }

        $count = Produk::count();
        if ($count > 0) {
            Produk::truncate();
            $this->line("✅ Cleaned up {$count} products and {$deletedImages} product images");
        }
    }

    private function cleanupCategories()
    {
        $count = Kategori::count();
        if ($count > 0) {
            Kategori::truncate();
            $this->line("✅ Cleaned up {$count} categories");
        }
    }
}
