<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promosi;
use App\Models\Produk;
use Carbon\Carbon;

class PromosiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Produk::all();

        $promotions = [
            // Active Promotions - Current Running Promotions
            [
                'product_name' => 'Tas Anyaman Daun Lontar Premium',
                'nama_promosi' => 'Promo Tas Premium Januari',
                'diskon' => 15.0,
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_akhir' => Carbon::now()->addDays(10),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Keranjang Lontar Multifungsi Besar',
                'nama_promosi' => 'Diskon Keranjang Multifungsi',
                'diskon' => 20.0,
                'tanggal_mulai' => Carbon::now()->subDays(2),
                'tanggal_akhir' => Carbon::now()->addDays(8),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Kalung Serat Lontar Bohemian',
                'nama_promosi' => 'Flash Sale Kalung Bohemian',
                'diskon' => 25.0,
                'tanggal_mulai' => Carbon::now()->subDay(),
                'tanggal_akhir' => Carbon::now()->addDays(7),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Lampu Gantung Lontar Artistic',
                'nama_promosi' => 'Promo Lampu Dekoratif',
                'diskon' => 30.0,
                'tanggal_mulai' => Carbon::now()->subDays(3),
                'tanggal_akhir' => Carbon::now()->addDays(12),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Lukisan 3D Lontar Pemandangan',
                'nama_promosi' => 'Promo Seni Lukisan 3D',
                'diskon' => 12.0,
                'tanggal_mulai' => Carbon::now(),
                'tanggal_akhir' => Carbon::now()->addDays(14),
                'status' => 'aktif'
            ],

            // Scheduled Promotions - Future Promotions
            [
                'product_name' => 'Patung Burung Garuda Lontar',
                'nama_promosi' => 'Promo Patung Garuda Special',
                'diskon' => 18.0,
                'tanggal_mulai' => Carbon::now()->addDays(3),
                'tanggal_akhir' => Carbon::now()->addDays(17),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Payung Teduh Lontar Ceremonial',
                'nama_promosi' => 'Diskon Payung Ceremonial',
                'diskon' => 22.0,
                'tanggal_mulai' => Carbon::now()->addDays(5),
                'tanggal_akhir' => Carbon::now()->addDays(19),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Miniatur Rumah Adat Lontar',
                'nama_promosi' => 'Promo Miniatur Rumah Adat',
                'diskon' => 16.0,
                'tanggal_mulai' => Carbon::now()->addDays(7),
                'tanggal_akhir' => Carbon::now()->addDays(21),
                'status' => 'aktif'
            ],

            // Expired Promotions - Past Promotions
            [
                'product_name' => 'Set Tempat Makanan Lontar',
                'nama_promosi' => 'Promo Tahun Baru Set Makanan',
                'diskon' => 35.0,
                'tanggal_mulai' => Carbon::now()->subDays(25),
                'tanggal_akhir' => Carbon::now()->subDays(5),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Bros Lontar Motif Bunga',
                'nama_promosi' => 'Flash Sale Bros Bunga',
                'diskon' => 15.0,
                'tanggal_mulai' => Carbon::now()->subDays(15),
                'tanggal_akhir' => Carbon::now()->subDays(2),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Cermin Bingkai Lontar Vintage',
                'nama_promosi' => 'Promo Cermin Vintage Klasik',
                'diskon' => 28.0,
                'tanggal_mulai' => Carbon::now()->subDays(20),
                'tanggal_akhir' => Carbon::now()->subDays(3),
                'status' => 'aktif'
            ],

            // Inactive Promotions - Disabled Promotions
            [
                'product_name' => 'Tas Belanja Lontar Eco-Friendly',
                'nama_promosi' => 'Promo Tas Eco-Friendly (Non-Aktif)',
                'diskon' => 20.0,
                'tanggal_mulai' => Carbon::now()->addDays(2),
                'tanggal_akhir' => Carbon::now()->addDays(16),
                'status' => 'tidak_aktif'
            ],
            [
                'product_name' => 'Set Gelang Lontar Natural',
                'nama_promosi' => 'Diskon Gelang Natural (Ditangguhkan)',
                'diskon' => 40.0,
                'tanggal_mulai' => Carbon::now()->addDay(),
                'tanggal_akhir' => Carbon::now()->addDays(8),
                'status' => 'tidak_aktif'
            ],
            [
                'product_name' => 'Hiasan Dinding Lontar 3D',
                'nama_promosi' => 'Promo Hiasan Dinding (Pause)',
                'diskon' => 25.0,
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_akhir' => Carbon::now()->addDays(6),
                'status' => 'tidak_aktif'
            ],

            // Additional Special Promotions
            [
                'product_name' => 'Kipas Upacara Lontar Sacred',
                'nama_promosi' => 'Promo Kipas Sacred Premium',
                'diskon' => 10.0,
                'tanggal_mulai' => Carbon::now()->addDays(1),
                'tanggal_akhir' => Carbon::now()->addDays(15),
                'status' => 'aktif'
            ],
            [
                'product_name' => 'Wadah Dupa Lontar Spiritual',
                'nama_promosi' => 'Diskon Wadah Dupa Spiritual',
                'diskon' => 18.0,
                'tanggal_mulai' => Carbon::now()->addDays(4),
                'tanggal_akhir' => Carbon::now()->addDays(18),
                'status' => 'aktif'
            ]
        ];

        foreach ($promotions as $promoData) {
            $product = $products->where('nama_produk', $promoData['product_name'])->first();

            if ($product) {
                Promosi::create([
                    'idProduk' => $product->idProduk,
                    'tanggal_mulai' => $promoData['tanggal_mulai'],
                    'tanggal_akhir' => $promoData['tanggal_akhir'],
                    'diskon' => $promoData['diskon'],
                    'status' => $promoData['status']
                ]);
            }
        }
    }
}
