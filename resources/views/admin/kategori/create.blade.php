@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')
@section('page-description', 'Buat kategori baru untuk produk kerajinan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Formulir Kategori Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.store') }}" method="POST" id="createCategoryForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nama_kategori" class="form-label required">
                                        <i class="fas fa-tag me-2"></i>Nama Kategori
                                    </label>
                                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                        id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}"
                                        placeholder="Masukkan nama kategori" maxlength="50" required>
                                    <div class="form-text">
                                        <small class="text-muted">Maksimal 50 karakter</small>
                                        <span id="charCount" class="float-end">0/50</span>
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
                                        placeholder="Masukkan deskripsi kategori..." required>{{ old('deskripsi') }}</textarea>
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
                                <div class="card bg-light border-dashed" id="previewCard" style="display: none;">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted">
                                            <i class="fas fa-eye me-2"></i>Preview Kategori
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon me-3">
                                                <i class="fas fa-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1" id="previewNama">-</h6>
                                                <p class="text-muted mb-0" id="previewDeskripsi">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-light">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                            <i class="fas fa-eye me-2"></i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Simpan Kategori
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Tips Membuat Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Nama yang Jelas:</strong> Gunakan nama yang mudah dipahami dan spesifik
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Deskripsi Detail:</strong> Jelaskan jenis produk yang masuk dalam kategori ini
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Hindari Duplikasi:</strong> Pastikan kategori belum ada sebelumnya
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Relevan:</strong> Pilih nama yang sesuai dengan jenis kerajinan Anda
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Examples Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Contoh Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="example-categories">
                        <div class="example-item mb-3 p-3 border rounded">
                            <h6 class="mb-1">Keramik</h6>
                            <small class="text-muted">Produk berbahan dasar tanah liat seperti vas, piring, dan
                                mangkuk</small>
                        </div>
                        <div class="example-item mb-3 p-3 border rounded">
                            <h6 class="mb-1">Tekstil</h6>
                            <small class="text-muted">Produk kain tradisional seperti batik, tenun, dan sulaman</small>
                        </div>
                        <div class="example-item mb-3 p-3 border rounded">
                            <h6 class="mb-1">Perhiasan</h6>
                            <small class="text-muted">Aksesoris dan perhiasan tradisional dari berbagai bahan</small>
                        </div>
                        <div class="example-item mb-3 p-3 border rounded">
                            <h6 class="mb-1">Ukiran</h6>
                            <small class="text-muted">Karya seni ukir dari kayu, batu, atau bahan lainnya</small>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Klik untuk menggunakan sebagai template</small>
                    </div>
                </div>
            </div>
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

        .border-dashed {
            border: 2px dashed #dee2e6 !important;
        }

        .example-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .example-item:hover {
            background-color: #f8f9fa;
            border-color: #007bff !important;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
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
            const previewCard = document.getElementById('previewCard');
            const previewBtn = document.getElementById('previewBtn');
            const previewNama = document.getElementById('previewNama');
            const previewDeskripsi = document.getElementById('previewDeskripsi');

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
            });

            // Preview functionality
            previewBtn.addEventListener('click', function() {
                const nama = namaInput.value.trim();
                const deskripsi = deskripsiInput.value.trim();

                if (nama && deskripsi) {
                    previewNama.textContent = nama;
                    previewDeskripsi.textContent = deskripsi;
                    previewCard.style.display = 'block';
                    previewCard.scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    alert('Mohon isi semua field untuk melihat preview');
                }
            });

            // Example category click handlers
            document.querySelectorAll('.example-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    const nama = this.querySelector('h6').textContent;
                    const deskripsi = this.querySelector('small').textContent;

                    namaInput.value = nama;
                    deskripsiInput.value = deskripsi;

                    // Trigger character counter update
                    namaInput.dispatchEvent(new Event('input'));

                    // Highlight the form
                    document.querySelector('.card-body').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Form validation
            document.getElementById('createCategoryForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');

                if (this.checkValidity()) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                }
            });
        });
    </script>
@endpush
