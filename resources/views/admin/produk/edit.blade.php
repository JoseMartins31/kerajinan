@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-description', 'Ubah informasi produk kerajinan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.produk.show', $produk->idProduk) }}">{{ Str::limit($produk->nama_produk, 30) }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.produk.show', $produk->idProduk) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>Daftar Produk
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.produk.update', $produk->idProduk) }}" method="POST"
                enctype="multipart/form-data" id="productEditForm">
                @csrf
                @method('PUT')

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
                                        id="nama_produk" name="nama_produk"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}"
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
                                        placeholder="Deskripsikan produk Anda secara detail..." required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
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
                                                {{ old('idKategori', $produk->idKategori) == $kat->idKategori ? 'selected' : '' }}>
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
                                            id="Harga" name="Harga" value="{{ old('Harga', $produk->Harga) }}"
                                            min="0" step="1000" required>
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
                                        id="stok" name="stok" value="{{ old('stok', $produk->stok ?? 1) }}"
                                        min="0">
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Jumlah stok yang tersedia untuk dijual
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Change Tracking -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>Pelacakan Perubahan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="changeTracker" class="alert alert-info" style="display: none;">
                                    <h6><i class="fas fa-info-circle me-2"></i>Perubahan yang Terdeteksi:</h6>
                                    <ul id="changesList"></ul>
                                </div>
                                <small class="text-muted">
                                    Perubahan akan ditampilkan di sini saat Anda memodifikasi data
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Current Photo -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-camera me-2"></i>Foto Produk
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- Current Image -->
                                @if ($produk->foto)
                                    <div class="current-image mb-3">
                                        <label class="form-label">Foto Saat Ini:</label>
                                        <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk }}"
                                            class="img-fluid rounded shadow"
                                            style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                @endif

                                <!-- New Photo Upload -->
                                <div class="mb-3">
                                    <label for="foto" class="form-label">
                                        {{ $produk->foto ? 'Ganti Foto' : 'Upload Foto' }}
                                    </label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                        id="foto" name="foto" accept="image/*">
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, GIF. Maksimal 2MB
                                        @if ($produk->foto)
                                            <br><strong>Catatan:</strong> Foto lama akan diganti jika Anda upload foto baru
                                        @endif
                                    </small>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="text-center" style="display: none;">
                                    <label class="form-label">Preview Foto Baru:</label>
                                    <img id="previewImage" src="" alt="Preview"
                                        class="img-fluid rounded shadow"
                                        style="max-height: 200px; width: 100%; object-fit: cover;">
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
                                        <option value="aktif"
                                            {{ old('status', $produk->status) == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ old('status', $produk->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                            Aktif</option>
                                        <option value="draft"
                                            {{ old('status', $produk->status) == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- User (Hidden, maintain current user) -->
                                <input type="hidden" name="idUser" value="{{ $produk->idUser }}">

                                <!-- Original Data for Change Tracking -->
                                <input type="hidden" id="original_nama_produk" value="{{ $produk->nama_produk }}">
                                <input type="hidden" id="original_deskripsi" value="{{ $produk->deskripsi }}">
                                <input type="hidden" id="original_Harga" value="{{ $produk->Harga }}">
                                <input type="hidden" id="original_stok" value="{{ $produk->stok }}">
                                <input type="hidden" id="original_status" value="{{ $produk->status }}">
                                <input type="hidden" id="original_idKategori" value="{{ $produk->idKategori }}">
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info me-2"></i>Info Produk
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-hashtag text-primary me-2"></i>
                                        <strong>ID:</strong> {{ $produk->idProduk }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong>Dibuat:</strong>
                                        {{ $produk->tanggal_upload ? $produk->tanggal_upload->format('d/m/Y H:i') : '-' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <strong>Oleh:</strong> {{ $produk->user->name ?? 'Unknown' }}
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-eye text-primary me-2"></i>
                                        <strong>Penjualan:</strong> {{ $produk->detailPesanan->sum('jumlah') ?? 0 }}
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
                                    <div>
                                        <a href="{{ route('admin.produk.show', $produk->idProduk) }}"
                                            class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Batal
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-warning me-2"
                                            onclick="resetForm()">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
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

        #imagePreview img,
        .current-image img {
            border: 3px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-text {
            font-size: 0.875rem;
        }

        #changesList {
            margin-bottom: 0;
        }

        .change-item {
            padding: 0.25rem 0;
            border-left: 3px solid #ffc107;
            padding-left: 0.75rem;
            margin-bottom: 0.5rem;
            background-color: rgba(255, 193, 7, 0.1);
            border-radius: 0 0.25rem 0.25rem 0;
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

            // Change tracking
            const originalValues = {
                nama_produk: $('#original_nama_produk').val(),
                deskripsi: $('#original_deskripsi').val(),
                Harga: $('#original_Harga').val(),
                stok: $('#original_stok').val(),
                status: $('#original_status').val(),
                idKategori: $('#original_idKategori').val()
            };

            function trackChanges() {
                const changes = [];

                // Check each field for changes
                if ($('#nama_produk').val() !== originalValues.nama_produk) {
                    changes.push(`Nama produk: "${originalValues.nama_produk}" → "${$('#nama_produk').val()}"`);
                }

                if ($('#deskripsi').val() !== originalValues.deskripsi) {
                    changes.push(`Deskripsi diubah`);
                }

                if ($('#Harga').val() !== originalValues.Harga) {
                    const oldPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(originalValues.Harga);
                    const newPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format($('#Harga').val());
                    changes.push(`Harga: ${oldPrice} → ${newPrice}`);
                }

                if ($('#stok').val() !== originalValues.stok) {
                    changes.push(`Stok: ${originalValues.stok} → ${$('#stok').val()}`);
                }

                if ($('#status').val() !== originalValues.status) {
                    changes.push(`Status: "${originalValues.status}" → "${$('#status').val()}"`);
                }

                if ($('#idKategori').val() !== originalValues.idKategori) {
                    const oldCategory = $('#idKategori option').filter(function() {
                        return this.value == originalValues.idKategori;
                    }).text();
                    const newCategory = $('#idKategori option:selected').text();
                    changes.push(`Kategori: "${oldCategory}" → "${newCategory}"`);
                }

                if ($('#foto').get(0).files.length > 0) {
                    changes.push(`Foto produk akan diganti`);
                }

                // Update change tracker
                if (changes.length > 0) {
                    $('#changesList').html('');
                    changes.forEach(function(change) {
                        $('#changesList').append(`<li class="change-item">${change}</li>`);
                    });
                    $('#changeTracker').show();
                } else {
                    $('#changeTracker').hide();
                }
            }

            // Track changes on input
            $('input, select, textarea').on('input change', trackChanges);

            // Reset form function
            window.resetForm = function() {
                if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                    // Reset to original values
                    $('#nama_produk').val(originalValues.nama_produk);
                    $('#deskripsi').val(originalValues.deskripsi);
                    $('#Harga').val(originalValues.Harga);
                    $('#stok').val(originalValues.stok);
                    $('#status').val(originalValues.status);
                    $('#idKategori').val(originalValues.idKategori);
                    $('#foto').val('');
                    $('#imagePreview').hide();

                    // Clear validation errors
                    $('.form-control, .form-select').removeClass('is-invalid');

                    // Update change tracker
                    trackChanges();
                }
            };

            // Form validation
            $('#productEditForm').on('submit', function(e) {
                let isValid = true;
                const requiredFields = ['nama_produk', 'deskripsi', 'Harga', 'status', 'idKategori'];

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
                    return false;
                }

                // Show confirmation if there are changes
                const changesList = $('#changesList li').length;
                if (changesList > 0) {
                    if (!confirm(`Anda akan menyimpan ${changesList} perubahan. Lanjutkan?`)) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Remove validation error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });

            // Initial change tracking
            trackChanges();
        });
    </script>
@endpush
