@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('page-description', 'Buat produk kerajinan tangan baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
    <li class="breadcrumb-item active">Tambah Produk</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf

                <div class="row">
                    <!-- Main Form -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Produk
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- Nama Produk -->
                                <div class="mb-3">
                                    <label for="nama_produk" class="form-label">
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                        id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}"
                                        placeholder="Contoh: Vas Keramik Motif Batik" required>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Berikan nama yang jelas dan menarik untuk produk Anda
                                    </small>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">
                                        Deskripsi Produk <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5"
                                        placeholder="Deskripsikan produk Anda secara detail..." required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Jelaskan bahan, ukuran, cara perawatan, dan keunikan produk
                                    </small>
                                </div>

                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label for="idKategori" class="form-label">
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('idKategori') is-invalid @enderror" id="idKategori"
                                        name="idKategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->idKategori }}"
                                                {{ old('idKategori') == $kat->idKategori ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idKategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Harga -->
                                <div class="mb-3">
                                    <label for="Harga" class="form-label">
                                        Harga <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('Harga') is-invalid @enderror"
                                            id="Harga" name="Harga" value="{{ old('Harga') }}" min="0"
                                            step="1000" placeholder="250000" required>
                                        @error('Harga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Masukkan harga dalam Rupiah (tanpa titik atau koma)
                                    </small>
                                </div>

                                <!-- Stok -->
                                <div class="mb-3">
                                    <label for="stok" class="form-label">
                                        Stok Produk
                                    </label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                        id="stok" name="stok" value="{{ old('stok', 1) }}" min="0"
                                        placeholder="10">
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Jumlah stok yang tersedia untuk dijual
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Photo Upload -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-camera me-2"></i>Foto Produk
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">
                                        Upload Foto <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                        id="foto" name="foto" accept="image/*" required>
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, GIF. Maksimal 2MB
                                    </small>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="text-center" style="display: none;">
                                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                    <div class="mt-2">
                                        <small class="text-muted">Preview Foto</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Settings -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-cog me-2"></i>Pengaturan
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        Status Produk <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- User (Hidden, set to current admin) -->
                                <input type="hidden" name="idUser" value="{{ Auth::id() }}">
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>Tips
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Gunakan foto berkualitas tinggi
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Tulis deskripsi yang detail
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pilih kategori yang tepat
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Tentukan harga yang kompetitif
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pastikan stok tersedia
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        #imagePreview img {
            border: 3px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-text {
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Image preview functionality
            $('#foto').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                        $('#imagePreview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').hide();
                }
            });

            // Form validation
            $('#productForm').on('submit', function(e) {
                let isValid = true;
                const requiredFields = ['nama_produk', 'deskripsi', 'Harga', 'foto', 'status',
                'idKategori'];

                requiredFields.forEach(function(field) {
                    const input = $(`[name="${field}"]`);
                    if (!input.val()) {
                        input.addClass('is-invalid');
                        isValid = false;
                    } else {
                        input.removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                }
            });

            // Remove validation error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endpush
