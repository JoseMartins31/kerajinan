@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('page-description', 'Perbarui informasi kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.kategori.show', $kategori->idKategori) }}">{{ $kategori->nama_kategori }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('admin.kategori.show', $kategori->idKategori) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>Daftar Kategori
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Kategori: {{ $kategori->nama_kategori }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.update', $kategori->idKategori) }}" method="POST"
                        id="editCategoryForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nama_kategori" class="form-label required">
                                        <i class="fas fa-tag me-2"></i>Nama Kategori
                                    </label>
                                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                        id="nama_kategori" name="nama_kategori"
                                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                        placeholder="Masukkan nama kategori" maxlength="50" required>
                                    <div class="form-text">
                                        <small class="text-muted">Maksimal 50 karakter</small>
                                        <span id="charCount"
                                            class="float-end">{{ strlen($kategori->nama_kategori) }}/50</span>
                                    </div>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="deskripsi" class="form-label required">
                                        <i class="fas fa-align-left me-2"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5"
                                        placeholder="Masukkan deskripsi kategori..." required>{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                    <div class="form-text">
                                        <small class="text-muted">Jelaskan jenis produk yang masuk dalam kategori
                                            ini</small>
                                    </div>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light border-dashed mb-4" id="previewCard">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted">
                                            <i class="fas fa-eye me-2"></i>Preview Kategori
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon me-3">
                                                <i class="fas fa-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1" id="previewNama">{{ $kategori->nama_kategori }}</h6>
                                                <p class="text-muted mb-0" id="previewDeskripsi">{{ $kategori->deskripsi }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.kategori.show', $kategori->idKategori) }}"
                                        class="btn btn-light">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" id="resetBtn">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category Info Sidebar -->
        <div class="col-lg-4">
            <!-- Current Category Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="category-icon-large mx-auto mb-2">
                            <i class="fas fa-tag fa-2x text-primary"></i>
                        </div>
                        <h6 class="mb-1">{{ $kategori->nama_kategori }}</h6>
                        <small class="text-muted">ID: {{ $kategori->idKategori }}</small>
                    </div>

                    <div class="info-items">
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Jumlah Produk:</span>
                            <span class="fw-bold">{{ $kategori->produk->count() }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Dibuat:</span>
                            <span class="fw-bold">{{ $kategori->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <span class="text-muted">Diperbarui:</span>
                            <span class="fw-bold">{{ $kategori->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change History -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Perubahan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Kategori Dibuat</h6>
                                <small class="text-muted">{{ $kategori->created_at->format('d F Y, H:i') }} WIB</small>
                            </div>
                        </div>
                        @if ($kategori->updated_at != $kategori->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Diperbarui</h6>
                                    <small class="text-muted">{{ $kategori->updated_at->format('d F Y, H:i') }}
                                        WIB</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Associated Products -->
            @if ($kategori->produk->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-box me-2"></i>Produk Terkait ({{ $kategori->produk->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="product-list">
                            @foreach ($kategori->produk->take(5) as $produk)
                                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                    <div class="product-thumb me-2">
                                        @if ($produk->foto)
                                            <img src="{{ asset('storage/' . $produk->foto) }}"
                                                alt="{{ $produk->nama_produk }}" class="rounded" width="30"
                                                height="30" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 30px; height: 30px;">
                                                <i class="fas fa-image text-muted small"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small">{{ Str::limit($produk->nama_produk, 20) }}</h6>
                                        <small class="text-muted">Rp
                                            {{ number_format($produk->Harga, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach

                            @if ($kategori->produk->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('admin.kategori.show', $kategori->idKategori) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Lihat {{ $kategori->produk->count() - 5 }} produk lainnya
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Warning for Products -->
            @if ($kategori->produk->count() > 0)
                <div class="card mt-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Perhatian
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 small">
                            Kategori ini memiliki <strong>{{ $kategori->produk->count() }} produk</strong>.
                            Perubahan nama kategori akan mempengaruhi tampilan di semua produk terkait.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .required::after {
            content: ' *';
            color: #dc3545;
        }

        .category-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-icon-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .border-dashed {
            border: 2px dashed #dee2e6 !important;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.75rem;
            top: 0.5rem;
            bottom: -1rem;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: -1.875rem;
            top: 0.375rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .info-item {
            font-size: 0.875rem;
        }

        .product-thumb img {
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        #charCount {
            font-size: 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.getElementById('nama_kategori');
            const deskripsiInput = document.getElementById('deskripsi');
            const charCount = document.getElementById('charCount');
            const previewNama = document.getElementById('previewNama');
            const previewDeskripsi = document.getElementById('previewDeskripsi');
            const resetBtn = document.getElementById('resetBtn');

            // Store original values
            const originalNama = "{{ $kategori->nama_kategori }}";
            const originalDeskripsi = `{{ $kategori->deskripsi }}`;

            // Character counter
            namaInput.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = `${length}/50`;

                if (length > 40) {
                    charCount.classList.add('text-warning');
                } else {
                    charCount.classList.remove('text-warning');
                }

                if (length >= 50) {
                    charCount.classList.remove('text-warning');
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }

                // Update preview
                previewNama.textContent = this.value || originalNama;
            });

            // Update preview for description
            deskripsiInput.addEventListener('input', function() {
                previewDeskripsi.textContent = this.value || originalDeskripsi;
            });

            // Reset form
            resetBtn.addEventListener('click', function() {
                namaInput.value = originalNama;
                deskripsiInput.value = originalDeskripsi;
                previewNama.textContent = originalNama;
                previewDeskripsi.textContent = originalDeskripsi;

                // Reset character counter
                const length = originalNama.length;
                charCount.textContent = `${length}/50`;
                charCount.classList.remove('text-warning', 'text-danger');

                // Remove validation classes
                namaInput.classList.remove('is-invalid', 'is-valid');
                deskripsiInput.classList.remove('is-invalid', 'is-valid');
            });

            // Form validation
            document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');

                if (this.checkValidity()) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                }
            });

            // Initialize character counter
            namaInput.dispatchEvent(new Event('input'));
        });
    </script>
@endpush
