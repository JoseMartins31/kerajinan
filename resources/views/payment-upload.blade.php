@extends('layouts.frontend')

@section('title', 'Upload Bukti Pembayaran - Kerajinan Indonesia')
@section('description', 'Upload bukti transfer pembayaran untuk konfirmasi pesanan Anda')

@push('styles')
    <style>
        .payment-upload-container {
            background-color: var(--bg-light);
            min-height: 70vh;
            padding: 2rem 0;
        }

        .upload-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .upload-header {
            background: linear-gradient(135deg, var(--accent-color), #e76f51);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--border-color);
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .step.active .step-number {
            background: var(--accent-color);
            color: white;
        }

        .step.completed .step-number {
            background: #28a745;
            color: white;
        }

        .order-info-card {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .order-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .order-total {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            border: 2px dashed var(--primary-color);
            margin: 1rem 0;
        }

        .bank-info-card {
            background: white;
            border-radius: 12px;
            border: 2px solid var(--primary-color);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .bank-account {
            background: var(--bg-light);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .bank-account:last-child {
            margin-bottom: 0;
        }

        .bank-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .account-info {
            color: var(--text-dark);
        }

        .account-number {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .upload-section {
            background: white;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            padding: 2rem;
        }

        .file-upload-area {
            border: 3px dashed var(--border-color);
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover,
        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background-color: rgba(200, 16, 46, 0.05);
        }

        .upload-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .file-input {
            display: none;
        }

        .file-preview {
            max-width: 100%;
            max-height: 400px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin: 1rem 0;
        }

        .upload-requirements {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .upload-requirements ul {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }

        .upload-requirements li {
            margin-bottom: 0.5rem;
            color: #155724;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .instructions {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .instructions-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .order-items {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .item-price {
            color: var(--primary-color);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-direction: column;
                align-items: center;
            }

            .step {
                margin: 0.5rem 0;
            }

            .upload-icon {
                font-size: 3rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="payment-upload-container">
        <div class="container">
            <!-- Page Header -->
            <div class="upload-card">
                <div class="upload-header" data-aos="fade-down">
                    <h2><i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran</h2>
                    <p class="mb-0">Upload bukti transfer untuk konfirmasi pesanan Anda</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator" data-aos="fade-up">
                    <div class="step completed">
                        <div class="step-number"><i class="fas fa-check"></i></div>
                        <span>Review Keranjang</span>
                    </div>
                    <div class="step completed">
                        <div class="step-number"><i class="fas fa-check"></i></div>
                        <span>Pembayaran</span>
                    </div>
                    <div class="step active">
                        <div class="step-number">3</div>
                        <span>Transfer Bank</span>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <span>Konfirmasi</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Information -->
                    <div class="order-info-card" data-aos="fade-right">
                        <h4><i class="fas fa-receipt me-2"></i>Informasi Pesanan</h4>
                        <div class="order-number">Pesanan #{{ $pesanan->idPesanan }}</div>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Tanggal Pesanan:</small><br>
                                <strong>{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Status:</small><br>
                                <span class="badge bg-warning">Menunggu Pembayaran</span>
                            </div>
                        </div>
                        <div class="order-total">
                            Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Bank Account Information -->
                    <div class="bank-info-card" data-aos="fade-right" data-aos-delay="100">
                        <h4><i class="fas fa-university me-2"></i>Informasi Rekening Bank</h4>
                        <p class="text-muted mb-3">Silakan transfer ke salah satu rekening berikut:</p>

                        <div class="bank-account">
                            <div class="bank-name">Bank BCA</div>
                            <div class="account-info">
                                <div class="account-number">1234567890</div>
                                <div>a/n PT Kerajinan Indonesia</div>
                            </div>
                        </div>

                        <div class="bank-account">
                            <div class="bank-name">Bank Mandiri</div>
                            <div class="account-info">
                                <div class="account-number">0987654321</div>
                                <div>a/n PT Kerajinan Indonesia</div>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Instructions -->
                    <div class="instructions" data-aos="fade-right" data-aos-delay="200">
                        <div class="instructions-title">
                            <i class="fas fa-info-circle me-2"></i>Petunjuk Transfer
                        </div>
                        <ol class="mb-0">
                            <li>Transfer sesuai dengan <strong>jumlah total yang tertera</strong></li>
                            <li>Simpan bukti transfer dari bank/ATM/mobile banking</li>
                            <li>Upload foto bukti transfer dengan jelas</li>
                            <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                        </ol>
                    </div>

                    <!-- File Upload Section -->
                    <div class="upload-section" data-aos="fade-right" data-aos-delay="300">
                        <h4><i class="fas fa-camera me-2"></i>Upload Bukti Transfer</h4>

                        <form action="{{ route('payment.store', $pesanan->idPesanan) }}" method="POST"
                            enctype="multipart/form-data" id="uploadForm">
                            @csrf

                            <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h5>Klik untuk pilih file atau drag & drop</h5>
                                <p class="text-muted mb-0">Format yang didukung: JPG, PNG, JPEG (Maksimal 2MB)</p>

                                <input type="file" id="fileInput" name="bukti_pembayaran" class="file-input"
                                    accept="image/*" required>

                                <div id="filePreview" style="display: none; margin-top: 1rem;">
                                    <img id="previewImage" class="file-preview" alt="Preview">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                            <i class="fas fa-times me-1"></i>Hapus File
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @error('bukti_pembayaran')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror

                            <div class="upload-requirements">
                                <strong><i class="fas fa-check-circle me-2"></i>Persyaratan Upload:</strong>
                                <ul>
                                    <li>File harus berupa gambar (JPG, PNG, JPEG)</li>
                                    <li>Ukuran maksimal 2MB</li>
                                    <li>Bukti transfer harus jelas dan dapat dibaca</li>
                                    <li>Pastikan jumlah transfer sesuai dengan total pesanan</li>
                                </ul>
                            </div>

                            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                                <i class="fas fa-upload me-2"></i>Upload Bukti Transfer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Items Summary -->
                <div class="col-lg-4">
                    <div class="order-items" data-aos="fade-left">
                        <div class="p-3 border-bottom">
                            <h5><i class="fas fa-shopping-bag me-2"></i>Item Pesanan</h5>
                        </div>

                        @foreach ($pesanan->detailPesanan as $detail)
                            <div class="order-item">
                                @if ($detail->produk->foto)
                                    <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                        alt="{{ $detail->produk->nama_produk }}" class="item-image">
                                @else
                                    <div class="item-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="item-info">
                                    <div class="item-name">{{ $detail->produk->nama_produk }}</div>
                                    <div class="text-muted small">Jumlah: {{ $detail->jumlah }}</div>
                                    <div class="item-price">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach

                        <div class="p-3 border-top bg-light">
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong class="text-primary">Rp
                                    {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-3 p-3 bg-white rounded border">
                        <h6><i class="fas fa-question-circle me-2"></i>Butuh Bantuan?</h6>
                        <p class="small text-muted mb-2">
                            Jika Anda mengalami kesulitan dalam upload bukti pembayaran,
                            hubungi customer service kami.
                        </p>
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone me-1"></i>Hubungi CS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const previewImage = document.getElementById('previewImage');
            const submitBtn = document.getElementById('submitBtn');
            const uploadArea = document.querySelector('.file-upload-area');

            // File input change handler
            fileInput.addEventListener('change', function(e) {
                handleFileSelect(e.target.files[0]);
            });

            // Drag and drop handlers
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            function handleFileSelect(file) {
                if (!file) return;

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    toastr.error('Tipe file tidak didukung. Gunakan JPG, PNG, atau JPEG.', 'Error');
                    fileInput.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    toastr.error('Ukuran file terlalu besar. Maksimal 2MB.', 'Error');
                    fileInput.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    filePreview.style.display = 'block';
                    submitBtn.disabled = false;
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }

            // Form submission
            $('#uploadForm').on('submit', function(e) {
                if (!fileInput.files[0]) {
                    e.preventDefault();
                    toastr.error('Harap pilih file bukti pembayaran terlebih dahulu.', 'Error');
                    return;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
                submitBtn.disabled = true;
            });
        });

        function removeFile() {
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const submitBtn = document.getElementById('submitBtn');
            const uploadArea = document.querySelector('.file-upload-area');

            fileInput.value = '';
            filePreview.style.display = 'none';
            uploadArea.style.display = 'block';
            submitBtn.disabled = true;
        }

        // Prevent accidental page leave
        let formSubmitted = false;
        $('#uploadForm').on('submit', function() {
            formSubmitted = true;
        });

        $(window).on('beforeunload', function(e) {
            if (!formSubmitted && document.getElementById('fileInput').files.length > 0) {
                return 'Anda memiliki file yang belum diupload. Apakah Anda yakin ingin meninggalkan halaman?';
            }
        });
    </script>
@endpush
