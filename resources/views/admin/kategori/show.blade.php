@extends('layouts.admin')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori')
@section('page-description', 'Informasi lengkap kategori dan produk terkait')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">{{ $kategori->nama_kategori }}</li>
@endsection

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.kategori.edit', $kategori->idKategori) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash me-2"></i>Hapus
        </button>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Category Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="category-icon-large mx-auto mb-3">
                            <i class="fas fa-tag fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-2">{{ $kategori->nama_kategori }}</h4>
                        <span class="badge bg-primary fs-6">ID: {{ $kategori->idKategori }}</span>
                    </div>

                    <div class="category-details">
                        <div class="detail-item mb-3">
                            <label class="form-label text-muted mb-1">
                                <i class="fas fa-align-left me-2"></i>Deskripsi
                            </label>
                            <p class="mb-0">{{ $kategori->deskripsi }}</p>
                        </div>

                        <div class="detail-item mb-3">
                            <label class="form-label text-muted mb-1">
                                <i class="fas fa-calendar me-2"></i>Dibuat Pada
                            </label>
                            <p class="mb-0">{{ $kategori->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>

                        <div class="detail-item mb-3">
                            <label class="form-label text-muted mb-1">
                                <i class="fas fa-clock me-2"></i>Terakhir Diperbarui
                            </label>
                            <p class="mb-0">{{ $kategori->updated_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h3 class="text-primary mb-1">{{ $kategori->produk->count() }}</h3>
                                <small class="text-muted">Total Produk</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h3 class="text-success mb-1">
                                    {{ $kategori->produk->where('status', 'available')->count() }}</h3>
                                <small class="text-muted">Produk Aktif</small>
                            </div>
                        </div>
                    </div>

                    @if ($kategori->produk->count() > 0)
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <h4 class="text-info mb-1">Rp
                                        {{ number_format($kategori->produk->avg('Harga'), 0, ',', '.') }}</h4>
                                    <small class="text-muted">Harga Rata-rata</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h4 class="text-warning mb-1">{{ $kategori->produk->sum('stok') }}</h4>
                                    <small class="text-muted">Total Stok</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Produk dalam Kategori
                        <span class="badge bg-primary ms-2">{{ $kategori->produk->count() }}</span>
                    </h5>
                    @if ($kategori->produk->count() > 0)
                        <div class="card-tools">
                            <a href="{{ route('admin.produk.create') }}?kategori={{ $kategori->idKategori }}"
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Produk
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($kategori->produk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori->produk as $produk)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="product-image me-3">
                                                        @if ($produk->foto)
                                                            <img src="{{ asset('storage/' . $produk->foto) }}"
                                                                alt="{{ $produk->nama_produk }}" class="rounded"
                                                                width="50" height="50" style="object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ Str::limit($produk->nama_produk, 30) }}</h6>
                                                        <small class="text-muted">ID: {{ $produk->idProduk }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp
                                                    {{ number_format($produk->Harga, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge {{ $produk->stok < 10 ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $produk->stok }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $produk->status === 'available' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $produk->status === 'available' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.produk.show', $produk->idProduk) }}"
                                                        class="btn btn-outline-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.produk.edit', $produk->idProduk) }}"
                                                        class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">Belum Ada Produk</h5>
                            <p class="text-muted mb-4">Kategori ini belum memiliki produk. Mulai dengan menambahkan produk
                                pertama.</p>
                            <a href="{{ route('admin.produk.create') }}?kategori={{ $kategori->idKategori }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if ($kategori->produk->count() > 0)
                <!-- Product Statistics -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-gradient-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">Rp
                                            {{ number_format($kategori->produk->max('Harga'), 0, ',', '.') }}</h4>
                                        <p class="mb-0">Harga Tertinggi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-gradient-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">Rp
                                            {{ number_format($kategori->produk->min('Harga'), 0, ',', '.') }}</h4>
                                        <p class="mb-0">Harga Terendah</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus kategori
                        <strong>"{{ $kategori->nama_kategori }}"</strong>?</p>

                    @if ($kategori->produk->count() > 0)
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Peringatan!</h6>
                            <p class="mb-2">Kategori ini memiliki <strong>{{ $kategori->produk->count() }}
                                    produk</strong>. Menghapus kategori akan:</p>
                            <ul class="mb-0">
                                <li>Menghapus semua produk dalam kategori ini</li>
                                <li>Tidak dapat dibatalkan setelah dihapus</li>
                                <li>Mempengaruhi data statistik</li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <form action="{{ route('admin.kategori.destroy', $kategori->idKategori) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Ya, Hapus Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .category-icon-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 0.75rem;
        }

        .detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .stat-item {
            padding: 0.5rem;
        }

        .empty-state {
            padding: 2rem 0;
        }

        .product-image img {
            border: 2px solid #e9ecef;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add tooltip for action buttons
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
