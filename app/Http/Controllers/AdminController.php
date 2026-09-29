<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Produk;
use App\Models\Promosi;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Notifications\OrderStatusChanged;
use App\Notifications\PaymentConfirmed;
use App\Notifications\PaymentRejected;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * AdminController - Backend administration interface
 *
 * Handles all admin-specific operations including:
 * - Admin dashboard with statistics
 * - Order management with status updates
 * - Payment verification and confirmation
 * - User account management
 * - Sales reporting
 *
 * Key Features:
 * - Notification system integration (sends notifications on order/payment updates)
 * - Shipping tracking with nomor_resi field
 * - Payment proof verification
 * - Role-based access control
 *
 * Notification Integration:
 * - OrderStatusChanged: Sent when order status is updated
 * - PaymentConfirmed: Sent when payment is approved
 * - PaymentRejected: Sent when payment is rejected
 */
class AdminController extends Controller
{
    // ====== ADMIN DASHBOARD ======

    /**
     * Display admin dashboard with comprehensive statistics.
     */
    public function dashboard()
    {
        // Basic Statistics
        $totalOrders = Pesanan::count();
        $totalRevenue = Pesanan::whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])->sum('total_harga');
        $totalProducts = Produk::count();
        $pendingOrders = Pesanan::whereIn('status_pesanan', ['waiting_payment', 'waiting_confirmation'])->count();

        // Recent Orders
        $recentOrders = Pesanan::with(['user'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // Low Stock Products (less than 10 items)
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->where('status', 'available')
            ->orderBy('stok', 'asc')
            ->take(10)
            ->get();

        // Active Promotions
        $activePromotions = Promosi::with('produk')
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_akhir', '>=', Carbon::now())
            ->take(10)
            ->get();

        // Top Selling Products (based on order details)
        $topProducts = Produk::with(['kategori', 'detailPesanan'])
            ->select(
                'produk.*',
                DB::raw('(SELECT COUNT(*) FROM detailpesanan WHERE detailpesanan.idProduk = produk.idProduk) as total_sales')
            )
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Chart Data for Sales Overview (last 30 days)
        $chartLabels = [];
        $chartData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');

            // Include more statuses that represent completed sales
            $dailySales = Pesanan::whereDate('tanggal_pesanan', $date)
                ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
                ->sum('total_harga');
            $chartData[] = $dailySales;
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'pendingOrders',
            'recentOrders',
            'lowStockProducts',
            'activePromotions',
            'topProducts',
            'chartLabels',
            'chartData'
        ));
    }

    // ====== ORDER MANAGEMENT ======

    /**
     * Display all orders.
     */
    public function indexOrders()
    {
        $orders = Pesanan::with(['user', 'detailPesanan.produk'])
            ->latest('tanggal_pesanan')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function showOrder($idPesanan)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.produk'])
            ->findOrFail($idPesanan);

        return view('admin.orders.show', compact('pesanan'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, $idPesanan)
    {
        $request->validate([
            'status_pesanan' => 'required|in:waiting_payment,waiting_confirmation,confirmed,processing,shipped,delivered,completed,cancelled,payment_rejected',
            'nomor_resi' => 'nullable|string|max:100'
        ]);

        $pesanan = Pesanan::with('user')->findOrFail($idPesanan);
        $oldStatus = $pesanan->status_pesanan;
        $newStatus = $request->status_pesanan;

        // Prepare update data
        $updateData = ['status_pesanan' => $newStatus];

        // Add nomor_resi if provided
        if ($request->has('nomor_resi')) {
            $updateData['nomor_resi'] = $request->nomor_resi;
        }

        // Update the order
        $pesanan->update($updateData);

        // Send notification to user if status actually changed
        if ($oldStatus !== $newStatus && $pesanan->user) {
            $pesanan->user->notify(new OrderStatusChanged($pesanan, $oldStatus, $newStatus));
        }

        return redirect()->route('admin.orders.show', $idPesanan)
            ->with('success', 'Order status updated successfully');
    }

    // ====== PAYMENT CONFIRMATION MANAGEMENT ======

    /**
     * Display orders waiting for payment confirmation.
     */
    public function pendingPayments()
    {
        $orders = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('status_pesanan', 'waiting_confirmation')
            ->latest('tanggal_pesanan')
            ->paginate(20);

        return view('admin.payments.pending', compact('orders'));
    }

    /**
     * Show payment proof for confirmation.
     */
    public function showPaymentProof($idPesanan)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('status_pesanan', 'waiting_confirmation')
            ->findOrFail($idPesanan);

        return view('admin.payments.show', compact('pesanan'));
    }

    /**
     * Confirm payment and update order status.
     */
    public function confirmPayment(Request $request, $idPesanan)
    {
        $request->validate([
            'action' => 'required|in:confirm,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $pesanan = Pesanan::with('user')
            ->where('status_pesanan', 'waiting_confirmation')
            ->findOrFail($idPesanan);

        if ($request->action === 'confirm') {
            $pesanan->update([
                'status_pesanan' => 'confirmed',
                'admin_notes' => $request->admin_notes
            ]);

            // Send payment confirmed notification
            if ($pesanan->user) {
                $pesanan->user->notify(new PaymentConfirmed($pesanan, $request->admin_notes));
            }

            $message = 'Payment confirmed successfully';
        } else {
            $pesanan->update([
                'status_pesanan' => 'payment_rejected',
                'admin_notes' => $request->admin_notes
            ]);

            // Send payment rejected notification
            if ($pesanan->user) {
                $pesanan->user->notify(new PaymentRejected($pesanan, $request->admin_notes));
            }

            $message = 'Payment rejected';
        }

        return redirect()->route('admin.payments.pending')
            ->with('success', $message);
    }

    // ====== USER MANAGEMENT ======

    /**
     * Display all users.
     */
    public function indexUsers()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display specific user details.
     */
    public function showUser($id)
    {
        $user = User::with(['pesanan.detailPesanan.produk'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user status.
     */
    public function updateUserStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return redirect()->route('admin.users.show', $id)
            ->with('success', 'User status updated successfully');
    }

    // ====== REPORTS & ANALYTICS ======

    /**
     * Display sales reports with comprehensive analytics.
     */
    public function salesReport(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $categoryId = $request->get('category_id', 'all');
        $exportFormat = $request->get('export');

        // Base query for completed orders
        $baseQuery = Pesanan::query()
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Apply status filter
        if ($status !== 'all') {
            $baseQuery->where('status_pesanan', $status);
        }

        // Apply category filter
        if ($categoryId !== 'all') {
            $baseQuery->whereHas('detailPesanan.produk', function ($q) use ($categoryId) {
                $q->where('idKategori', $categoryId);
            });
        }

        // Clone query for different calculations
        $ordersQuery = clone $baseQuery;
        $revenueQuery = clone $baseQuery;
        $statsQuery = clone $baseQuery;

        // Get main data
        $orders = $ordersQuery->with(['user', 'detailPesanan.produk.kategori'])
            ->latest('tanggal_pesanan')
            ->paginate(20)
            ->appends($request->query());

        // Calculate statistics
        $totalRevenue = $revenueQuery->sum('total_harga');
        $totalOrders = $statsQuery->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Get top products
        $topProducts = $this->getTopProducts($startDate, $endDate, $categoryId, 10);

        // Get sales by category
        $categoryStats = $this->getCategoryStats($startDate, $endDate);

        // Get daily/weekly/monthly data based on period
        $chartData = $this->getChartData($period, $startDate, $endDate, $status, $categoryId);

        // Get payment method statistics
        $paymentStats = $this->getPaymentMethodStats($startDate, $endDate);

        // Get customer statistics
        $customerStats = $this->getCustomerStats($startDate, $endDate);

        // Get all categories for filter
        $categories = \App\Models\Kategori::orderBy('nama_kategori')->get();

        // Comparison with previous period
        $previousPeriodStats = $this->getPreviousPeriodComparison($startDate, $endDate, $status, $categoryId);

        $data = compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'topProducts',
            'categoryStats',
            'chartData',
            'paymentStats',
            'customerStats',
            'categories',
            'previousPeriodStats',
            'reportType',
            'period',
            'startDate',
            'endDate',
            'status',
            'categoryId'
        );

        // Handle export requests
        if ($exportFormat) {
            return $this->exportSalesReport($exportFormat, $data, $request);
        }

        return view('admin.reports.sales', $data);
    }

    /**
     * Get top selling products for the report period.
     */
    private function getTopProducts($startDate, $endDate, $categoryId = 'all', $limit = 10)
    {
        $query = \App\Models\Produk::select(
            'produk.idProduk',
            'produk.nama_produk',
            'produk.foto',
            'produk.Harga',
            'produk.idKategori',
            DB::raw('SUM(detailpesanan.jumlah) as total_sold'),
            DB::raw('SUM(detailpesanan.sub_total) as total_revenue')
        )
            ->join('detailpesanan', 'produk.idProduk', '=', 'detailpesanan.idProduk')
            ->join('pesanan', 'detailpesanan.idPesanan', '=', 'pesanan.idPesanan')
            ->whereIn('pesanan.status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('pesanan.tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with('kategori')
            ->groupBy('produk.idProduk', 'produk.nama_produk', 'produk.foto', 'produk.Harga', 'produk.idKategori')
            ->orderBy('total_sold', 'desc');

        if ($categoryId !== 'all') {
            $query->where('produk.idKategori', $categoryId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get sales statistics by category.
     */
    private function getCategoryStats($startDate, $endDate)
    {
        return \App\Models\Kategori::select(
            'kategori.idKategori',
            'kategori.nama_kategori',
            DB::raw('COUNT(DISTINCT pesanan.idPesanan) as total_orders'),
            DB::raw('SUM(detailpesanan.jumlah) as total_items'),
            DB::raw('SUM(pesanan.total_harga) as total_revenue')
        )
            ->leftJoin('produk', 'kategori.idKategori', '=', 'produk.idKategori')
            ->leftJoin('detailpesanan', 'produk.idProduk', '=', 'detailpesanan.idProduk')
            ->leftJoin('pesanan', 'detailpesanan.idPesanan', '=', 'pesanan.idPesanan')
            ->whereIn('pesanan.status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('pesanan.tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('kategori.idKategori', 'kategori.nama_kategori')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Get chart data based on selected period.
     */
    private function getChartData($period, $startDate, $endDate, $status, $categoryId)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $data = [];

        switch ($period) {
            case 'day':
                while ($start->lte($end)) {
                    $dayData = $this->getDailySales($start->format('Y-m-d'), $status, $categoryId);
                    $data[] = [
                        'label' => $start->format('M d'),
                        'date' => $start->format('Y-m-d'),
                        'revenue' => $dayData['revenue'],
                        'orders' => $dayData['orders']
                    ];
                    $start->addDay();
                }
                break;

            case 'week':
                $start->startOfWeek();
                while ($start->lte($end)) {
                    $weekEnd = $start->copy()->endOfWeek();
                    if ($weekEnd->gt($end)) $weekEnd = $end;

                    $weekData = $this->getPeriodSales($start->format('Y-m-d'), $weekEnd->format('Y-m-d'), $status, $categoryId);
                    $data[] = [
                        'label' => $start->format('M d') . ' - ' . $weekEnd->format('M d'),
                        'date' => $start->format('Y-m-d'),
                        'revenue' => $weekData['revenue'],
                        'orders' => $weekData['orders']
                    ];
                    $start->addWeek();
                }
                break;

            case 'month':
                $start->startOfMonth();
                while ($start->lte($end)) {
                    $monthEnd = $start->copy()->endOfMonth();
                    if ($monthEnd->gt($end)) $monthEnd = $end;

                    $monthData = $this->getPeriodSales($start->format('Y-m-d'), $monthEnd->format('Y-m-d'), $status, $categoryId);
                    $data[] = [
                        'label' => $start->format('M Y'),
                        'date' => $start->format('Y-m-d'),
                        'revenue' => $monthData['revenue'],
                        'orders' => $monthData['orders']
                    ];
                    $start->addMonth();
                }
                break;
        }

        return $data;
    }

    /**
     * Get daily sales data.
     */
    private function getDailySales($date, $status, $categoryId)
    {
        $query = Pesanan::whereDate('tanggal_pesanan', $date)
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed']);

        if ($status !== 'all') {
            $query->where('status_pesanan', $status);
        }

        if ($categoryId !== 'all') {
            $query->whereHas('detailPesanan.produk', function ($q) use ($categoryId) {
                $q->where('idKategori', $categoryId);
            });
        }

        return [
            'revenue' => $query->sum('total_harga'),
            'orders' => $query->count()
        ];
    }

    /**
     * Get sales data for a specific period.
     */
    private function getPeriodSales($startDate, $endDate, $status, $categoryId)
    {
        $query = Pesanan::whereBetween('tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed']);

        if ($status !== 'all') {
            $query->where('status_pesanan', $status);
        }

        if ($categoryId !== 'all') {
            $query->whereHas('detailPesanan.produk', function ($q) use ($categoryId) {
                $q->where('idKategori', $categoryId);
            });
        }

        return [
            'revenue' => $query->sum('total_harga'),
            'orders' => $query->count()
        ];
    }

    /**
     * Get payment method statistics.
     */
    private function getPaymentMethodStats($startDate, $endDate)
    {
        // Since we only have transfer bank, return basic stats
        $total = Pesanan::whereBetween('tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->count();

        return [
            ['method' => 'Transfer Bank', 'count' => $total, 'percentage' => 100]
        ];
    }

    /**
     * Get customer statistics.
     */
    private function getCustomerStats($startDate, $endDate)
    {
        $totalCustomers = Pesanan::whereBetween('tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->distinct('idUser')
            ->count('idUser');

        $newCustomers = \App\Models\User::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $returningCustomers = Pesanan::select('idUser', DB::raw('COUNT(*) as order_count'))
            ->whereBetween('tanggal_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status_pesanan', ['confirmed', 'shipped', 'delivered', 'completed'])
            ->groupBy('idUser')
            ->having('order_count', '>', 1)
            ->count();

        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'retention_rate' => $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0
        ];
    }

    /**
     * Get previous period comparison data.
     */
    private function getPreviousPeriodComparison($startDate, $endDate, $status, $categoryId)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end) + 1;

        $prevStart = $start->copy()->subDays($daysDiff);
        $prevEnd = $end->copy()->subDays($daysDiff);

        $currentPeriod = $this->getPeriodSales($startDate, $endDate, $status, $categoryId);
        $previousPeriod = $this->getPeriodSales($prevStart->format('Y-m-d'), $prevEnd->format('Y-m-d'), $status, $categoryId);

        $revenueChange = 0;
        $ordersChange = 0;

        if ($previousPeriod['revenue'] > 0) {
            $revenueChange = (($currentPeriod['revenue'] - $previousPeriod['revenue']) / $previousPeriod['revenue']) * 100;
        }

        if ($previousPeriod['orders'] > 0) {
            $ordersChange = (($currentPeriod['orders'] - $previousPeriod['orders']) / $previousPeriod['orders']) * 100;
        }

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'revenue_change' => $revenueChange,
            'orders_change' => $ordersChange
        ];
    }

    /**
     * Export sales report in various formats.
     */
    private function exportSalesReport($format, $data, $request)
    {
        $filename = 'sales_report_' . $data['startDate'] . '_to_' . $data['endDate'];

        switch ($format) {
            case 'excel':
                return $this->exportToExcel($data, $filename);
            case 'pdf':
                return $this->exportToPDF($data, $filename);
            case 'csv':
                return $this->exportToCSV($data, $filename);
            default:
                return redirect()->back()->with('error', 'Format export tidak didukung.');
        }
    }

    /**
     * Export to Excel format.
     */
    private function exportToExcel($data, $filename)
    {
        $title = 'Laporan Penjualan ' . $data['startDate'] . ' - ' . $data['endDate'];

        return Excel::download(
            new SalesReportExport($data, $title),
            $filename . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    /**
     * Export to PDF format.
     */
    private function exportToPDF($data, $filename)
    {
        // Get category name for display if filtering by category
        $categoryName = 'Semua Kategori';
        if ($data['categoryId'] !== 'all') {
            $category = \App\Models\Kategori::find($data['categoryId']);
            $categoryName = $category ? $category->nama_kategori : 'Kategori Tidak Ditemukan';
        }

        $pdfData = array_merge($data, [
            'title' => 'Laporan Penjualan',
            'categoryName' => $categoryName
        ]);

        // Configure DomPDF options
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Create DomPDF instance
        $dompdf = new Dompdf($options);

        // Load HTML content
        $html = view('admin.reports.sales-pdf', $pdfData)->render();
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_H-i-s');
        $pdfFilename = $filename . '_' . $timestamp . '.pdf';

        // Return PDF download response
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            $pdfFilename,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $pdfFilename . '"'
            ]
        );
    }

    /**
     * Export to CSV format.
     */
    private function exportToCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Tanggal Pesanan',
                'ID Pesanan',
                'Pelanggan',
                'Status',
                'Total Harga',
                'Produk',
                'Kategori',
                'Jumlah',
                'Sub Total'
            ]);

            // Data rows
            foreach ($data['orders'] as $order) {
                foreach ($order->detailPesanan as $detail) {
                    fputcsv($file, [
                        $order->tanggal_pesanan->format('Y-m-d H:i:s'),
                        $order->idPesanan,
                        $order->user->username ?? 'Guest',
                        $order->status_pesanan,
                        $order->total_harga,
                        $detail->produk->nama_produk,
                        $detail->produk->kategori->nama_kategori ?? 'N/A',
                        $detail->jumlah,
                        $detail->sub_total
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
