<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 10px;
        }

        .filters {
            background-color: #e9ecef;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
        }

        .filters strong {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9ecef !important;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .top-products {
            margin-bottom: 30px;
        }

        .top-products h3 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .category-stats {
            margin-bottom: 30px;
        }

        .category-stats h3 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Kerajinan Indonesia - Laporan Penjualan</p>
        <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        <p>Digenerate pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="filters">
        <strong>Filter:</strong>
        Status: {{ $status === 'all' ? 'Semua Status' : ucfirst(str_replace('_', ' ', $status)) }} |
        Kategori: {{ $categoryName ?? 'Semua Kategori' }} |
        Periode: {{ ucfirst($period) }}
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
            <div class="stat-label">Rata-rata per Pesanan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $customerStats['total_customers'] }}</div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>

    @if (count($topProducts) > 0)
        <div class="top-products">
            <h3>Produk Terlaris</h3>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Terjual</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topProducts as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->nama_produk }}</td>
                            <td>{{ $product->kategori->nama_kategori ?? 'N/A' }}</td>
                            <td>{{ $product->total_sold }}</td>
                            <td>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if (count($categoryStats) > 0)
        <div class="category-stats">
            <h3>Performa Kategori</h3>
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Pesanan</th>
                        <th>Items Terjual</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categoryStats as $stat)
                        <tr>
                            <td>{{ $stat->nama_kategori }}</td>
                            <td>{{ number_format($stat->total_orders) }}</td>
                            <td>{{ number_format($stat->total_items) }}</td>
                            <td>Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="page-break"></div>

    <h3>Detail Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Status</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Total Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                @foreach ($order->detailPesanan as $index => $detail)
                    <tr>
                        <td>{{ $order->tanggal_pesanan->format('d/m/Y') }}</td>
                        <td>#{{ $order->idPesanan }}</td>
                        <td>{{ $order->user->username ?? 'Guest' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}</td>
                        <td>{{ $detail->produk->nama_produk }}</td>
                        <td>{{ $detail->produk->kategori->nama_kategori ?? 'N/A' }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        <td>{{ $index === 0 ? 'Rp ' . number_format($order->total_harga, 0, ',', '.') : '' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Kerajinan Indonesia</p>
        <p>{{ now()->format('d F Y, H:i:s') }}</p>
    </div>
</body>

</html>
