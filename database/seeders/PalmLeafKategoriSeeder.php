<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class PalmLeafKategoriSeeder extends Seeder
{
    /**
     * Categories for palm leaf (Daun Lontar) handicrafts
     */
    private $categories = [
        [
            'nama_kategori' => 'Tas & Keranjang',
            'deskripsi' => 'Tas, keranjang, dan wadah anyaman dari daun lontar berkualitas tinggi. Cocok untuk berbagai keperluan sehari-hari dengan desain yang elegan dan ramah lingkungan.'
        ],
        [
            'nama_kategori' => 'Aksesoris & Perhiasan',
            'deskripsi' => 'Aksesoris fashion dan perhiasan unik dari serat daun lontar. Meliputi kalung, gelang, anting, dan aksesori pelengkap busana yang stylish.'
        ],
        [
            'nama_kategori' => 'Dekorasi Rumah',
            'deskripsi' => 'Hiasan dan dekorasi rumah dari anyaman daun lontar. Memberikan sentuhan alami dan artistik untuk mempercantik interior rumah Anda.'
        ],
        [
            'nama_kategori' => 'Peralatan Dapur',
            'deskripsi' => 'Peralatan dapur tradisional dari daun lontar seperti tudung saji, tempat makanan, dan perlengkapan makan yang alami dan sehat.'
        ],
        [
            'nama_kategori' => 'Seni & Kerajinan',
            'deskripsi' => 'Karya seni dan kerajinan artistik dari daun lontar. Cocok untuk koleksi, hadiah, atau untuk menunjang hobi seni dan kerajinan tangan.'
        ],
        [
            'nama_kategori' => 'Perlengkapan Upacara',
            'deskripsi' => 'Perlengkapan upacara adat dan keagamaan dari daun lontar. Produk tradisional yang mendukung kelestarian budaya Indonesia.'
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌴 Creating palm leaf handicraft categories...');

        foreach ($this->categories as $category) {
            Kategori::create($category);
            $this->command->line("✅ Created category: {$category['nama_kategori']}");
        }

        $this->command->info('🎉 Palm leaf categories created successfully!');
    }
}
