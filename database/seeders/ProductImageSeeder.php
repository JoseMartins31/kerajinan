<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ProductImageSeeder extends Seeder
{
    /**
     * Sample products with placeholder images for each category
     */
    private $sampleProducts = [
        'Keramik' => [
            [
                'nama' => 'Vas Keramik Tradisional',
                'deskripsi' => 'Vas keramik buatan tangan dengan motif tradisional Indonesia. Dibuat oleh pengrajin berpengalaman dengan teknik pembakaran tradisional.',
                'harga' => 125000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Set Piring Keramik Batik',
                'deskripsi' => 'Set 6 piring keramik dengan motif batik klasik. Cocok untuk acara formal maupun casual. Food safe dan dishwasher safe.',
                'harga' => 280000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Mangkuk Keramik Minimalis',
                'deskripsi' => 'Mangkuk keramik dengan desain minimalis modern. Tersedia dalam berbagai warna pastel yang menenangkan.',
                'harga' => 85000,
                'status' => 'aktif',
            ],
        ],
        'Tekstil' => [
            [
                'nama' => 'Kain Batik Tulis Premium',
                'deskripsi' => 'Kain batik tulis asli dari Solo dengan motif parang klasik. Menggunakan pewarna alami dan proses tradisional.',
                'harga' => 450000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Sarung Tenun Ikat NTT',
                'deskripsi' => 'Sarung tenun ikat tradisional dari Nusa Tenggara Timur dengan motif khas daerah. Benang katun berkualitas tinggi.',
                'harga' => 320000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Selendang Songket Palembang',
                'deskripsi' => 'Selendang songket asli Palembang dengan benang emas. Cocok untuk acara formal dan resmi.',
                'harga' => 680000,
                'status' => 'aktif',
            ],
        ],
        'Kayu' => [
            [
                'nama' => 'Ukiran Garuda Pancasila',
                'deskripsi' => 'Ukiran Garuda Pancasila dari kayu jati dengan detail halus. Simbol nasional yang cocok untuk hiasan ruang tamu.',
                'harga' => 850000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Set Peralatan Makan Kayu',
                'deskripsi' => 'Set sendok, garpu, dan pisau dari kayu sono keling. Ramah lingkungan dan aman untuk makanan.',
                'harga' => 95000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Kotak Perhiasan Jati Carved',
                'deskripsi' => 'Kotak perhiasan dari kayu jati dengan ukiran tradisional. Dilengkapi dengan sekat dan cermin di dalam.',
                'harga' => 380000,
                'status' => 'aktif',
            ],
        ],
        'Logam' => [
            [
                'nama' => 'Keris Pamor Beras Wutah',
                'deskripsi' => 'Keris pusaka dengan pamor beras wutah, bilah dari besi tua berkualitas. Untuk koleksi dan pusaka keluarga.',
                'harga' => 2500000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Gong Mini Kuningan',
                'deskripsi' => 'Gong mini dari kuningan murni dengan suara jernih. Cocok untuk terapi suara atau koleksi.',
                'harga' => 450000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lampion Tembaga Antik',
                'deskripsi' => 'Lampion dari tembaga dengan desain antik vintage. Memberikan cahaya hangat dan atmosfer klasik.',
                'harga' => 180000,
                'status' => 'aktif',
            ],
        ],
        'Bambu' => [
            [
                'nama' => 'Tas Anyaman Bambu Eco',
                'deskripsi' => 'Tas belanja dari anyaman bambu yang ramah lingkungan. Kuat, tahan lama, dan stylish untuk daily use.',
                'harga' => 75000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lampu Gantung Bambu Modern',
                'deskripsi' => 'Lampu gantung dari bambu dengan desain modern minimalis. Memberikan pencahayaan natural dan hangat.',
                'harga' => 220000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Set Peralatan Dapur Bambu',
                'deskripsi' => 'Set lengkap peralatan dapur dari bambu: spatula, sendok sayur, garpu, dan talenan. Antibakteri alami.',
                'harga' => 120000,
                'status' => 'aktif',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user (first admin or create one)
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'username' => 'admin_products',
                'email' => 'admin.products@kerajinan.com',
                'role' => 'admin',
                'status' => 'active'
            ]);
        }

        $this->command->info('🚀 Starting product seeder with placeholder images...');

        foreach ($this->sampleProducts as $kategoriNama => $products) {
            // Get or create category
            $kategori = Kategori::firstOrCreate(
                ['nama_kategori' => $kategoriNama],
                [
                    'deskripsi_kategori' => "Kategori untuk produk kerajinan $kategoriNama",
                    'status_kategori' => 'aktif'
                ]
            );

            $this->command->info("📁 Processing category: {$kategoriNama}");

            foreach ($products as $index => $productData) {
                // Check if product already exists
                $existingProduct = Produk::where('nama_produk', $productData['nama'])->first();
                if ($existingProduct) {
                    $this->command->info("⏭️  Skipping existing product: {$productData['nama']}");
                    continue;
                }

                // Create placeholder image file name based on product
                $placeholderName = $this->createPlaceholderImagePath($kategoriNama, $index + 1);

                // Create product with placeholder image path
                $produk = Produk::create([
                    'nama_produk' => $productData['nama'],
                    'deskripsi' => $productData['deskripsi'],
                    'Harga' => $productData['harga'],
                    'status' => $productData['status'],
                    'idKategori' => $kategori->idKategori,
                    'idUser' => $admin->id,
                    'tanggal_upload' => now(),
                    'foto' => $placeholderName, // Set placeholder path
                ]);

                $this->command->info("✅ Created product: {$productData['nama']} with placeholder image");
            }
        }

        $this->command->info('🎉 Product seeder completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Summary:');
        $this->command->info('- Products created with placeholder images');
        $this->command->info('- Professional placeholder system implemented');
        $this->command->info('- Ready for real product images to be uploaded');
        $this->command->info('');
        $this->command->info('💡 Next steps:');
        $this->command->info('1. Upload real product images through admin panel');
        $this->command->info('2. Images will automatically replace placeholders');
        $this->command->info('3. Placeholder system provides fallback for missing images');
    }

    /**
     * Create placeholder image path for product
     */
    private function createPlaceholderImagePath($category, $number): string
    {
        return "produk/placeholder_{$category}_{$number}.jpg";
    }
}
