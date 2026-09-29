<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PalmLeafSeeder extends Seeder
{
    /**
     * Run the database seeds for Palm Leaf Handicraft website.
     */
    public function run(): void
    {
        $this->command->info('🌴 Starting Palm Leaf Handicraft Database Seeding...');
        $this->command->line('==================================================');

        // Seed categories first
        $this->call(PalmLeafKategoriSeeder::class);

        $this->command->line('');

        // Then seed products
        $this->call(PalmLeafProdukSeeder::class);

        $this->command->line('');
        $this->command->info('🎉 Palm Leaf Handicraft Database Seeding Complete!');
        $this->command->line('==================================================');
        $this->command->line('');
        $this->command->comment('🌿 Your website is now ready for Palm Leaf (Daun Lontar) Handicrafts:');
        $this->command->line('• 6 specialized categories created');
        $this->command->line('• 24 authentic palm leaf products added');
        $this->command->line('• Professional placeholder system ready');
        $this->command->line('• Admin account configured');
        $this->command->line('');
        $this->command->info('🚀 Visit your website to see the new palm leaf handicraft collection!');
    }
}
