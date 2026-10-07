@extends('layouts.admin')

@section('title', 'Promosi')
@section('page-title', 'Manajemen Promosi')
@section('page-description', 'Kelola promosi dan diskon produk')

@section('breadcrumb')
    <li class="breadcrumb-item active">Promosi</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkApplyModal">
            <i class="fas fa-layer-group me-2"></i>Aplikasi Massal
        </button>
        <a href="{{ route('admin.promosi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Promosi
        </a>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title text-white mb-1">
                                {{ $promosi->filter(function ($p) {return $p->isActive();})->count() }}</h3>
                            <p class="card-text text-white-50 mb-0">Promosi Aktif</p>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title text-white mb-1">
                                {{ $promosi->filter(function ($p) {return $p->isScheduled();})->count() }}</h3>
                            <p class="card-text text-white-50 mb-0">Promosi Terjadwal</p>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title text-white mb-1">
                                {{ number_format($promosi->avg(function ($p) {return $p->getDiscountPercentage();}) ?? 0,1) }}%
                            </h3>
                            <p class="card-text text-white-50 mb-0">Rata-rata Diskon</p>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-danger">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title text-white mb-1">
                                {{ $promosi->filter(function ($p) {return $p->isExpired();})->count() }}</h3>
                            <p class="card-text text-white-50 mb-0">Promosi Berakhir</p>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">
                <i class="fas fa-percentage me-2"></i>Daftar Promosi
            </h6>
            {{-- <div class="card-tools">
                <a href="{{ route('admin.promosi.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Promosi
                </a>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="promosiTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th width="25%">Produk</th>
                            <th width="10%">Nama Promosi</th>
                            <th width="10%">Diskon</th>
                            <th width="12%">Tanggal Mulai</th>
                            <th width="12%">Tanggal Selesai</th>
                            <th width="10%">Status</th>
                            <th width="16%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promosi as $promo)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input promotion-checkbox"
                                        value="{{ $promo->idPromosi }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="product-thumbnail me-3">
                                            @if ($promo->produk && $promo->produk->foto)
                                                <img src="{{ Storage::url($promo->produk->foto) }}"
                                                    alt="{{ $promo->produk->nama_produk }}" class="rounded product-img"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="product-placeholder d-flex align-items-center justify-content-center rounded"
                                                    style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-name mb-1">
                                                {{ $promo->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</h6>
                                            <small
                                                class="text-muted d-block">{{ $promo->produk->kategori->nama_kategori ?? 'Tanpa Kategori' }}</small>
                                            @if ($promo->produk)
                                                <small
                                                    class="text-info">{{ $promo->produk->getFormattedOriginalPrice() }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="promotion-name">
                                        <strong>{{ $promo->nama_promosi ?? 'Promosi #' . $promo->idPromosi }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="discount-info">
                                        <span class="badge bg-success fs-6">{{ $promo->getDiscountPercentage() }}%</span>
                                        @if ($promo->produk)
                                            <small class="text-muted d-block">
                                                Hemat Rp
                                                {{ number_format(($promo->produk->Harga * $promo->getDiscountPercentage()) / 100, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <span class="badge bg-info">
                                            {{ $promo->tanggal_mulai ? $promo->tanggal_mulai->format('d/m/Y') : '-' }}
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ $promo->tanggal_mulai ? $promo->tanggal_mulai->format('H:i') : '-' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <span class="badge bg-warning text-dark">
                                            {{ $promo->getEndDate() ? $promo->getEndDate()->format('d/m/Y') : '-' }}
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ $promo->getEndDate() ? $promo->getEndDate()->diffForHumans() : '-' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $promo->getStatusClass() }}">
                                        @if ($promo->isExpired())
                                            <i class="fas fa-times-circle me-1"></i>
                                        @elseif($promo->isScheduled())
                                            <i class="fas fa-clock me-1"></i>
                                        @elseif($promo->isActive())
                                            <i class="fas fa-check-circle me-1"></i>
                                        @else
                                            <i class="fas fa-pause-circle me-1"></i>
                                        @endif
                                        {{ $promo->getStatusDisplay() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.promosi.show', $promo->idPromosi) }}"
                                            class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.promosi.edit', $promo->idPromosi) }}"
                                            class="btn btn-sm btn-outline-success" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (!$promo->isExpired())
                                            <button type="button"
                                                class="btn btn-sm btn-outline-{{ $promo->isActive() ? 'warning' : 'info' }}"
                                                onclick="toggleStatus({{ $promo->idPromosi }}, '{{ $promo->isActive() ? 'tidak_aktif' : 'aktif' }}')"
                                                title="{{ $promo->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fas fa-{{ $promo->isActive() ? 'pause' : 'play' }}"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                            title="Hapus"
                                            data-promotion-name="{{ $promo->nama_promosi ?? 'Promosi #' . $promo->idPromosi }}"
                                            data-delete-url="{{ route('admin.promosi.destroy', $promo->idPromosi) }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bulk Apply Modal -->
    <div class="modal fade" id="bulkApplyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-layer-group me-2"></i>Bulk Apply Promotion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.promosi.bulk-apply') }}" method="POST" id="bulkApplyForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Start Date *</label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date *</label>
                                <input type="datetime-local" name="tanggal_berakhir" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Discount Percentage *</label>
                                <div class="input-group">
                                    <input type="number" name="diskon" class="form-control" min="0"
                                        max="100" step="0.01" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif">Active</option>
                                    <option value="tidak_aktif">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Select Products *</label>
                            <select name="product_ids[]" class="form-select select2" multiple required>
                                @foreach (\App\Models\Produk::with('kategori')->get() as $product)
                                    <option value="{{ $product->idProduk }}">
                                        {{ $product->nama_produk }}
                                        ({{ $product->kategori->nama_kategori ?? 'No Category' }})
                                        -
                                        Rp {{ number_format($product->Harga) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Products with existing active promotions will be skipped.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-layer-group me-1"></i>Apply to Selected Products
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#promosiTable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    emptyTable: 'Belum Ada Promosi'
                },
                order: [
                    [4, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                }]
            });

            // Select All functionality
            $('#selectAll').change(function() {
                $('.promotion-checkbox').prop('checked', this.checked);
            });

            $('.promotion-checkbox').change(function() {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                }
            });

            // Initialize date inputs with current date
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const startInput = document.querySelector('input[name="tanggal_mulai"]');
            const endInput = document.querySelector('input[name="tanggal_berakhir"]');

            if (startInput) {
                startInput.value = now.toISOString().slice(0, 16);
            }

            if (endInput) {
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 7);
                endInput.value = tomorrow.toISOString().slice(0, 16);
            }

            // Delete button handler
            $(document).on('click', '.btn-delete', function() {
                const promotionName = $(this).data('promotion-name');
                const deleteUrl = $(this).data('delete-url');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Promosi "${promotionName}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit delete form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = deleteUrl;

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
            });
        });

        // Toggle promotion status
        function toggleStatus(id, status) {
            const statusText = status === 'aktif' ? 'mengaktifkan' : 'menonaktifkan';

            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin ${statusText} promosi ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/promosi/${id}/toggle`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = $('meta[name="csrf-token"]').attr('content');

                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = status;

                    form.appendChild(csrfInput);
                    form.appendChild(statusInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Form submission with loading state
        $('#bulkApplyForm').submit(function(e) {
            const selectedProducts = $('select[name="product_ids[]"]').val();
            if (!selectedProducts || selectedProducts.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Silakan pilih minimal satu produk!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-1"></i>Menerapkan...');
        });
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .product-img {
            transition: transform 0.3s ease;
        }

        .product-img:hover {
            transform: scale(1.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge {
            font-size: 0.8em;
        }

        .empty-state {
            padding: 2rem;
        }

        .promotion-checkbox:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush
