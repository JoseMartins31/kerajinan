<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Promosi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * ProdukController - Main admin controller for product management
 *
 * This controller handles three main areas:
 * 1. Category management (CRUD operations)
 * 2. Product management (CRUD with image upload)
 * 3. Promotion management (time-based discounts with conflict detection)
 *
 * Key Features:
 * - Custom primary keys (idKategori, idProduk, idPromosi)
 * - Image upload handling for products
 * - Promotional conflict detection (no overlapping active promotions)
 * - AJAX endpoints for quick promotion operations
 * - Bulk operations for promotions
 *
 * Recent additions:
 * - Complete promotion management system (Jan 2026)
 * - Conflict detection for overlapping promotions
 * - AJAX quick-apply functionality
 * - Bulk promotion operations
 */
class ProdukController extends Controller
{
    // ====== KATEGORI CRUD OPERATIONS ======

    /**
     * Display a listing of all categories.
     */
    public function indexKategori()
    {
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function createKategori()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created category.
     */
    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string'
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dibuat');
    }

    /**
     * Display the specified category.
     */
    public function showKategori($id)
    {
        $kategori = Kategori::with('produk')->findOrFail($id);
        return view('admin.kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified category.
     */
    public function updateKategori(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified category.
     */
    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

    // ====== PRODUK CRUD OPERATIONS ======

    /**
     * Display a listing of all products.
     */
    public function index()
    {
        $produk = Produk::with(['kategori', 'user', 'promosi'])->get();
        return view('admin.produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.produk.create', compact('kategori'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'Harga' => 'required|numeric|min:0',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|string|max:30',
            'stok' => 'required|integer|min:0',
            'idKategori' => 'required|exists:kategori,idKategori',
            'idUser' => 'required|exists:users,id'
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('produk', $fotoName, 'public');
            $validated['foto'] = $fotoPath;
        }

        $validated['tanggal_upload'] = now();
        Produk::create($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dibuat');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $produk = Produk::with(['kategori', 'user', 'testimoni', 'promosi', 'detailPesanan'])->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'Harga' => 'required|numeric|min:0',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|string|max:30',
            'stok' => 'required|integer|min:0',
            'idKategori' => 'required|exists:kategori,idKategori',
            'idUser' => 'required|exists:users,id'
        ]);

        $produk = Produk::findOrFail($id);

        // Handle file upload if new photo is provided
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('produk', $fotoName, 'public');
            $validated['foto'] = $fotoPath;
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified product.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Delete associated photo
        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }

    // ====== ADDITIONAL HELPER METHODS ======

    /**
     * Get products by category.
     */
    public function getByCategory($categoryId)
    {
        $kategori = Kategori::findOrFail($categoryId);
        $produk = Produk::with(['kategori', 'user'])
            ->where('idKategori', $categoryId)
            ->get();

        return view('produk.by-category', compact('produk', 'kategori'));
    }

    /**
     * Search products by name.
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        $produk = Produk::with(['kategori', 'user'])
            ->where('nama_produk', 'LIKE', '%' . $search . '%')
            ->orWhere('deskripsi', 'LIKE', '%' . $search . '%')
            ->get();

        return view('produk.search', compact('produk', 'search'));
    }

    /**
     * API endpoint for live product search
     */
    public function apiSearchProducts(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $produk = Produk::with('kategori')
            ->where('status', 'aktif')
            ->where(function ($query) use ($search) {
                $query->where('nama_produk', 'LIKE', '%' . $search . '%')
                    ->orWhere('deskripsi', 'LIKE', '%' . $search . '%');
            })
            ->select('idProduk', 'nama_produk', 'deskripsi', 'Harga', 'foto', 'idKategori')
            ->limit(10)
            ->get();

        return response()->json($produk->map(function ($product) {
            return [
                'idProduk' => $product->idProduk,
                'nama_produk' => $product->nama_produk,
                'deskripsi' => Str::limit($product->deskripsi, 80),
                'Harga' => $product->Harga,
                'formatted_price' => 'Rp ' . number_format($product->Harga, 0, ',', '.'),
                'foto' => $product->foto ? Storage::url($product->foto) : null,
                'kategori' => $product->kategori->nama_kategori ?? 'Tanpa Kategori'
            ];
        }));
    }

    // ====== PROMOTION MANAGEMENT ======

    /**
     * Display all promotions.
     */
    public function indexPromosi()
    {
        $promosi = Promosi::with(['produk.kategori'])->get();
        return view('admin.promosi.index', compact('promosi'));
    }

    /**
     * Show form to create a new promotion.
     */
    public function createPromosi()
    {
        return view('admin.promosi.create');
    }

    /**
     * Store a new promotion.
     */
    public function storePromosi(Request $request)
    {
        $validated = $request->validate([
            'nama_promosi' => 'required|string|max:255',
            'idProduk' => 'required|exists:produk,idProduk',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after:tanggal_mulai',
            'tanggal_selesai' => 'sometimes|date|after:tanggal_mulai', // alias for tanggal_akhir
            'persentase_diskon' => 'required|numeric|min:0|max:100',
            'diskon' => 'sometimes|numeric|min:0|max:100', // alias for persentase_diskon
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        // Ensure we have end date (handle both column names)
        if (!isset($validated['tanggal_akhir']) && isset($validated['tanggal_selesai'])) {
            $validated['tanggal_akhir'] = $validated['tanggal_selesai'];
        }

        // Ensure we have discount percentage (handle both column names)
        if (!isset($validated['persentase_diskon']) && isset($validated['diskon'])) {
            $validated['persentase_diskon'] = $validated['diskon'];
        }

        // Check for conflicting promotions
        $existingPromotion = Promosi::where('idProduk', $validated['idProduk'])
            ->where('status', 'aktif')
            ->where(function ($query) use ($validated) {
                $endDate = $validated['tanggal_akhir'] ?? $validated['tanggal_selesai'];
                $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $endDate])
                    ->orWhereBetween('tanggal_akhir', [$validated['tanggal_mulai'], $endDate])
                    ->orWhere(function ($q) use ($validated, $endDate) {
                        $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                            ->where('tanggal_akhir', '>=', $endDate);
                    });
            })
            ->exists();

        if ($existingPromotion) {
            return back()->withErrors(['error' => 'Produk sudah memiliki promosi aktif pada periode tersebut.']);
        }

        Promosi::create($validated);

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promosi berhasil dibuat');
    }

    /**
     * Display promotion details.
     */
    public function showPromosi($id)
    {
        $promosi = Promosi::with(['produk.kategori', 'produk.testimoni'])->findOrFail($id);
        return view('admin.promosi.show', compact('promosi'));
    }

    /**
     * Show form to edit promotion.
     */
    public function editPromosi($id)
    {
        $promosi = Promosi::findOrFail($id);
        $produk = Produk::select('idProduk', 'nama_produk', 'Harga')->get();
        return view('admin.promosi.edit', compact('promosi', 'produk'));
    }

    /**
     * Update promotion.
     */
    public function updatePromosi(Request $request, $id)
    {
        $validated = $request->validate([
            'idProduk' => 'required|exists:produk,idProduk',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'diskon' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $promosi = Promosi::findOrFail($id);

        // Check for conflicting promotions (exclude current promotion)
        $existingPromotion = Promosi::where('idProduk', $validated['idProduk'])
            ->where('idPromosi', '!=', $id)
            ->where('status', 'aktif')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_berakhir']])
                    ->orWhereBetween('tanggal_berakhir', [$validated['tanggal_mulai'], $validated['tanggal_berakhir']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                            ->where('tanggal_berakhir', '>=', $validated['tanggal_berakhir']);
                    });
            })
            ->exists();

        if ($existingPromotion) {
            return back()->withErrors(['error' => 'Product already has an active promotion during this period.']);
        }

        $promosi->update($validated);

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promotion updated successfully');
    }

    /**
     * Delete promotion.
     */
    public function destroyPromosi($id)
    {
        $promosi = Promosi::findOrFail($id);
        $promosi->delete();

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promotion deleted successfully');
    }

    /**
     * Quick apply promotion to product.
     */
    public function quickApplyPromotion(Request $request, $productId)
    {
        $validated = $request->validate([
            'diskon' => 'required|numeric|min:0|max:100',
            'tanggal_berakhir' => 'required|date|after:today'
        ]);

        $produk = Produk::findOrFail($productId);

        // Check for existing active promotions
        $existingPromotion = Promosi::where('idProduk', $productId)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_berakhir', '>=', now())
            ->first();

        if ($existingPromotion) {
            return response()->json([
                'success' => false,
                'message' => 'Product already has an active promotion'
            ]);
        }

        Promosi::create([
            'idProduk' => $productId,
            'tanggal_mulai' => now(),
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'diskon' => $validated['diskon'],
            'status' => 'aktif'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promotion applied successfully'
        ]);
    }

    /**
     * Toggle promotion status.
     */
    public function togglePromotionStatus($id)
    {
        $promosi = Promosi::findOrFail($id);
        $promosi->status = $promosi->status === 'aktif' ? 'tidak_aktif' : 'aktif';
        $promosi->save();

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promotion status updated successfully');
    }

    /**
     * Get active promotions for a product (AJAX).
     */
    public function getProductPromotions($productId)
    {
        $promotions = Promosi::where('idProduk', $productId)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_berakhir', '>=', now())
            ->get();

        return response()->json($promotions);
    }

    /**
     * Bulk apply promotion to multiple products.
     */
    public function bulkApplyPromotion(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:produk,idProduk',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'diskon' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $successCount = 0;
        $errors = [];

        foreach ($validated['product_ids'] as $productId) {
            // Check for conflicting promotions
            $existingPromotion = Promosi::where('idProduk', $productId)
                ->where('status', 'aktif')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_berakhir']])
                        ->orWhereBetween('tanggal_berakhir', [$validated['tanggal_mulai'], $validated['tanggal_berakhir']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                                ->where('tanggal_berakhir', '>=', $validated['tanggal_berakhir']);
                        });
                })
                ->exists();

            if (!$existingPromotion) {
                Promosi::create([
                    'idProduk' => $productId,
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_berakhir' => $validated['tanggal_berakhir'],
                    'diskon' => $validated['diskon'],
                    'status' => $validated['status']
                ]);
                $successCount++;
            } else {
                $product = Produk::find($productId);
                $errors[] = "Product '{$product->nama_produk}' already has an active promotion";
            }
        }

        $message = "Successfully applied promotion to {$successCount} products";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', $errors);
        }

        return redirect()->route('admin.promosi.index')
            ->with('success', $message);
    }
}
