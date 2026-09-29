@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-description', 'Analisis lengkap penjualan dan performa bisnis')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Penjualan</li>
@endsection

@push('styles')
    <style>
        .report-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card.revenue {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.orders {
            background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
        }

        .stat-card.avg {
            background: linear-gradient(135deg, #fdbb2d 0%, #22c1c3 100%);
        }

        .stat-card.customers {
            background: linear-gradient(135deg, #ee9ca7 0%, #ffdde1 100%);
            color: #333;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .change-indicator {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .change-up {
            color: #28a745;
        }

        .change-down {
            color: #dc3545;
        }

        .filter-card {
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 2rem;
        }

        .table-modern {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table-modern thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table-modern tbody tr {
            border-bottom: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fc;
            transform: translateY(-1px);
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
        }

        .product-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .export-buttons .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .progress-modern {
            height: 8px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.8rem;
            }

            .filter-card {
                padding: 1rem;
            }

            .chart-container {
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.reports.sales') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Periode</label>
                        <select name="period" class="form-control" onchange="updateDateInputs()">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Harian</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Mingguan</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="shipped" {{ $status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="delivered" {{ $status == 'delivered' ? 'selected' : '' }}>Selesai</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category_id" class="form-control">
                            <option value="all">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->idKategori }}"
                                    {{ $categoryId == $category->idKategori ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card revenue">
                    <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pendapatan</div>
                    @if (isset($previousPeriodStats))
                        <div
                            class="change-indicator {{ $previousPeriodStats['revenue_change'] >= 0 ? 'change-up' : 'change-down' }}">
                            <i class="fas fa-arrow-{{ $previousPeriodStats['revenue_change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs(round($previousPeriodStats['revenue_change'], 1)) }}% dari periode sebelumnya
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card orders">
                    <div class="stat-number">{{ number_format($totalOrders) }}</div>
                    <div class="stat-label">Total Pesanan</div>
                    @if (isset($previousPeriodStats))
                        <div
                            class="change-indicator {{ $previousPeriodStats['orders_change'] >= 0 ? 'change-up' : 'change-down' }}">
                            <i class="fas fa-arrow-{{ $previousPeriodStats['orders_change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs(round($previousPeriodStats['orders_change'], 1)) }}% dari periode sebelumnya
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card avg">
                    <div class="stat-number">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                    <div class="stat-label">Rata-rata per Pesanan</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card customers">
                    <div class="stat-number">{{ number_format($customerStats['total_customers']) }}</div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="change-indicator">
                        <small>{{ number_format($customerStats['retention_rate'], 1) }}% retention rate</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales Chart -->
            <div class="col-xl-8">
                <div class="card report-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Trend Penjualan
                            {{ ucfirst($period == 'day' ? 'Harian' : ($period == 'week' ? 'Mingguan' : 'Bulanan')) }}
                        </h5>
                        <div class="export-buttons">
                            <a href="{{ route('admin.reports.sales') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}"
                                class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a href="{{ route('admin.reports.sales') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                                class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-xl-4">
                <div class="card report-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-trophy me-2"></i>Produk Terlaris
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($topProducts as $index => $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <span class="badge badge-primary"
                                        style="font-size: 1rem; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="me-3">
                                    @if ($product->foto && file_exists(public_path('images/' . $product->foto)))
                                        <img src="{{ asset('images/' . $product->foto) }}"
                                            alt="{{ $product->nama_produk }}" class="product-img">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->nama_produk, 25) }}</h6>
                                    <small class="text-muted">{{ $product->kategori->nama_kategori ?? 'N/A' }}</small>
                                    <div class="progress progress-modern mt-1">
                                        <div class="progress-bar"
                                            style="width: {{ $topProducts->max('total_sold') > 0 ? ($product->total_sold / $topProducts->max('total_sold')) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <small class="text-primary">{{ $product->total_sold }} terjual</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">Tidak ada data penjualan</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Performance -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card report-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tags me-2"></i>Performa Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-modern table-sm">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Pesanan</th>
                                        <th>Items</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoryStats as $stat)
                                        <tr>
                                            <td>
                                                <span class="category-badge bg-primary text-white">
                                                    {{ $stat->nama_kategori }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($stat->total_orders) }}</td>
                                            <td>{{ number_format($stat->total_items) }}</td>
                                            <td>Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Stats -->
            <div class="col-lg-6">
                <div class="card report-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Statistik Pelanggan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="mb-3">
                                    <h3 class="text-primary">{{ $customerStats['total_customers'] }}</h3>
                                    <small class="text-muted">Total Pelanggan</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <h3 class="text-success">{{ $customerStats['new_customers'] }}</h3>
                                    <small class="text-muted">Pelanggan Baru</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <h3 class="text-info">{{ $customerStats['returning_customers'] }}</h3>
                                    <small class="text-muted">Repeat Customer</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Customer Retention Rate</label>
                            <div class="progress progress-modern" style="height: 15px;">
                                <div class="progress-bar bg-success"
                                    style="width: {{ $customerStats['retention_rate'] }}%">
                                    {{ number_format($customerStats['retention_rate'], 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Orders -->
        <div class="card report-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Detail Pesanan ({{ $totalOrders }} pesanan)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->idPesanan }}</strong></td>
                                    <td>{{ $order->tanggal_pesanan->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            {{ $order->user->username ?? 'Guest' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $order->status_pesanan === 'confirmed' ? 'success' : ($order->status_pesanan === 'shipped' ? 'info' : 'primary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            @foreach ($order->detailPesanan->take(2) as $detail)
                                                {{ $detail->produk->nama_produk }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if ($order->detailPesanan->count() > 2)
                                                <br><span class="text-muted">+{{ $order->detailPesanan->count() - 2 }}
                                                    lainnya</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td><strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->idPesanan) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Tidak ada pesanan dalam periode ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = @json($chartData);

        const labels = chartData.map(item => item.label);
        const revenueData = chartData.map(item => item.revenue);
        const ordersData = chartData.map(item => item.orders);

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    yAxisID: 'y',
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'Jumlah Pesanan',
                    data: ordersData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    yAxisID: 'y1',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                } else {
                                    return 'Pesanan: ' + context.parsed.y + ' order';
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah Pesanan'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

        // Auto-update date inputs based on period selection
        function updateDateInputs() {
            const period = document.querySelector('select[name="period"]').value;
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');
            const today = new Date();

            switch (period) {
                case 'day':
                    // Last 7 days
                    startDate.value = new Date(today.getTime() - 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                    endDate.value = today.toISOString().split('T')[0];
                    break;
                case 'week':
                    // Last 4 weeks
                    startDate.value = new Date(today.getTime() - 27 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                    endDate.value = today.toISOString().split('T')[0];
                    break;
                case 'month':
                    // Last 6 months
                    const sixMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 5, 1);
                    startDate.value = sixMonthsAgo.toISOString().split('T')[0];
                    endDate.value = today.toISOString().split('T')[0];
                    break;
            }
        }

        // Auto-submit form when filters change
        document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(element => {
            element.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Print function
        function printReport() {
            window.print();
        }

        // Show loading state for exports
        document.querySelectorAll('.export-buttons a').forEach(link => {
            link.addEventListener('click', function(e) {
                const btn = this;
                const originalText = btn.innerHTML;

                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
                btn.classList.add('disabled');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('disabled');
                }, 3000);
            });
        });
    </script>
@endpush
