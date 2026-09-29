@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', $produk->nama_produk)
@section('page-description', 'Detail informasi produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($produk->nama_produk, 30) }}</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.produk.edit', $produk->idProduk) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Produk
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Main Product Info -->
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Product Image -->
                            @if ($produk->foto)
                                <div class="product-image-container">
                                    <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk }}"
                                        class="img-fluid rounded shadow product-image">
                                </div>
                            @else
                                <div class="product-placeholder d-flex align-items-center justify-content-center rounded"
                                    style="height: 250px; background-color: #f8f9fa;">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada foto</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="product-title">{{ $produk->nama_produk }}</h4>

                            <!-- Price Information -->
                            <div class="price-section mb-3">
                                @if ($produk->hasActivePromotion())
                                    <div class="price-with-discount">
                                        <span class="original-price text-decoration-line-through text-muted">
                                            {{ $produk->getFormattedOriginalPrice() }}
                                        </span>
                                        <span class="discounted-price text-success fw-bold fs-4">
                                            {{ $produk->getFormattedCurrentPrice() }}
                                        </span>
                                        <span class="discount-badge badge bg-success ms-2">
                                            -{{ $produk->getBestActivePromotion()->persentase_diskon }}% OFF
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Hemat {{ $produk->getFormattedDiscountAmount() }}
                                    </small>
                                @else
                                    <div class="price-regular">
                                        <span class="current-price fw-bold fs-4 text-primary">
                                            {{ $produk->getFormattedOriginalPrice() }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Meta -->
                            <div class="product-meta">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="meta-item">
                                            <i class="fas fa-tags text-primary me-2"></i>
                                            <strong>Kategori:</strong>
                                            <span
                                                class="badge bg-primary ms-2">{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="meta-item">
                                            <i
                                                class="fas fa-circle text-{{ $produk->status === 'aktif' ? 'success' : ($produk->status === 'tidak_aktif' ? 'secondary' : 'warning') }} me-2"></i>
                                            <strong>Status:</strong>
                                            <span
                                                class="badge bg-{{ $produk->status === 'aktif' ? 'success' : ($produk->status === 'tidak_aktif' ? 'secondary' : 'warning') }} ms-2">
                                                {{ ucfirst(str_replace('_', ' ', $produk->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            <strong>Tanggal Upload:</strong>
                                            <span
                                                class="ms-2">{{ $produk->tanggal_upload ? $produk->tanggal_upload->format('d F Y, H:i') : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="meta-item">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            <strong>Diupload oleh:</strong>
                                            <span class="ms-2">{{ $produk->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-file-text me-2"></i>Deskripsi Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="product-description">
                        {!! nl2br(e($produk->deskripsi)) !!}
                    </div>
                </div>
            </div>

            <!-- Promotions -->
            @if ($produk->promosi->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-percentage me-2"></i>Promosi Terkait
                            <span class="badge bg-info ms-2">{{ $produk->promosi->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($produk->promosi as $promo)
                                <div class="col-md-6 mb-3">
                                    <div
                                        class="promotion-card p-3 rounded border {{ $promo->isActive() ? 'border-success bg-light-success' : 'border-secondary' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="promotion-title mb-0">{{ $promo->nama_promosi }}</h6>
                                            @if ($promo->isActive())
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($promo->tanggal_mulai > now())
                                                <span class="badge bg-warning">Terjadwal</span>
                                            @else
                                                <span class="badge bg-secondary">Berakhir</span>
                                            @endif
                                        </div>
                                        <div class="promotion-details">
                                            <div class="discount-info mb-2">
                                                <i class="fas fa-percentage text-success me-2"></i>
                                                <strong>{{ $promo->persentase_diskon }}% OFF</strong>
                                            </div>
                                            <div class="promotion-period">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $promo->tanggal_mulai->format('d/m/Y') }} -
                                                    {{ $promo->tanggal_selesai->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <!-- Stock -->
                        <div class="stat-item mb-3">
                            <div class="stat-icon">
                                <i class="fas fa-boxes text-primary"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Stok</div>
                                <div class="stat-value">
                                    @php
                                        $stok = $produk->stok ?? 0;
                                    @endphp
                                    <span
                                        class="fw-bold text-{{ $stok <= 5 ? 'danger' : ($stok <= 20 ? 'warning' : 'success') }}">
                                        {{ $stok }}
                                    </span>
                                    @if ($stok <= 5)
                                        <small class="text-danger d-block">Stok Rendah!</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Total Sales -->
                        <div class="stat-item mb-3">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart text-success"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Penjualan</div>
                                <div class="stat-value fw-bold text-success">
                                    {{ $produk->detailPesanan->sum('jumlah') ?? 0 }}
                                </div>
                            </div>
                        </div>

                        <!-- Reviews -->
                        <div class="stat-item mb-3">
                            <div class="stat-icon">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Testimoni</div>
                                <div class="stat-value">
                                    <span class="fw-bold text-warning">{{ $produk->testimoni->count() }}</span>
                                    @if ($produk->testimoni->count() > 0)
                                        <small class="text-muted d-block">
                                            Rating rata-rata: {{ number_format($produk->testimoni->avg('rating'), 1) }}/5
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Active Promotions -->
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-percentage text-info"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Promosi Aktif</div>
                                <div class="stat-value fw-bold text-info">
                                    {{ $produk->getActivePromotions()->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.produk.edit', $produk->idProduk) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Produk
                        </a>

                        @if ($produk->status !== 'aktif')
                            <button class="btn btn-outline-success" onclick="updateStatus('aktif')">
                                <i class="fas fa-check me-2"></i>Aktifkan
                            </button>
                        @else
                            <button class="btn btn-outline-warning" onclick="updateStatus('tidak_aktif')">
                                <i class="fas fa-pause me-2"></i>Nonaktifkan
                            </button>
                        @endif

                        <a href="{{ route('admin.promosi.create') }}?produk={{ $produk->idProduk }}"
                            class="btn btn-outline-info">
                            <i class="fas fa-percentage me-2"></i>Tambah Promosi
                        </a>

                        <button class="btn btn-outline-danger" onclick="deleteProduct()">
                            <i class="fas fa-trash me-2"></i>Hapus Produk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.15);
            border: none;
            border-radius: 0.75rem;
        }

        .card-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .product-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .price-section {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 4px solid #007bff;
        }

        .original-price {
            font-size: 1.1rem;
            margin-right: 0.5rem;
        }

        .discounted-price {
            font-size: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .product-description {
            line-height: 1.6;
            color: #555;
        }

        .promotion-card {
            transition: transform 0.2s ease;
        }

        .promotion-card:hover {
            transform: translateY(-2px);
        }

        .bg-light-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }

        .stats-grid .stat-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 4px solid #007bff;
        }

        .stat-icon {
            margin-right: 1rem;
            font-size: 1.5rem;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function updateStatus(newStatus) {
            if (confirm(
                `Apakah Anda yakin ingin ${newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan'} produk ini?`)) {
                // Create form to update status
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('admin.produk.update', $produk->idProduk) }}'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'PUT'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'status',
                    'value': newStatus
                }));

                // Add other required fields with current values
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'nama_produk',
                    'value': '{{ $produk->nama_produk }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'deskripsi',
                    'value': '{{ addslashes($produk->deskripsi) }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'Harga',
                    'value': '{{ $produk->Harga }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'idKategori',
                    'value': '{{ $produk->idKategori }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'idUser',
                    'value': '{{ $produk->idUser }}'
                }));

                $('body').append(form);
                form.submit();
            }
        }

        function deleteProduct() {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus produk "{{ $produk->nama_produk }}"? Tindakan ini tidak dapat dibatalkan.'
                    )) {
                // Create delete form
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('admin.produk.destroy', $produk->idProduk) }}'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));

                $('body').append(form);
                form.submit();
            }
        }
    </script>
@endpush
