@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->idPesanan)
@section('page-title', 'Detail Pesanan #' . $pesanan->idPesanan)
@section('page-description', 'Informasi lengkap pesanan pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
    <li class="breadcrumb-item active">Detail #{{ $pesanan->idPesanan }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Status & Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Status & Aksi</h6>
                    @php
                        $statusClass = [
                            'waiting_payment' => 'warning',
                            'waiting_confirmation' => 'info',
                            'confirmed' => 'primary',
                            'processing' => 'primary',
                            'shipped' => 'success',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            'payment_rejected' => 'danger',
                        ];
                        $statusText = [
                            'waiting_payment' => 'Menunggu Pembayaran',
                            'waiting_confirmation' => 'Menunggu Konfirmasi',
                            'confirmed' => 'Dikonfirmasi',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            'payment_rejected' => 'Pembayaran Ditolak',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusClass[$pesanan->status_pesanan] ?? 'secondary' }} fs-6">
                        {{ $statusText[$pesanan->status_pesanan] ?? $pesanan->status_pesanan }}
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $pesanan->idPesanan) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ubah Status Pesanan</label>
                                    <select name="status_pesanan" class="form-select" required>
                                        <option value="waiting_payment"
                                            {{ $pesanan->status_pesanan == 'waiting_payment' ? 'selected' : '' }}>
                                            Menunggu Pembayaran
                                        </option>
                                        <option value="waiting_confirmation"
                                            {{ $pesanan->status_pesanan == 'waiting_confirmation' ? 'selected' : '' }}>
                                            Menunggu Konfirmasi
                                        </option>
                                        <option value="confirmed"
                                            {{ $pesanan->status_pesanan == 'confirmed' ? 'selected' : '' }}>
                                            Dikonfirmasi
                                        </option>
                                        <option value="processing"
                                            {{ $pesanan->status_pesanan == 'processing' ? 'selected' : '' }}>
                                            Diproses
                                        </option>
                                        <option value="shipped"
                                            {{ $pesanan->status_pesanan == 'shipped' ? 'selected' : '' }}>
                                            Dikirim
                                        </option>
                                        <option value="completed"
                                            {{ $pesanan->status_pesanan == 'completed' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                        <option value="cancelled"
                                            {{ $pesanan->status_pesanan == 'cancelled' ? 'selected' : '' }}>
                                            Dibatalkan
                                        </option>
                                        <option value="payment_rejected"
                                            {{ $pesanan->status_pesanan == 'payment_rejected' ? 'selected' : '' }}>
                                            Pembayaran Ditolak
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Resi (Opsional)</label>
                                    <input type="text" name="nomor_resi" class="form-control"
                                        value="{{ $pesanan->nomor_resi }}"
                                        placeholder="Masukkan nomor resi untuk pengiriman">
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Update Status
                            </button>
                        </div>
                    </form>

                    @if ($pesanan->status_pesanan === 'waiting_confirmation' && $pesanan->bukti_pembayaran)
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('admin.payments.show', $pesanan->idPesanan) }}" class="btn btn-warning">
                                <i class="fas fa-check-circle me-1"></i>
                                Verifikasi Bukti Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Produk yang Dipesan ({{ $pesanan->detailPesanan->count() }} item)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if ($detail->produk->foto)
                                                        <img src="{{ asset('public/images/' . $detail->produk->foto) }}"
                                                            alt="{{ $detail->produk->nama_produk }}" class="rounded"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $detail->produk->nama_produk }}</h6>
                                                    <small class="text-muted">ID:
                                                        {{ $detail->produk->idProduk }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end">Rp
                                            {{ number_format($detail->produk->Harga, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total Pembayaran:</th>
                                    <th class="text-end text-success">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Order Info -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user-circle fa-3x text-gray-400 me-3"></i>
                        <div>
                            <h5 class="mb-1">{{ $pesanan->user->username }}</h5>
                            <p class="text-muted mb-0">{{ $pesanan->user->email }}</p>
                        </div>
                    </div>

                    @if ($pesanan->no_telepon)
                        <div class="mb-3">
                            <label class="text-muted small">No. Telepon</label>
                            <div>{{ $pesanan->no_telepon }}</div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted small">Alamat Pengiriman</label>
                        <div>{{ $pesanan->alamat_pengiriman }}</div>
                    </div>

                    @if ($pesanan->catatan)
                        <div class="mb-3">
                            <label class="text-muted small">Catatan Pelanggan</label>
                            <div>{{ $pesanan->catatan }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">ID Pesanan</label>
                        <div class="fw-bold">#{{ $pesanan->idPesanan }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Tanggal Pesanan</label>
                        <div>{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Metode Pembayaran</label>
                        <div>
                            @switch($pesanan->metode_pembayaran)
                                @case('transfer_bank')
                                    <i class="fas fa-university me-1"></i> Transfer Bank
                                @break

                                @case('cod')
                                    <i class="fas fa-money-bill me-1"></i> Cash on Delivery
                                @break

                                @case('ewallet')
                                    <i class="fas fa-mobile-alt me-1"></i> E-Wallet
                                @break

                                @default
                                    {{ $pesanan->metode_pembayaran }}
                            @endswitch
                        </div>
                    </div>

                    @if ($pesanan->nomor_resi)
                        <div class="mb-3">
                            <label class="text-muted small">Nomor Resi</label>
                            <div class="fw-bold">{{ $pesanan->nomor_resi }}</div>
                        </div>
                    @endif

                    @if ($pesanan->admin_notes)
                        <div class="mb-3">
                            <label class="text-muted small">Catatan Admin</label>
                            <div class="small">{{ $pesanan->admin_notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Proof -->
            @if ($pesanan->bukti_pembayaran)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bukti Pembayaran</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                            class="img-fluid rounded mb-3" style="max-height: 300px; cursor: pointer;"
                            onclick="window.open(this.src, '_blank')">
                        <div class="small text-muted">Klik gambar untuk memperbesar</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection
