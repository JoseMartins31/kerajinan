@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')
@section('page-description', 'Kelola produk kerajinan tangan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Produk</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Produk
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Daftar Produk
                    </h6>
                    <div class="card-tools">
                        <a href="{{ route('admin.produk.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Produk
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="produkTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="8%">Foto</th>
                                    <th width="20%">Nama Produk</th>
                                    <th width="15%">Kategori</th>
                                    <th width="12%">Harga</th>
                                    <th width="8%">Status</th>
                                    <th width="10%">Tanggal Upload</th>
                                    <th width="8%">Stok</th>
                                    <th width="14%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produk as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $item->idProduk }}</span>
                                        </td>
                                        <td>
                                            @if ($item->foto)
                                                <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama_produk }}"
                                                    class="product-thumbnail rounded"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="product-placeholder d-flex align-items-center justify-content-center rounded"
                                                    style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-name">
                                                    <strong>{{ $item->nama_produk }}</strong>
                                                </div>
                                                <small class="text-muted">
                                                    {{ Str::limit($item->deskripsi, 50) }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $item->kategori->nama_kategori ?? 'Tidak ada kategori' }}</span>
                                        </td>
                                        <td>
                                            <div class="price-info">
                                                @if ($item->hasActivePromotion())
                                                    <div
                                                        class="original-price text-decoration-line-through text-muted small">
                                                        {{ $item->getFormattedOriginalPrice() }}
                                                    </div>
                                                    <div class="discounted-price text-success fw-bold">
                                                        {{ $item->getFormattedCurrentPrice() }}
                                                    </div>
                                                    <small class="discount-badge badge bg-success">
                                                        -{{ $item->getBestActivePromotion()->persentase_diskon }}%
                                                    </small>
                                                @else
                                                    <div class="regular-price fw-bold">
                                                        {{ $item->getFormattedOriginalPrice() }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item->status === 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($item->status === 'tidak_aktif')
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($item->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $item->tanggal_upload ? $item->tanggal_upload->format('d/m/Y') : '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $stok = $item->stok ?? 0;
                                            @endphp
                                            @if ($stok <= 5)
                                                <span class="badge bg-danger">{{ $stok }}</span>
                                                <small class="text-danger d-block">Stok Rendah</small>
                                            @elseif($stok <= 20)
                                                <span class="badge bg-warning">{{ $stok }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $stok }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.produk.show', $item->idProduk) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.produk.edit', $item->idProduk) }}"
                                                    class="btn btn-sm btn-outline-success" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    title="Hapus" data-product-name="{{ $item->nama_produk }}"
                                                    data-delete-url="{{ route('admin.produk.destroy', $item->idProduk) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Produk</h5>
                                                <p class="text-muted">Mulai dengan membuat produk pertama Anda.</p>
                                                <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Tambah Produk
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        .product-thumbnail {
            border: 2px solid #e9ecef;
            transition: transform 0.2s ease;
        }

        .product-thumbnail:hover {
            transform: scale(1.1);
            border-color: #007bff;
        }

        .product-placeholder {
            border: 2px dashed #dee2e6;
        }

        .product-info .product-name {
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .price-info {
            font-size: 0.85rem;
        }

        .discount-badge {
            font-size: 0.7rem;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .table-responsive {
            border-radius: 0.5rem;
        }

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
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#produkTable').DataTable({
                responsive: true,
                processing: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
                    '<"row"<"col-sm-12"B>>',
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-1"></i> Excel',
                        className: 'btn btn-success btn-sm me-1',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                        className: 'btn btn-danger btn-sm me-1',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Print',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                order: [
                    [6, 'desc']
                ], // Sort by upload date descending
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                columnDefs: [{
                        targets: [8], // Actions column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [1], // Photo column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [0], // ID column
                        width: "5%"
                    },
                    {
                        targets: [1], // Photo column
                        width: "8%"
                    },
                    {
                        targets: [2], // Name column
                        width: "20%"
                    },
                    {
                        targets: [3], // Category column
                        width: "15%"
                    },
                    {
                        targets: [4], // Price column
                        width: "12%"
                    },
                    {
                        targets: [5], // Status column
                        width: "8%"
                    },
                    {
                        targets: [6], // Date column
                        width: "10%"
                    },
                    {
                        targets: [7], // Stock column
                        width: "8%"
                    },
                    {
                        targets: [8], // Actions column
                        width: "14%"
                    }
                ],
                drawCallback: function(settings) {
                    // Reinitialize tooltips after table redraw
                    $('[title]').tooltip();
                }
            });

            // Initialize tooltips
            $('[title]').tooltip();

            // Handle delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const productName = $(this).data('product-name');
                const deleteUrl = $(this).data('delete-url');

                if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?`)) {
                    // Create and submit delete form
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': deleteUrl
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
            });
        });
    </script>
@endpush
