@extends('layouts.admin')

@section('title', 'Create Promotion')
@section('page-title', 'Tambah Promosi Baru')
@section('page-description', 'Buat promosi dan diskon untuk produk kerajinan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promosi.index') }}">Promosi</a></li>
    <li class="breadcrumb-item active">Tambah Promosi</li>
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
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-form me-2"></i>Promotion Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promosi.store') }}" method="POST" id="promotionForm">
                        @csrf

                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label for="product_search" class="form-label">
                                <i class="fas fa-box me-1"></i>Cari Produk *
                            </label>
                            <div class="position-relative">
                                <input type="text" id="product_search" class="form-control"
                                    placeholder="Ketik nama produk untuk mencari..." autocomplete="off">
                                <div id="product_dropdown" class="dropdown-menu w-100"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <!-- Search results will appear here -->
                                </div>
                            </div>
                            <input type="hidden" name="idProduk" id="idProduk" required>
                            @error('idProduk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Selected Product Display -->
                            <div id="selected_product" class="mt-3" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img id="selected_product_image" src="" alt=""
                                                    class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                            <div class="col">
                                                <h6 id="selected_product_name" class="mb-1"></h6>
                                                <small id="selected_product_category" class="text-muted d-block"></small>
                                                <strong id="selected_product_price" class="text-success"></strong>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="clearSelectedProduct()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        value="{{ old('tanggal_mulai') }}" required>
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
                                        value="{{ old('tanggal_berakhir') }}" required>
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
                                            max="100" step="0.01" value="{{ old('diskon') }}" required>
                                        <span class="input-group-text">%</span>
                                        @error('diskon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Enter a value between 0 and 100
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Status *
                                    </label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Active
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ old('status') === 'tidak_aktif' ? 'selected' : '' }}>
                                            <i class="fas fa-pause-circle"></i> Inactive
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
                                <button type="button" class="btn btn-info me-2" onclick="previewPromotion()">
                                    <i class="fas fa-eye me-1"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Promotion
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Sidebar -->
        <div class="col-lg-4">
            <div class="card" id="previewCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Promotion Preview
                    </h5>
                </div>
                <div class="card-body" id="promotionPreview">
                    <!-- Preview content will be populated by JavaScript -->
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Tips for Creating Promotions
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Timing:</strong> Schedule promotions during peak shopping hours
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Discount:</strong> Consider psychological pricing (e.g., 25%, 50%, 75%)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Duration:</strong> Limited-time offers create urgency
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Conflicts:</strong> System will prevent overlapping active promotions
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Testing:</strong> Use inactive status to test before going live
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Conflict Check -->
            <div class="card mt-4" id="conflictCard" style="display: none;">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Potential Conflicts
                    </h5>
                </div>
                <div class="card-body" id="conflictInfo">
                    <!-- Conflict information will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Set default date values
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('tanggal_mulai').value = now.toISOString().slice(0, 16);

            const nextWeek = new Date(now);
            nextWeek.setDate(nextWeek.getDate() + 7);
            document.getElementById('tanggal_berakhir').value = nextWeek.toISOString().slice(0, 16);

            // Form validation and preview updates
            $('#idProduk, #diskon, #tanggal_mulai, #tanggal_berakhir, #status').change(function() {
                updatePreview();
                checkConflicts();
            });

            // Form submission
            $('#promotionForm').submit(function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Creating...');
                submitBtn.prop('disabled', true);
            });
        });

        function updatePreview() {
            const productId = document.getElementById('idProduk').value;
            const discountInput = document.getElementById('diskon');
            const startDateInput = document.getElementById('tanggal_mulai');
            const endDateInput = document.getElementById('tanggal_berakhir');
            const statusSelect = document.getElementById('status');

            if (!productId || !discountInput.value) {
                $('#previewCard').hide();
                return;
            }

            const productName = document.getElementById('selected_product_name').textContent;
            const priceText = document.getElementById('selected_product_price').textContent;
            const originalPrice = parseFloat(priceText.replace(/[^\d]/g, ''));
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
            $('#previewCard').show();
        }

        function checkConflicts() {
            const productId = document.getElementById('idProduk').value;
            const startDate = document.getElementById('tanggal_mulai').value;
            const endDate = document.getElementById('tanggal_berakhir').value;

            if (!productId || !startDate || !endDate) {
                $('#conflictCard').hide();
                return;
            }

            // This would normally make an AJAX call to check for conflicts
            // For now, we'll show a placeholder
            $('#conflictCard').hide();

            // Simulate conflict check (you can implement actual AJAX call)
            /*
            $.ajax({
                url: `/admin/promosi/check-conflicts`,
                method: 'POST',
                data: {
                    idProduk: productId,
                    tanggal_mulai: startDate,
                    tanggal_berakhir: endDate,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.hasConflicts) {
                        $('#conflictInfo').html(response.conflictMessage);
                        $('#conflictCard').show();
                    } else {
                        $('#conflictCard').hide();
                    }
                }
            });
            */
        }

        function previewPromotion() {
            updatePreview();
            if ($('#previewCard').is(':visible')) {
                $('html, body').animate({
                    scrollTop: $('#previewCard').offset().top - 100
                }, 500);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Preview Not Available',
                    text: 'Please select a product and enter a discount percentage to see the preview.'
                });
            }
        }

        // Live Product Search
        let searchTimeout;
        const searchInput = document.getElementById('product_search');
        const dropdown = document.getElementById('product_dropdown');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                dropdown.classList.remove('show');
                return;
            }

            searchTimeout = setTimeout(() => {
                searchProducts(query);
            }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#product_search') && !e.target.closest('#product_dropdown')) {
                dropdown.classList.remove('show');
            }
        });

        function searchProducts(query) {
            fetch(`{{ route('admin.api.produk.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(products => {
                    displaySearchResults(products);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    dropdown.innerHTML = '<div class="dropdown-item text-danger">Error occurred during search</div>';
                    dropdown.classList.add('show');
                });
        }

        function displaySearchResults(products) {
            if (products.length === 0) {
                dropdown.innerHTML = '<div class="dropdown-item text-muted">Tidak ada produk ditemukan</div>';
                dropdown.classList.add('show');
                return;
            }

            let html = '';
            products.forEach(product => {
                const imageUrl = product.foto || '/images/placeholder-product.png';
                html += `
                    <div class="dropdown-item product-item" style="cursor: pointer; border-bottom: 1px solid #eee;"
                         onclick="selectProduct(${product.idProduk}, '${product.nama_produk}', '${product.kategori}', '${product.formatted_price}', '${imageUrl}', ${product.Harga})">
                        <div class="d-flex align-items-center">
                            <img src="${imageUrl}" alt="${product.nama_produk}"
                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='/images/placeholder-product.png'">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${product.nama_produk}</h6>
                                <small class="text-muted">${product.kategori}</small>
                                <br>
                                <strong class="text-success">${product.formatted_price}</strong>
                            </div>
                        </div>
                    </div>
                `;
            });

            dropdown.innerHTML = html;
            dropdown.classList.add('show');
        }

        function selectProduct(id, name, category, price, imageUrl, numericPrice) {
            // Set hidden input value
            document.getElementById('idProduk').value = id;

            // Clear search input
            searchInput.value = name;

            // Hide dropdown
            dropdown.classList.remove('show');

            // Show selected product
            document.getElementById('selected_product_image').src = imageUrl;
            document.getElementById('selected_product_name').textContent = name;
            document.getElementById('selected_product_category').textContent = category;
            document.getElementById('selected_product_price').textContent = price;
            document.getElementById('selected_product').style.display = 'block';

            // Update preview
            updatePreview();
        }

        function clearSelectedProduct() {
            document.getElementById('idProduk').value = '';
            document.getElementById('product_search').value = '';
            document.getElementById('selected_product').style.display = 'none';
            updatePreview();
        }
    </script>
@endpush
