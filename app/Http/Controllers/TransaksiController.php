<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Traits\FileUploadTrait;

class TransaksiController extends Controller
{
    use FileUploadTrait;

    // ====== PRODUCT BROWSING ======

    /**
     * Display homepage with featured products.
     */
    public function index()
    {
        $produk = Produk::with(['kategori', 'user'])
            ->where('status', 'aktif')
            ->latest('tanggal_upload')
            ->take(12)
            ->get();

        $kategori = Kategori::withCount('produk')->get();

        return view('home', compact('produk', 'kategori'));
    }

    /**
     * Display all products with filtering options.
     */
    public function shop(Request $request)
    {
        $query = Produk::with(['kategori', 'user'])->where('status', 'aktif');

        // Filter by category
        if ($request->has('idKategori') && $request->idKategori != '') {
            $query->where('idKategori', $request->idKategori);
        }

        // Filter by price range
        if ($request->has('min_harga')) {
            $query->where('Harga', '>=', $request->min_harga);
        }
        if ($request->has('max_harga')) {
            $query->where('Harga', '<=', $request->max_harga);
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('Harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('Harga', 'desc');
                break;
            case 'name':
                $query->orderBy('nama_produk', 'asc');
                break;
            default:
                $query->latest('tanggal_upload');
        }

        $produk = $query->paginate(12);
        $kategori = Kategori::all();

        return view('shop', compact('produk', 'kategori'));
    }

    /**
     * Display single product details.
     */
    public function productDetail($idProduk)
    {
        $produk = Produk::with(['kategori', 'user', 'testimoni.user', 'promosi'])
            ->where('status', 'aktif')
            ->findOrFail($idProduk);

        // Get related products from same category
        $relatedProducts = Produk::with(['kategori', 'user'])
            ->where('idKategori', $produk->idKategori)
            ->where('idProduk', '!=', $idProduk)
            ->where('status', 'aktif')
            ->take(4)
            ->get();

        return view('product-detail', compact('produk', 'relatedProducts'));
    }

    // ====== CART MANAGEMENT ======

    /**
     * Add product to cart.
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'idProduk' => 'required|exists:produk,idProduk',
            'jumlah' => 'required|integer|min:1'
        ]);

        $produk = Produk::findOrFail($request->idProduk);

        // Check if product has sufficient stock
        if ($produk->stok < $request->jumlah) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $produk->stok . ' items available.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Insufficient stock. Only ' . $produk->stok . ' items available.');
        }

        // Get cart from session
        $cart = Session::get('cart', []);

        $idProduk = $request->idProduk;
        $jumlah = $request->jumlah;

        if (isset($cart[$idProduk])) {
            $newQuantity = $cart[$idProduk]['jumlah'] + $jumlah;
            if ($newQuantity > $produk->stok) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Maximum available: ' . $produk->stok
                    ], 400);
                }
                return redirect()->back()->with('error', 'Cannot add more items. Maximum available: ' . $produk->stok);
            }
            $cart[$idProduk]['jumlah'] = $newQuantity;
        } else {
            $cart[$idProduk] = [
                'nama_produk' => $produk->nama_produk,
                'Harga' => $produk->Harga,
                'jumlah' => $jumlah,
                'foto' => $produk->foto,
                'idProduk' => $idProduk
            ];
        }

        Session::put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Display cart contents.
     */
    public function viewCart()
    {
        $cart = Session::get('cart', []);
        $total_harga = 0;

        foreach ($cart as $item) {
            $total_harga += $item['Harga'] * $item['jumlah'];
        }

        return view('cart', compact('cart', 'total_harga'));
    }

    /**
     * Update cart item quantity.
     */
    public function updateCart(Request $request)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$request->idProduk])) {
            if ($request->jumlah > 0) {
                $cart[$request->idProduk]['jumlah'] = $request->jumlah;
            } else {
                unset($cart[$request->idProduk]);
            }
        }

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart($idProduk)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$idProduk])) {
            unset($cart[$idProduk]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Product removed from cart!');
    }

    /**
     * Get cart count for API.
     */
    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['jumlah'];
        }

        return response()->json(['count' => $count]);
    }

    // ====== CHECKOUT & ORDER MANAGEMENT ======

    /**
     * Display checkout page.
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout');
        }

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $total_harga = 0;
        foreach ($cart as $item) {
            $total_harga += $item['Harga'] * $item['jumlah'];
        }

        return view('checkout', compact('cart', 'total_harga'));
    }

    /**
     * Process order placement.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:500',
            'metode_pembayaran' => 'required|string|in:transfer_bank,cod,ewallet'
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $total_harga = 0;
            foreach ($cart as $item) {
                $total_harga += $item['Harga'] * $item['jumlah'];
            }

            // Create order
            $pesanan = Pesanan::create([
                'idUser' => Auth::id(),
                'tanggal_pesanan' => now(),
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'no_telepon' => $request->no_telepon,
                'catatan' => $request->catatan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pesanan' => 'waiting_payment',
                'total_harga' => $total_harga
            ]);

            // Create order details
            foreach ($cart as $idProduk => $item) {
                DetailPesanan::create([
                    'idPesanan' => $pesanan->idPesanan,
                    'idProduk' => $idProduk,
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $item['Harga'] * $item['jumlah']
                ]);
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('payment.upload', $pesanan->idPesanan)
                ->with('success', 'Order placed successfully! Please upload your payment proof.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Show payment proof upload form.
     */
    public function showPaymentUpload($idPesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk'])
            ->where('idPesanan', $idPesanan)
            ->where('idUser', Auth::id())
            ->where('status_pesanan', 'waiting_payment')
            ->firstOrFail();

        return view('payment-upload', compact('pesanan'));
    }

    /**
     * Handle payment proof upload.
     */
    public function uploadPaymentProof(Request $request, $idPesanan)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pesanan = Pesanan::where('idPesanan', $idPesanan)
            ->where('idUser', Auth::id())
            ->where('status_pesanan', 'waiting_payment')
            ->firstOrFail();

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old payment proof if exists
            if ($pesanan->bukti_pembayaran) {
                $this->deleteFile($pesanan->bukti_pembayaran);
            }

            // Upload new payment proof using trait method
            $filePath = $this->storePaymentProof($request->file('bukti_pembayaran'), $idPesanan);

            if ($filePath) {
                $pesanan->update([
                    'bukti_pembayaran' => $filePath,
                    'status_pesanan' => 'waiting_confirmation'
                ]);
            } else {
                return redirect()->back()->with('error', 'Invalid file format or size. Please upload a valid image (JPG, PNG) under 2MB.');
            }
        }

        return redirect()->route('order.success', $idPesanan)
            ->with('success', 'Payment proof uploaded successfully! Your order is now waiting for admin confirmation.');
    }

    /**
     * Display order success page.
     */
    public function orderSuccess($idPesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user'])
            ->where('idPesanan', $idPesanan)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('order-success', compact('pesanan'));
    }

    /**
     * Display user's order history.
     */
    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Pesanan::with(['detailPesanan.produk'])
            ->where('idUser', Auth::id())
            ->latest('tanggal_pesanan')
            ->paginate(10);

        return view('my-orders', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function orderDetail($idPesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user'])
            ->where('idPesanan', $idPesanan)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('order-detail', compact('pesanan'));
    }

    // ====== TESTIMONIAL MANAGEMENT ======

    /**
     * Store product testimonial.
     */
    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'idProduk' => 'required|exists:produk,idProduk',
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'required|string|min:10|max:1000'
        ], [
            'idProduk.required' => 'Product is required',
            'idProduk.exists' => 'Selected product does not exist',
            'rating.required' => 'Rating is required',
            'rating.numeric' => 'Rating must be a number',
            'rating.min' => 'Rating must be at least 1',
            'rating.max' => 'Rating cannot exceed 5',
            'komentar.required' => 'Review comment is required',
            'komentar.min' => 'Review must be at least 10 characters long',
            'komentar.max' => 'Review cannot exceed 1000 characters'
        ]);

        // Check if user has ordered this product
        $hasOrdered = DetailPesanan::whereHas('pesanan', function ($q) {
            $q->where('idUser', Auth::id())
                ->where('status_pesanan', 'completed');
        })->where('idProduk', $request->idProduk)->exists();

        if (!$hasOrdered) {
            return redirect()->back()->with('error', 'You can only review products you have purchased and completed.');
        }

        // Check if user already reviewed this product
        $existingReview = Testimoni::where('idUser', Auth::id())
            ->where('idProduk', $request->idProduk)
            ->exists();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        try {
            Testimoni::create([
                'idUser' => Auth::id(),
                'idProduk' => $request->idProduk,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'tanggal' => now()
            ]);

            return redirect()->back()->with('success', 'Thank you for your review! Your feedback helps other customers make informed decisions.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit review. Please try again.');
        }
    }
}
