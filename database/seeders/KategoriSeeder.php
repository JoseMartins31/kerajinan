<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Keramik',
                'deskripsi' => 'Produk kerajinan berbahan dasar keramik dan tanah liat, seperti vas, mangkuk, piring, dan dekorasi rumah.'
            ],
            [
                'nama_kategori' => 'Tekstil',
                'deskripsi' => 'Kerajinan tekstil tradisional meliputi batik, tenun, sulam, dan berbagai produk kain tradisional.'
            ],
            [
                'nama_kategori' => 'Kayu',
                'deskripsi' => 'Ukiran kayu, furniture, miniatur, dan berbagai kerajinan berbahan dasar kayu solid berkualitas.'
            ],
            [
                'nama_kategori' => 'Logam',
                'deskripsi' => 'Kerajinan logam seperti perhiasan, aksesoris, peralatan rumah tangga, dan dekorasi berbahan logam.'
            ],
            [
                'nama_kategori' => 'Bambu',
                'deskripsi' => 'Produk ramah lingkungan berbahan bambu seperti tas, wadah, furniture, dan dekorasi rumah.'
            ],
            [
                'nama_kategori' => 'Rotan',
                'deskripsi' => 'Kerajinan rotan berkualitas tinggi meliputi keranjang, furniture, dan aksesoris rumah.'
            ],
            [
                'nama_kategori' => 'Kulit',
                'deskripsi' => 'Produk kulit handmade seperti tas, sepatu, dompet, ikat pinggang, dan aksesoris kulit lainnya.'
            ],
            [
                'nama_kategori' => 'Perhiasan',
                'deskripsi' => 'Perhiasan tradisional dan modern berbahan perak, emas, batu mulia, dan bahan alami.'
            ],
            [
                'nama_kategori' => 'Seni Lukis',
                'deskripsi' => 'Karya seni lukis tradisional dan kontemporer, lukisan kain, canvas, dan media lainnya.'
            ],
            [
                'nama_kategori' => 'Patung',
                'deskripsi' => 'Seni patung dari berbagai bahan seperti kayu, batu, logam, dan bahan tradisional lainnya.'
            ]
        ];

        foreach ($categories as $category) {
            Kategori::create($category);
        }
    }
}
