@extends('layouts.admin')

@section('title', 'Detail Promosi')
@section('page-title', 'Detail Promosi')
@section('page-description', 'Informasi lengkap promosi dan diskon produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promosi.index') }}">Promosi</a></li>
    <li class="breadcrumb-item active">Detail Promosi</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.promosi.edit', $promosi->idPromosi) }}" class="btn btn-warning me-2">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    <a href="{{ route('admin.promosi.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
    </a>
    <form method="POST" action="{{ route('admin.promosi.toggle', $promosi->idPromosi) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-{{ $promosi->status === 'aktif' ? 'secondary' : 'success' }}">
            <i class="fas fa-{{ $promosi->status === 'aktif' ? 'pause' : 'play' }} me-1"></i>
            {{ $promosi->status === 'aktif' ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
    </div>
    </div>
    </div>

    <div class="row">
        <!-- Main Promotion Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-percent me-2"></i>Promotion Information
                    </h5>
                    <div>
                        <span
                            class="badge {{ $promosi->status === 'aktif' ? 'promotion-status-active' : 'promotion-status-inactive' }} fs-6">
                            <i class="fas fa-{{ $promosi->status === 'aktif' ? 'check-circle' : 'pause-circle' }} me-1"></i>
                            {{ $promosi->status === 'aktif' ? 'Active' : 'Inactive' }}
                        </span>
                        @if ($promosi->tanggal_berakhir < now())
                            <span class="badge bg-danger fs-6 ms-1">
                                <i class="fas fa-clock me-1"></i>Expired
                            </span>
                        @elseif($promosi->tanggal_mulai > now())
                            <span class="badge bg-primary fs-6 ms-1">
                                <i class="fas fa-clock me-1"></i>Scheduled
                            </span>
                        @else
                            <span class="badge bg-success fs-6 ms-1">
                                <i class="fas fa-play me-1"></i>Running
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Product Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            @if ($promosi->produk->foto)
                                <img src="{{ asset('storage/' . $promosi->produk->foto) }}"
                                    alt="{{ $promosi->produk->nama_produk }}" class="img-fluid rounded shadow-sm">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3 class="text-primary mb-2">{{ $promosi->produk->nama_produk }}</h3>
                            <p class="text-muted mb-3">{{ Str::limit($promosi->produk->deskripsi, 200) }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Category</h6>
                                    <span class="badge bg-secondary mb-2">
                                        {{ $promosi->produk->kategori->nama_kategori ?? 'No Category' }}
                                    </span>

                                    <h6 class="text-muted mt-3">Stock</h6>
                                    <span
                                        class="badge bg-{{ $promosi->produk->stok > 10 ? 'success' : ($promosi->produk->stok > 0 ? 'warning' : 'danger') }}">
                                        {{ $promosi->produk->stok }} units
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Seller</h6>
                                    <p class="mb-2">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $promosi->produk->user->username ?? 'Unknown' }}
                                    </p>

                                    <h6 class="text-muted mt-3">Product Status</h6>
                                    <span class="badge bg-info">{{ $promosi->produk->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Pricing Details</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                                                <h6 class="text-muted">Original Price</h6>
                                                <h4 class="text-dark">Rp {{ number_format($promosi->produk->Harga) }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-percent fa-2x text-danger mb-2"></i>
                                                <h6 class="text-muted">Discount</h6>
                                                <h4 class="text-danger">{{ $promosi->diskon }}%</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-calculator fa-2x text-warning mb-2"></i>
                                                <h6 class="text-muted">You Save</h6>
                                                <h4 class="text-warning">Rp
                                                    {{ number_format(($promosi->produk->Harga * $promosi->diskon) / 100) }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                                <h6 class="text-muted">Final Price</h6>
                                                <h4 class="text-success">Rp
                                                    {{ number_format($promosi->produk->Harga * (1 - $promosi->diskon / 100)) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-calendar me-1"></i>Promotion Timeline
                            </h6>
                            <div class="timeline">
                                <div
                                    class="timeline-item {{ $promosi->tanggal_mulai <= now() ? 'completed' : 'pending' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Promotion Starts</h6>
                                        <p class="text-muted mb-1">
                                            {{ \Carbon\Carbon::parse($promosi->tanggal_mulai)->format('l, F j, Y \a\t H:i') }}
                                        </p>
                                        <small
                                            class="text-info">{{ \Carbon\Carbon::parse($promosi->tanggal_mulai)->diffForHumans() }}</small>
                                    </div>
                                </div>

                                <div
                                    class="timeline-item {{ $promosi->tanggal_berakhir <= now() ? 'completed' : 'pending' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-stop"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Promotion Ends</h6>
                                        <p class="text-muted mb-1">
                                            {{ \Carbon\Carbon::parse($promosi->tanggal_berakhir)->format('l, F j, Y \a\t H:i') }}
                                        </p>
                                        <small
                                            class="text-{{ $promosi->tanggal_berakhir <= now() ? 'danger' : 'warning' }}">
                                            {{ \Carbon\Carbon::parse($promosi->tanggal_berakhir)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="p-3 bg-primary text-white rounded">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h6>Duration</h6>
                                <strong>
                                    {{ \Carbon\Carbon::parse($promosi->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($promosi->tanggal_berakhir)) }}
                                    days
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted">Created</small>
                                <br>
                                <strong>{{ $promosi->created_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted">Updated</small>
                                <br>
                                <strong>{{ $promosi->updated_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promosi.edit', $promosi->idPromosi) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Promotion
                        </a>

                        <form method="POST" action="{{ route('admin.promosi.toggle', $promosi->idPromosi) }}">
                            @csrf
                            <button type="submit"
                                class="btn btn-{{ $promosi->status === 'aktif' ? 'secondary' : 'success' }} w-100">
                                <i class="fas fa-{{ $promosi->status === 'aktif' ? 'pause' : 'play' }} me-1"></i>
                                {{ $promosi->status === 'aktif' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <button class="btn btn-info" onclick="duplicatePromotion()">
                            <i class="fas fa-copy me-1"></i>Duplicate Promotion
                        </button>

                        <button class="btn btn-danger" onclick="deletePromotion({{ $promosi->idPromosi }})">
                            <i class="fas fa-trash me-1"></i>Delete Promotion
                        </button>

                        <a href="{{ route('admin.produk.show', $promosi->produk->idProduk) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-box me-1"></i>View Product Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Promotion Preview -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-storefront me-2"></i>Customer View
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative mb-3">
                        @if ($promosi->produk->foto)
                            <img src="{{ asset('storage/' . $promosi->produk->foto) }}"
                                alt="{{ $promosi->produk->nama_produk }}" class="img-fluid rounded"
                                style="max-height: 150px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 150px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                            {{ $promosi->diskon }}% OFF
                        </span>
                    </div>

                    <h6 class="text-primary">{{ $promosi->produk->nama_produk }}</h6>

                    <div class="mb-2">
                        <span class="text-decoration-line-through text-muted">
                            Rp {{ number_format($promosi->produk->Harga) }}
                        </span>
                    </div>

                    <h4 class="text-success mb-3">
                        Rp {{ number_format($promosi->produk->Harga * (1 - $promosi->diskon / 100)) }}
                    </h4>

                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-clock me-1"></i>
                        @if ($promosi->tanggal_berakhir < now())
                            Promotion ended
                        @else
                            Ends {{ \Carbon\Carbon::parse($promosi->tanggal_berakhir)->diffForHumans() }}
                        @endif
                    </small>

                    <button class="btn btn-primary btn-sm" disabled>
                        <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 50px;
            margin-bottom: 30px;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 40px;
            bottom: -30px;
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .timeline-item.completed .timeline-marker {
            background-color: #28a745;
        }

        .timeline-item.pending .timeline-marker {
            background-color: #ffc107;
            color: #212529;
        }

        .timeline-content {
            padding-top: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deletePromotion(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This promotion will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/promosi/${id}`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = $('meta[name="csrf-token"]').attr('content');

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function duplicatePromotion() {
            Swal.fire({
                title: 'Duplicate Promotion',
                text: "This will create a new promotion with the same settings.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, duplicate it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to create page with pre-filled values
                    const params = new URLSearchParams({
                        duplicate: '{{ $promosi->idPromosi }}',
                        idProduk: '{{ $promosi->idProduk }}',
                        diskon: '{{ $promosi->diskon }}',
                        status: 'tidak_aktif'
                    });
                    window.location.href = `{{ route('admin.promosi.create') }}?${params.toString()}`;
                }
            });
        }
    </script>
@endpush
