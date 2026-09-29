<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\User;

class PalmLeafProdukSeeder extends Seeder
{
    /**
     * Palm leaf handicraft products organized by category
     */
    private $products = [
        'Tas & Keranjang' => [
            [
                'nama' => 'Tas Anyaman Daun Lontar Premium',
                'deskripsi' => 'Tas anyaman dari daun lontar asli dengan desain modern dan elegan. Dilengkapi dengan tali kulit sintetis yang kuat. Cocok untuk acara formal maupun casual. Ramah lingkungan dan tahan lama.',
                'harga' => 185000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Keranjang Lontar Multifungsi Besar',
                'deskripsi' => 'Keranjang serbaguna dari anyaman daun lontar dengan ukuran besar. Ideal untuk menyimpan pakaian, mainan anak, atau sebagai keranjang laundry. Dilengkapi dengan tutup dan handle yang nyaman.',
                'harga' => 145000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Tas Belanja Lontar Eco-Friendly',
                'deskripsi' => 'Tas belanja ramah lingkungan dari anyaman daun lontar. Kuat dan tahan lama, dapat menggantikan tas plastik. Desain simpel dan praktis untuk berbelanja sehari-hari.',
                'harga' => 75000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Keranjang Buah Lontar Artistik',
                'deskripsi' => 'Keranjang buah dengan desain artistik dari anyaman daun lontar. Sempurna untuk menghias meja makan sambil menyimpan buah-buahan segar. Finishing natural yang indah.',
                'harga' => 95000,
                'status' => 'aktif',
            ]
        ],
        'Aksesoris & Perhiasan' => [
            [
                'nama' => 'Kalung Serat Lontar Bohemian',
                'deskripsi' => 'Kalung bohemian style dari serat daun lontar yang ditenun halus. Desain unik dan artistik yang cocok untuk gaya kasual dan etnik. Nyaman dipakai dan tidak menyebabkan alergi.',
                'harga' => 85000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Set Gelang Lontar Natural',
                'deskripsi' => 'Set 3 gelang dari serat daun lontar dengan berbagai pola anyaman. Dapat dipakai terpisah atau bersamaan. Aksesoris natural yang memberikan kesan earth-tone pada penampilan.',
                'harga' => 120000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Anting Lontar Etnik Tradisional',
                'deskripsi' => 'Anting dengan desain etnik tradisional dari anyaman halus daun lontar. Ringan dan nyaman dipakai. Cocok untuk melengkapi busana tradisional atau modern dengan sentuhan etnik.',
                'harga' => 65000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Bros Lontar Motif Bunga',
                'deskripsi' => 'Bros cantik dengan motif bunga dari anyaman daun lontar yang detail. Dapat digunakan di hijab, blazer, atau tas. Finishing rapi dengan warna natural yang elegan.',
                'harga' => 55000,
                'status' => 'aktif',
            ]
        ],
        'Dekorasi Rumah' => [
            [
                'nama' => 'Lampu Gantung Lontar Artistic',
                'deskripsi' => 'Lampu gantung dengan shade dari anyaman daun lontar yang artistik. Memberikan pencahayaan hangat dan shadow pattern yang indah. Cocok untuk ruang tamu, kamar tidur, atau area santai.',
                'harga' => 280000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Hiasan Dinding Lontar 3D',
                'deskripsi' => 'Hiasan dinding 3D dari anyaman daun lontar dengan motif geometris modern. Memberikan texture dan dimensi pada dinding. Mudah dipasang dengan sistem mounting yang disediakan.',
                'harga' => 165000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pot Tanaman Lontar Minimalis',
                'deskripsi' => 'Pot tanaman dengan desain minimalis dari anyaman daun lontar. Cocok untuk tanaman hias indoor. Dilengkapi dengan liner plastik anti bocor. Menambah suasana natural di ruangan.',
                'harga' => 125000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Cermin Bingkai Lontar Vintage',
                'deskripsi' => 'Cermin dengan bingkai dari anyaman daun lontar bergaya vintage. Diameter 40cm, cocok untuk kamar tidur, kamar mandi, atau hallway. Bingkai tebal dengan finishing natural.',
                'harga' => 195000,
                'status' => 'aktif',
            ]
        ],
        'Peralatan Dapur' => [
            [
                'nama' => 'Tudung Saji Lontar Traditional',
                'deskripsi' => 'Tudung saji tradisional dari anyaman daun lontar untuk melindungi makanan dari lalat dan debu. Desain klasik dengan konstruksi yang kokoh. Diameter 35cm, tinggi 20cm.',
                'harga' => 95000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Set Tempat Makanan Lontar',
                'deskripsi' => 'Set 3 tempat makanan berbagai ukuran dari anyaman daun lontar. Cocok untuk menyimpan snack, buah kering, atau camilan. Food grade dan mudah dibersihkan.',
                'harga' => 135000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Tempat Bumbu Lontar Rustic',
                'deskripsi' => 'Tempat bumbu dengan 6 sekat dari anyaman daun lontar bergaya rustic. Praktis untuk mengatur bumbu dapur. Dilengkapi dengan tutup yang rapat dan sendok kayu kecil.',
                'harga' => 155000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Alas Piring Lontar Set 6pcs',
                'deskripsi' => 'Set 6 alas piring (placemat) dari anyaman daun lontar. Melindungi meja dari panas dan goresan. Mudah dibersihkan dan tahan lama. Diameter 30cm dengan motif natural.',
                'harga' => 185000,
                'status' => 'aktif',
            ]
        ],
        'Seni & Kerajinan' => [
            [
                'nama' => 'Patung Burung Garuda Lontar',
                'deskripsi' => 'Patung miniatur burung Garuda dari anyaman daun lontar dengan detail yang halus. Simbol nasional Indonesia dalam bentuk kerajinan tradisional. Tinggi 25cm, cocok untuk pajangan.',
                'harga' => 320000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lukisan 3D Lontar Pemandangan',
                'deskripsi' => 'Lukisan 3D dari susunan anyaman daun lontar yang membentuk pemandangan alam Indonesia. Karya seni unik yang menggabungkan teknik tradisional dengan konsep modern. Frame kayu jati.',
                'harga' => 450000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Miniatur Rumah Adat Lontar',
                'deskripsi' => 'Miniatur rumah adat Indonesia dari anyaman daun lontar dengan detail arsitektur yang akurat. Cocok untuk pajangan, souvenir, atau media edukasi budaya. Ukuran 20x15x15cm.',
                'harga' => 285000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Mobile Art Lontar Kinetic',
                'deskripsi' => 'Mobile art kinetik dari anyaman daun lontar yang bergerak mengikuti angin. Seni interaktif yang indah untuk dekorasi ruangan. Dapat digantung di langit-langit atau teras.',
                'harga' => 225000,
                'status' => 'aktif',
            ]
        ],
        'Perlengkapan Upacara' => [
            [
                'nama' => 'Sesajen Lontar Set Lengkap',
                'deskripsi' => 'Set lengkap tempat sesajen dari anyaman daun lontar untuk upacara adat Hindu-Bali. Terdiri dari berbagai ukuran wadah sesuai kebutuhan upacara tradisional. Blessed by local priest.',
                'harga' => 380000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Kipas Upacara Lontar Sacred',
                'deskripsi' => 'Kipas upacara dari daun lontar dengan handle kayu ukir untuk ritual keagamaan. Dibuat dengan penuh penghayatan spiritual. Dilengkapi dengan sarung pelindung dari kain tradisional.',
                'harga' => 165000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Payung Teduh Lontar Ceremonial',
                'deskripsi' => 'Payung teduh ceremonial dari anyaman daun lontar untuk upacara adat outdoor. Diameter 1.5 meter dengan tiang bambu yang kokoh. Motif tradisional dengan makna filosofis.',
                'harga' => 520000,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Wadah Dupa Lontar Spiritual',
                'deskripsi' => 'Wadah dupa berbentuk lotus dari anyaman daun lontar untuk ritual spiritual. Desain yang memudahkan sirkulasi asap dupa. Diameter 15cm dengan detail yang halus dan indah.',
                'harga' => 95000,
                'status' => 'aktif',
            ]
        ]
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
                'username' => 'admin_palmleaf',
                'email' => 'admin@palmleafcraft.com',
                'role' => 'admin',
                'status' => 'active'
            ]);
        }

        $this->command->info('🌴 Creating palm leaf handicraft products...');

        foreach ($this->products as $kategoriNama => $products) {
            // Get category
            $kategori = Kategori::where('nama_kategori', $kategoriNama)->first();

            if (!$kategori) {
                $this->command->warn("⚠️  Category '{$kategoriNama}' not found, skipping...");
                continue;
            }

            $this->command->info("📁 Processing category: {$kategoriNama}");

            foreach ($products as $index => $productData) {
                // Create placeholder image path
                $placeholderName = $this->createPlaceholderImagePath($kategoriNama, $index + 1);

                // Create product
                $produk = Produk::create([
                    'nama_produk' => $productData['nama'],
                    'deskripsi' => $productData['deskripsi'],
                    'Harga' => $productData['harga'],
                    'status' => $productData['status'],
                    'idKategori' => $kategori->idKategori,
                    'idUser' => $admin->id,
                    'tanggal_upload' => now(),
                    'foto' => $placeholderName,
                ]);

                $this->command->line("✅ Created: {$productData['nama']} - Rp " . number_format($productData['harga'], 0, ',', '.'));
            }
        }

        $totalProducts = Produk::count();
        $this->command->info("🎉 Palm leaf products created successfully!");
        $this->command->line("📊 Total products created: {$totalProducts}");
        $this->command->line("");
        $this->command->comment("💡 Next steps:");
        $this->command->line("1. Your website now focuses exclusively on palm leaf handicrafts");
        $this->command->line("2. Upload real product images through admin panel");
        $this->command->line("3. Professional Unsplash placeholders will show until real images are uploaded");
    }

    /**
     * Create placeholder image path for product
     */
    private function createPlaceholderImagePath($category, $number): string
    {
        $categorySlug = str_replace(['&', ' '], ['', '_'], $category);
        return "produk/palmleaf_{$categorySlug}_{$number}.jpg";
    }
}
