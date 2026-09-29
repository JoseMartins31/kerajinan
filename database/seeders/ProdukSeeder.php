<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $categories = Kategori::all();

        $products = [
            // Keramik Products
            [
                'nama_produk' => 'Vas Keramik Motif Tradisional',
                'deskripsi' => 'Vas keramik handmade dengan motif tradisional Indonesia. Cocok untuk dekorasi ruang tamu dan kantor. Terbuat dari bahan keramik berkualitas tinggi dengan finishing glossy.',
                'Harga' => 125000,
                'stok' => 15,
                'status' => 'available',
                'kategori' => 'Keramik',
                'foto' => 'produk/vas_keramik_1.jpg'
            ],
            [
                'nama_produk' => 'Set Mangkuk Keramik Warna-Warni',
                'deskripsi' => 'Set 4 mangkuk keramik dengan warna-warni cerah. Aman untuk microwave dan dishwasher. Desain modern dengan sentuhan tradisional.',
                'Harga' => 89000,
                'stok' => 25,
                'status' => 'available',
                'kategori' => 'Keramik',
                'foto' => 'produk/mangkuk_keramik_1.jpg'
            ],

            // Tekstil Products
            [
                'nama_produk' => 'Batik Tulis Solo Premium',
                'deskripsi' => 'Kain batik tulis asli Solo dengan motif parang klasik. Menggunakan pewarna alami dan proses pembuatan tradisional. Cocok untuk acara formal dan kasual.',
                'Harga' => 450000,
                'stok' => 8,
                'status' => 'available',
                'kategori' => 'Tekstil',
                'foto' => 'produk/batik_solo_1.jpg'
            ],
            [
                'nama_produk' => 'Selendang Tenun Songket',
                'deskripsi' => 'Selendang tenun songket dengan benang emas. Dibuat dengan teknik tradisional Palembang. Cocok untuk kebaya dan pakaian adat.',
                'Harga' => 320000,
                'stok' => 12,
                'status' => 'available',
                'kategori' => 'Tekstil',
                'foto' => 'produk/songket_1.jpg'
            ],

            // Kayu Products
            [
                'nama_produk' => 'Ukiran Topeng Bali',
                'deskripsi' => 'Topeng kayu ukiran Bali dengan detail halus. Terbuat dari kayu pule berkualitas. Cocok untuk dekorasi dan koleksi seni.',
                'Harga' => 175000,
                'stok' => 20,
                'status' => 'available',
                'kategori' => 'Kayu',
                'foto' => 'produk/topeng_bali_1.jpg'
            ],
            [
                'nama_produk' => 'Miniatur Kapal Pinisi',
                'deskripsi' => 'Miniatur kapal Pinisi tradisional Sulawesi. Dibuat dengan detail tinggi dari kayu jati. Ukuran 30cm, cocok untuk pajangan kantor.',
                'Harga' => 280000,
                'stok' => 10,
                'status' => 'available',
                'kategori' => 'Kayu',
                'foto' => 'produk/pinisi_1.jpg'
            ],

            // Logam Products
            [
                'nama_produk' => 'Gelang Perak Motif Ukir',
                'deskripsi' => 'Gelang perak 925 dengan motif ukir tradisional Yogyakarta. Handmade dengan finishing berkualitas tinggi. Tersedia berbagai ukuran.',
                'Harga' => 195000,
                'stok' => 30,
                'status' => 'available',
                'kategori' => 'Logam',
                'foto' => 'produk/gelang_perak_1.jpg'
            ],
            [
                'nama_produk' => 'Bros Kuningan Motif Bunga',
                'deskripsi' => 'Bros kuningan dengan motif bunga tradisional. Cocok untuk pelengkap kebaya dan pakaian formal. Tahan lama dan anti karat.',
                'Harga' => 65000,
                'stok' => 45,
                'status' => 'available',
                'kategori' => 'Logam',
                'foto' => 'produk/bros_kuningan_1.jpg'
            ],

            // Bambu Products
            [
                'nama_produk' => 'Tas Bambu Anyam Premium',
                'deskripsi' => 'Tas tangan dari bambu anyam dengan handle kulit asli. Ramah lingkungan dan tahan lama. Cocok untuk gaya kasual dan pantai.',
                'Harga' => 145000,
                'stok' => 18,
                'status' => 'available',
                'kategori' => 'Bambu',
                'foto' => 'produk/tas_bambu_1.jpg'
            ],
            [
                'nama_produk' => 'Lampu Gantung Bambu',
                'deskripsi' => 'Lampu gantung dari bambu dengan desain modern minimalis. Cocok untuk ruang tamu dan cafe. Sudah termasuk fitting lampu.',
                'Harga' => 220000,
                'stok' => 12,
                'status' => 'available',
                'kategori' => 'Bambu',
                'foto' => 'produk/lampu_bambu_1.jpg'
            ],

            // Rotan Products
            [
                'nama_produk' => 'Keranjang Rotan Multifungsi',
                'deskripsi' => 'Keranjang rotan dengan tutup untuk penyimpanan. Dapat digunakan untuk laundry, mainan anak, atau dekorasi. Tahan lama dan fleksibel.',
                'Harga' => 98000,
                'stok' => 22,
                'status' => 'available',
                'kategori' => 'Rotan',
                'foto' => 'produk/keranjang_rotan_1.jpg'
            ],

            // Kulit Products
            [
                'nama_produk' => 'Dompet Kulit Asli Handmade',
                'deskripsi' => 'Dompet kulit sapi asli dengan jahitan tangan. Desain klasik dengan banyak slot kartu. Tahan lama dan semakin bagus seiring waktu.',
                'Harga' => 185000,
                'stok' => 35,
                'status' => 'available',
                'kategori' => 'Kulit',
                'foto' => 'produk/dompet_kulit_1.jpg'
            ],

            // Perhiasan Products
            [
                'nama_produk' => 'Kalung Batu Akik Merah Delima',
                'deskripsi' => 'Kalung dengan batu akik merah delima asli. Setting perak 925. Dipercaya membawa keberuntungan dan energi positif.',
                'Harga' => 350000,
                'stok' => 8,
                'status' => 'available',
                'kategori' => 'Perhiasan',
                'foto' => 'produk/kalung_akik_1.jpg'
            ],

            // Seni Lukis Products
            [
                'nama_produk' => 'Lukisan Pemandangan Bali',
                'deskripsi' => 'Lukisan cat minyak pemandangan sawah terasering Bali. Ukuran 40x60cm dengan frame kayu. Karya seniman lokal Ubud.',
                'Harga' => 525000,
                'stok' => 5,
                'status' => 'available',
                'kategori' => 'Seni Lukis',
                'foto' => 'produk/lukisan_bali_1.jpg'
            ],

            // Patung Products
            [
                'nama_produk' => 'Patung Buddha Meditasi',
                'deskripsi' => 'Patung Buddha dari batu alam dengan pose meditasi. Tinggi 25cm, cocok untuk taman atau ruang meditasi. Finishing halus dan detail.',
                'Harga' => 295000,
                'stok' => 15,
                'status' => 'available',
                'kategori' => 'Patung',
                'foto' => 'produk/patung_buddha_1.jpg'
            ]
        ];

        foreach ($products as $index => $productData) {
            $kategori = $categories->where('nama_kategori', $productData['kategori'])->first();
            $user = $users->random();

            Produk::create([
                'tanggal_upload' => Carbon::now()->subDays(rand(1, 90)),
                'nama_produk' => $productData['nama_produk'],
                'deskripsi' => $productData['deskripsi'],
                'Harga' => $productData['Harga'],
                'foto' => $productData['foto'],
                'stok' => $productData['stok'],
                'status' => $productData['status'],
                'idKategori' => $kategori->idKategori,
                'idUser' => $user->id
            ]);
        }
    }
}
