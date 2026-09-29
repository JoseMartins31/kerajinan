@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')
@section('page-description', 'Kelola kategori produk kerajinan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>Daftar Kategori
                    </h6>
                    <div class="card-tools">
                        <a href="{{ route('admin.kategori.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="kategoriesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Nama Kategori</th>
                                    <th width="40%">Deskripsi</th>
                                    <th width="15%">Jumlah Produk</th>
                                    <th width="15%">Tanggal Dibuat</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $item->idKategori }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="category-icon me-2">
                                                    <i class="fas fa-tag text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $item->nama_kategori }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ Str::limit($item->deskripsi, 80) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-box text-info me-2"></i>
                                                <span class="badge bg-info">{{ $item->produk->count() ?? 0 }} produk</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $item->created_at->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.kategori.show', $item->idKategori) }}"
                                                    class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.kategori.edit', $item->idKategori) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    title="Hapus" data-category-name="{{ $item->nama_kategori }}"
                                                    data-delete-url="{{ route('admin.kategori.destroy', $item->idKategori) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $item->idKategori }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus kategori
                                                                <strong>"{{ $item->nama_kategori }}"</strong>?
                                                            </p>
                                                            @if ($item->produk->count() > 0)
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    <strong>Peringatan:</strong> Kategori ini memiliki
                                                                    {{ $item->produk->count() }} produk.
                                                                    Menghapus kategori akan mempengaruhi produk-produk
                                                                    tersebut.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('admin.kategori.destroy', $item->idKategori) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Kategori</h5>
                                                <p class="text-muted">Mulai dengan membuat kategori pertama Anda.</p>
                                                <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $kategori->count() }} kategori
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Klik pada nama kategori untuk melihat detail
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $kategori->count() }}</h4>
                            <p class="mb-0">Total Kategori</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $kategori->sum(function ($k) {return $k->produk->count();}) }}</h4>
                            <p class="mb-0">Total Produk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $kategori->where('produk', '>', 0)->count() }}</h4>
                            <p class="mb-0">Kategori Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .category-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state {
            padding: 2rem 0;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .table th {
            background-color: var(--bs-dark);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
        }

        .table td {
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.025);
        }

        .badge {
            font-size: 0.775rem;
            padding: 0.375rem 0.75rem;
        }

        .modal-content {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #664d03;
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
            $('#kategoriesTable').DataTable({
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
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                        className: 'btn btn-danger btn-sm me-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Print',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ], // Sort by creation date descending
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                columnDefs: [{
                        targets: [5], // Actions column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [0], // ID column
                        width: "5%"
                    },
                    {
                        targets: [1], // Name column
                        width: "20%"
                    },
                    {
                        targets: [2], // Description column
                        width: "40%"
                    },
                    {
                        targets: [3], // Product count column
                        width: "15%"
                    },
                    {
                        targets: [4], // Date column
                        width: "15%"
                    },
                    {
                        targets: [5], // Actions column
                        width: "10%"
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
                const categoryName = $(this).data('category-name');
                const deleteUrl = $(this).data('delete-url');

                if (confirm(`Apakah Anda yakin ingin menghapus kategori "${categoryName}"?`)) {
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
