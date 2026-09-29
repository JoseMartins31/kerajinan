@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang di dashboard admin Anda')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Produk
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pesanan Tertunda
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Aksi Pesanan:</div>
                            <a class="dropdown-item" href="{{ route('admin.orders') }}">Lihat Semua Pesanan</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.orders') }}?status=pending">Pesanan Tertunda</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->idPesanan }}</td>
                                        <td>{{ $order->user->username ?? 'Guest' }}</td>
                                        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $order->status_pesanan === 'confirmed' ? 'success' : ($order->status_pesanan === 'waiting_payment' ? 'warning' : 'secondary') }}">
                                                @switch($order->status_pesanan)
                                                    @case('waiting_payment')
                                                        Menunggu Pembayaran
                                                    @break

                                                    @case('waiting_confirmation')
                                                        Menunggu Konfirmasi
                                                    @break

                                                    @case('confirmed')
                                                        Dikonfirmasi
                                                    @break

                                                    @case('processing')
                                                        Diproses
                                                    @break

                                                    @case('shipped')
                                                        Dikirim
                                                    @break

                                                    @case('delivered')
                                                        Diterima
                                                    @break

                                                    @case('cancelled')
                                                        Dibatalkan
                                                    @break

                                                    @case('payment_rejected')
                                                        Pembayaran Ditolak
                                                    @break

                                                    @default
                                                        {{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->idPesanan) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada pesanan terbaru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Notifications -->
            <div class="col-lg-4 mb-4">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Product
                            </a>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-info">
                                <i class="fas fa-tags me-2"></i>Add New Category
                            </a>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-success">
                                <i class="fas fa-percent me-2"></i>Create Promotion
                            </a>
                            <a href="{{ route('admin.orders') }}?status=pending" class="btn btn-warning">
                                <i class="fas fa-clock me-2"></i>Review Pending Orders
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                @if ($lowStockProducts->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Peringatan Stok Rendah</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($lowStockProducts as $product)
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                        <div>
                                            <h6 class="my-0">{{ Str::limit($product->nama_produk, 30) }}</h6>
                                            <small class="text-muted">Stok: {{ $product->stok }} tersisa</small>
                                        </div>
                                        <span class="badge badge-danger badge-pill">{{ $product->stok }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($lowStockProducts->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.products.index') }}?low_stock=1"
                                        class="btn btn-sm btn-outline-danger">
                                        View All Low Stock Items
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Active Promotions -->
                @if ($activePromotions->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">Promosi Aktif</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($activePromotions as $promotion)
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $promotion->produk->nama_produk ?? 'Unknown Product' }}</h6>
                                            <small class="text-success">{{ $promotion->diskon }}% OFF</small>
                                        </div>
                                        <small class="text-muted">
                                            Berakhir: {{ \Carbon\Carbon::parse($promotion->tanggal_akhir)->format('d M Y') }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                            @if ($activePromotions->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-sm btn-outline-success">
                                        View All Promotions
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Sales Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Ringkasan Penjualan (30 Hari Terakhir)</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Aksi Grafik:</div>
                                <a class="dropdown-item" href="#"
                                    onclick="alert('Fitur unduh laporan segera hadir!')">Unduh Laporan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="salesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
                    </div>
                    <div class="card-body">
                        @forelse($topProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}"
                                        class="rounded" width="50" height="50" style="object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ Str::limit($product->nama_produk, 25) }}</h6>
                                    <small class="text-muted">Penjualan: {{ $product->total_sales ?? 0 }} item</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-primary">{{ $product->kategori->nama_kategori ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">Belum ada data penjualan</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .border-left-primary {
                border-left: 0.25rem solid #4e73df !important;
            }

            .border-left-success {
                border-left: 0.25rem solid #1cc88a !important;
            }

            .border-left-info {
                border-left: 0.25rem solid #36b9cc !important;
            }

            .border-left-warning {
                border-left: 0.25rem solid #f6c23e !important;
            }

            .badge {
                font-size: 0.75em;
            }

            .badge-success {
                background-color: #1cc88a;
            }

            .badge-warning {
                background-color: #f6c23e;
            }

            .badge-danger {
                background-color: #e74a3b;
            }

            .badge-primary {
                background-color: #4e73df;
            }

            .badge-secondary {
                background-color: #858796;
            }

            .chart-area {
                position: relative;
                height: 300px;
            }

            .list-group-item {
                border: none !important;
            }

            .card {
                border: none;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales Chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chartLabels = {!! json_encode($chartLabels ?? []) !!};
            const chartData = {!! json_encode($chartData ?? []) !!};

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Penjualan Harian (Rp)',
                        data: chartData,
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(255, 255, 255, 2)',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(255, 255, 255, 2)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        fill: true,
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
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7,
                                color: '#858796'
                            }
                        },
                        y: {
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                color: '#858796',
                                callback: function(value, index, values) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                    } else {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            grid: {
                                color: "rgba(234, 236, 244, 0.5)",
                                drawBorder: false,
                                borderDash: [2],
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#858796',
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: "rgba(255,255,255,0.95)",
                            bodyColor: "#858796",
                            titleColor: '#6e707e',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            displayColors: true,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            caretSize: 6,
                            cornerRadius: 6,
                            callbacks: {
                                title: function(context) {
                                    return 'Tanggal: ' + context[0].label;
                                },
                                label: function(context) {
                                    return 'Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                },
                                footer: function(context) {
                                    // Calculate percentage change from previous day
                                    const currentIndex = context[0].dataIndex;
                                    if (currentIndex > 0) {
                                        const current = context[0].parsed.y;
                                        const previous = chartData[currentIndex - 1];
                                        if (previous > 0) {
                                            const change = ((current - previous) / previous * 100).toFixed(1);
                                            return change > 0 ? `↗ +${change}% dari hari sebelumnya` :
                                                `↘ ${change}% dari hari sebelumnya`;
                                        }
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    // Add animation
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Add chart summary info
            const totalSales = chartData.reduce((sum, value) => sum + value, 0);
            const averageSales = totalSales / chartData.length;
            const maxSales = Math.max(...chartData);

            console.log('Chart Summary:');
            console.log('Total Sales (30 days): Rp ' + totalSales.toLocaleString('id-ID'));
            console.log('Average Daily Sales: Rp ' + averageSales.toLocaleString('id-ID'));
            console.log('Highest Single Day: Rp ' + maxSales.toLocaleString('id-ID'));
        </script>
    @endpush
