@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran #' . $pesanan->idPesanan)
@section('page-title', 'Verifikasi Pembayaran #' . $pesanan->idPesanan)
@section('page-description', 'Verifikasi dan konfirmasi pembayaran pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.payments.pending') }}">Pembayaran Tertunda</a></li>
    <li class="breadcrumb-item active">Verifikasi #{{ $pesanan->idPesanan }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.payments.pending') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')

    <div class="row">
        <!-- Payment Proof & Actions -->
        <div class="col-lg-8">
            <!-- Payment Proof -->
            @if ($pesanan->bukti_pembayaran)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bukti Pembayaran</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}"
                            alt="Bukti Pembayaran #{{ $pesanan->idPesanan }}" class="img-fluid rounded shadow-sm mb-3"
                            style="max-height: 500px; cursor: pointer;"
                            onclick="openImageInNewTab('{{ asset('storage/' . $pesanan->bukti_pembayaran) }}')">

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-info btn-sm"
                                onclick="openImageInNewTab('{{ asset('storage/' . $pesanan->bukti_pembayaran) }}')">
                                <i class="fas fa-external-link-alt me-1"></i>
                                Buka di Tab Baru
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="downloadImage('{{ asset('storage/' . $pesanan->bukti_pembayaran) }}', 'bukti_pembayaran_{{ $pesanan->idPesanan }}')">
                                <i class="fas fa-download me-1"></i>
                                Download
                            </button>
                        </div>

                        <div class="small text-muted mt-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Klik gambar untuk memperbesar atau gunakan tombol di atas
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow mb-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Bukti Pembayaran Belum Diupload</h5>
                        <p class="text-muted">Pelanggan belum mengupload bukti pembayaran untuk pesanan ini.</p>
                    </div>
                </div>
            @endif

            <!-- Verification Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Verifikasi</h6>
                </div>
                <div class="card-body">
                    @if ($pesanan->bukti_pembayaran)
                        <form method="POST" action="{{ route('admin.payments.confirm', $pesanan->idPesanan) }}"
                            id="verificationForm">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Catatan Admin (Opsional)</label>
                                <textarea name="admin_notes" class="form-control" rows="4"
                                    placeholder="Berikan catatan tambahan untuk pelanggan..."></textarea>
                                <div class="form-text">
                                    Catatan ini akan dikirim ke pelanggan melalui notifikasi email.
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-success btn-lg"
                                            onclick="confirmPayment('confirm')">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Konfirmasi Pembayaran
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-danger btn-lg"
                                            onclick="confirmPayment('reject')">
                                            <i class="fas fa-times-circle me-2"></i>
                                            Tolak Pembayaran
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="action" id="actionInput">
                        </form>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Tidak dapat melakukan verifikasi karena bukti pembayaran belum diupload.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Pesanan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($detail->produk->foto)
                                                    <img src="{{ asset('public/images/' . $detail->produk->foto) }}"
                                                        alt="{{ $detail->produk->nama_produk }}" class="rounded me-2"
                                                        style="width: 30px; height: 30px; object-fit: cover;">
                                                @endif
                                                <small>{{ $detail->produk->nama_produk }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end small">Rp
                                            {{ number_format($detail->produk->Harga, 0, ',', '.') }}</td>
                                        <td class="text-end small">Rp
                                            {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end text-success">Rp
                                        {{ number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order & Customer Info -->
        <div class="col-lg-4">
            <!-- Order Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pesanan</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-warning fs-6">Menunggu Konfirmasi</span>
                    </div>
                    <div class="small text-muted">
                        Pesanan ini menunggu verifikasi pembayaran dari admin.
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user-circle fa-2x text-gray-400 me-3"></i>
                        <div>
                            <h6 class="mb-1">{{ $pesanan->user->username }}</h6>
                            <p class="text-muted small mb-0">{{ $pesanan->user->email }}</p>
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
                        <div class="small">{{ $pesanan->alamat_pengiriman }}</div>
                    </div>

                    @if ($pesanan->catatan)
                        <div class="mb-3">
                            <label class="text-muted small">Catatan Pelanggan</label>
                            <div class="small">{{ $pesanan->catatan }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">ID Pesanan</label>
                        <div class="fw-bold">#{{ $pesanan->idPesanan }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Tanggal Pesanan</label>
                        <div class="small">{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</div>
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

                    <div class="mb-3">
                        <label class="text-muted small">Total Pembayaran</label>
                        <div class="h5 text-success fw-bold">
                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.orders.show', $pesanan->idPesanan) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>
                            Lihat Detail Lengkap
                        </a>
                        <a href="{{ route('admin.users.show', $pesanan->user->id) }}"
                            class="btn btn-outline-info btn-sm">
                            <i class="fas fa-user me-1"></i>
                            Lihat Profil Pelanggan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function confirmPayment(action) {
            const actionText = action === 'confirm' ? 'mengkonfirmasi' : 'menolak';
            const confirmMessage = `Apakah Anda yakin ingin ${actionText} pembayaran ini?`;

            if (confirm(confirmMessage)) {
                document.getElementById('actionInput').value = action;
                document.getElementById('verificationForm').submit();
            }
        }

        function openImageInNewTab(imageUrl) {
            window.open(imageUrl, '_blank');
        }

        function downloadImage(imageUrl, filename) {
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <style>
        .img-fluid:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease-in-out;
        }
    </style>
@endsection
