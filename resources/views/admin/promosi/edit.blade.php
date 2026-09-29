@extends('layouts.admin')

@section('title', 'Edit Promotion')
@section('page-title', 'Edit Promosi')
@section('page-description', 'Update informasi promosi dan diskon produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promosi.index') }}">Promosi</a></li>
    <li class="breadcrumb-item active">Edit Promosi</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.promosi.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-form me-2"></i>Promotion Details
                    </h5>
                    <div>
                        <span class="badge {{ $promosi->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $promosi->status === 'aktif' ? 'Active' : 'Inactive' }}
                        </span>
                        @if ($promosi->tanggal_berakhir < now())
                            <span class="badge bg-danger ms-1">Expired</span>
                        @elseif($promosi->tanggal_mulai > now())
                            <span class="badge bg-primary ms-1">Scheduled</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promosi.update', $promosi->idPromosi) }}" method="POST"
                        id="promotionForm">
                        @csrf
                        @method('PUT')

                        <!-- Current Product Info -->
                        <div class="alert alert-info mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if ($promosi->produk->foto)
                                        <img src="{{ asset('storage/' . $promosi->produk->foto) }}"
                                            alt="{{ $promosi->produk->nama_produk }}" class="img-fluid rounded"
                                            style="max-height: 80px;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <h6 class="mb-1">Currently Applied To:</h6>
                                    <h5 class="text-primary mb-1">{{ $promosi->produk->nama_produk }}</h5>
                                    <p class="mb-1">
                                        <span
                                            class="badge bg-secondary">{{ $promosi->produk->kategori->nama_kategori ?? 'No Category' }}</span>
                                        <span class="ms-2 text-muted">Original Price: Rp
                                            {{ number_format($promosi->produk->Harga) }}</span>
                                    </p>
                                    <p class="mb-0 text-success">
                                        <strong>Current Discounted Price: Rp
                                            {{ number_format($promosi->produk->Harga * (1 - $promosi->diskon / 100)) }}</strong>
                                        <span class="badge bg-success ms-2">{{ $promosi->diskon }}% OFF</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label for="idProduk" class="form-label">
                                <i class="fas fa-box me-1"></i>Select Product *
                            </label>
                            <select name="idProduk" id="idProduk" class="form-select select2" required>
                                @foreach ($produk as $product)
                                    <option value="{{ $product->idProduk }}" data-price="{{ $product->Harga }}"
                                        {{ $promosi->idProduk == $product->idProduk ? 'selected' : '' }}>
                                        {{ $product->nama_produk }} - Rp {{ number_format($product->Harga) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idProduk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>Start Date & Time *
                                    </label>
                                    <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai"
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($promosi->tanggal_mulai)->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_berakhir" class="form-label">
                                        <i class="fas fa-calendar-check me-1"></i>End Date & Time *
                                    </label>
                                    <input type="datetime-local" name="tanggal_berakhir" id="tanggal_berakhir"
                                        class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                                        value="{{ old('tanggal_berakhir', \Carbon\Carbon::parse($promosi->tanggal_berakhir)->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('tanggal_berakhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Discount and Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="diskon" class="form-label">
                                        <i class="fas fa-percent me-1"></i>Discount Percentage *
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="diskon" id="diskon"
                                            class="form-control @error('diskon') is-invalid @enderror" min="0"
                                            max="100" step="0.01" value="{{ old('diskon', $promosi->diskon) }}"
                                            required>
                                        <span class="input-group-text">%</span>
                                        @error('diskon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Status *
                                    </label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ $promosi->status === 'aktif' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ $promosi->status === 'tidak_aktif' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.promosi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            <div>
                                <a href="{{ route('admin.promosi.show', $promosi->idPromosi) }}"
                                    class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                <button type="button" class="btn btn-warning me-2" onclick="previewPromotion()">
                                    <i class="fas fa-search me-1"></i>Preview Changes
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Promotion
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Sidebar -->
        <div class="col-lg-4">
            <!-- Current Promotion Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Current Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-calendar-alt text-primary fa-2x mb-2"></i>
                                <h6 class="mb-1">Started</h6>
                                <small>{{ \Carbon\Carbon::parse($promosi->tanggal_mulai)->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-calendar-check text-warning fa-2x mb-2"></i>
                                <h6 class="mb-1">Ends</h6>
                                <small>{{ \Carbon\Carbon::parse($promosi->tanggal_berakhir)->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="mb-2">
                            @if ($promosi->tanggal_berakhir < now())
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-clock me-1"></i>Expired
                                </span>
                            @elseif($promosi->tanggal_mulai > now())
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-clock me-1"></i>Scheduled
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-play me-1"></i>Running
                                </span>
                            @endif
                        </div>
                        <p class="mb-0 text-muted">
                            {{ \Carbon\Carbon::parse($promosi->tanggal_berakhir)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card mt-4" id="previewCard" style="display: none;">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Preview Changes
                    </h5>
                </div>
                <div class="card-body" id="promotionPreview">
                    <!-- Preview content will be populated by JavaScript -->
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.promosi.toggle', $promosi->idPromosi) }}"
                            class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn btn-{{ $promosi->status === 'aktif' ? 'secondary' : 'success' }} w-100">
                                <i class="fas fa-{{ $promosi->status === 'aktif' ? 'pause' : 'play' }} me-1"></i>
                                {{ $promosi->status === 'aktif' ? 'Deactivate' : 'Activate' }} Promotion
                            </button>
                        </form>

                        <button class="btn btn-danger" onclick="deletePromotion({{ $promosi->idPromosi }})">
                            <i class="fas fa-trash me-1"></i>Delete Promotion
                        </button>

                        <button class="btn btn-info" onclick="duplicatePromotion()">
                            <i class="fas fa-copy me-1"></i>Duplicate Promotion
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form validation and preview updates
            $('#idProduk, #diskon, #tanggal_mulai, #tanggal_berakhir, #status').change(function() {
                updatePreview();
            });

            // Form submission
            $('#promotionForm').submit(function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
                submitBtn.prop('disabled', true);
            });

            // Initialize preview with current values
            updatePreview();
        });

        function updatePreview() {
            const productSelect = document.getElementById('idProduk');
            const discountInput = document.getElementById('diskon');
            const startDateInput = document.getElementById('tanggal_mulai');
            const endDateInput = document.getElementById('tanggal_berakhir');
            const statusSelect = document.getElementById('status');

            if (!productSelect.value || !discountInput.value) {
                return;
            }

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productName = selectedOption.text.split(' - ')[0];
            const originalPrice = parseFloat(selectedOption.dataset.price);
            const discount = parseFloat(discountInput.value);
            const discountAmount = originalPrice * (discount / 100);
            const finalPrice = originalPrice - discountAmount;

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            const previewHtml = `
            <div class="text-center mb-3">
                <h6 class="text-primary">${productName}</h6>
                <div class="mb-2">
                    <span class="text-decoration-line-through text-muted">Rp ${originalPrice.toLocaleString('id-ID')}</span>
                    <span class="badge bg-danger ms-2">${discount}% OFF</span>
                </div>
                <h4 class="text-success mb-0">Rp ${finalPrice.toLocaleString('id-ID')}</h4>
                <small class="text-muted">You save Rp ${discountAmount.toLocaleString('id-ID')}</small>
            </div>

            <hr>

            <div class="row text-center">
                <div class="col-6">
                    <small class="text-muted d-block">Starts</small>
                    <strong>${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</strong>
                    <small class="d-block">${startDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</small>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Ends</small>
                    <strong>${endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</strong>
                    <small class="d-block">${endDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</small>
                </div>
            </div>

            <hr>

            <div class="text-center">
                <span class="badge ${statusSelect.value === 'aktif' ? 'bg-success' : 'bg-secondary'}">
                    ${statusSelect.value === 'aktif' ? 'Active' : 'Inactive'}
                </span>
            </div>
        `;

            document.getElementById('promotionPreview').innerHTML = previewHtml;
        }

        function previewPromotion() {
            updatePreview();
            $('#previewCard').show();
            $('html, body').animate({
                scrollTop: $('#previewCard').offset().top - 100
            }, 500);
        }

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
                text: "This will create a new promotion with the same settings. You can modify it afterwards.",
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
