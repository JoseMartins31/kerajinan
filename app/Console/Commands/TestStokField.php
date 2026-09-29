<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;

class TestStokField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:stok-field';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if stok field is working correctly in product CRUD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Stok Field Functionality');
        $this->line('==============================');

        $product = Produk::first();

        if (!$product) {
            $this->error('No products found in database');
            return;
        }

        $this->info("Testing with product: {$product->nama_produk}");
        $this->line("Current stok: " . ($product->stok ?? 'NULL'));

        // Test updating stok
        $newStok = 25;
        $product->update(['stok' => $newStok]);
        $updated = $product->fresh();

        $this->line("Updated stok to: {$updated->stok}");

        if ($updated->stok == $newStok) {
            $this->info("✅ Stok field is working correctly!");
            $this->line("✅ Model fillable includes stok");
            $this->line("✅ Database update successful");
        } else {
            $this->error("❌ Stok field update failed");
        }

        $this->line("");
        $this->comment("💡 Now test in the admin panel:");
        $this->line("1. Go to Admin → Produk → Edit any product");
        $this->line("2. Change the stok value");
        $this->line("3. Save - the stok should now be tracked!");
    }
}
