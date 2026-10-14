@extends('layouts.app')

@section('content')
@php
    $badge = [
        'Berhasil' => 'bg-success bg-opacity-10 text-success',
        'Gagal'    => 'bg-danger bg-opacity-10 text-danger',
        'Menunggu' => 'bg-warning bg-opacity-10 text-warning-emphasis',
    ];
@endphp
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold text-dark">Log WhatsApp</h2>
            <p class="text-muted mb-0">Riwayat pengiriman pesan WhatsApp sistem E-Monitoring.</p>
        </div>
        <div>
            <a href="{{ route('whatsapp.logs') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-clockwise"></i> Segarkan
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUji">
                <i class="bi bi-whatsapp"></i> Uji Kirim Pesan
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Statistik (dihitung dari database) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Total Pesan</div>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($stat['total']) }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Berhasil</div>
                <h3 class="fw-bold text-success mb-0">{{ number_format($stat['berhasil']) }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Gagal</div>
                <h3 class="fw-bold text-danger mb-0">{{ number_format($stat['gagal']) }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Menunggu</div>
                <h3 class="fw-bold text-warning-emphasis mb-0">{{ number_format($stat['menunggu']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Log -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3">Penerima</th>
                            <th class="py-3">Nomor Tujuan</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Pesan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="px-4 text-secondary">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $log->recipient_name }}</td>
                            <td><code>{{ $log->phone }}</code></td>
                            <td><span class="badge bg-light text-dark border">{{ $log->type }}</span></td>
                            <td style="max-width: 280px;" class="text-truncate" title="{{ $log->message }}">{{ $log->message }}</td>
                            <td><span class="badge {{ $badge[$log->status] ?? 'bg-light text-dark' }} px-2 py-1">{{ $log->status }}</span></td>
                            <td class="text-muted small">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada riwayat log WhatsApp.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <p class="text-muted small mt-2">Menampilkan maksimal 100 log terbaru.</p>
</div>

<!-- Modal Uji Kirim Pesan -->
<div class="modal fade" id="modalUji" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('whatsapp.logs.store') }}" method="POST" class="modal-content rounded-4">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Uji Kirim Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <<div class="alert alert-info small">
                   Pesan akan dikirim ke nomor tujuan lewat layanan WhatsApp Gateway Lokal. Untuk uji coba, gunakan <strong>nomor Anda sendiri</strong>.
            </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Penerima</label>
                    <input type="text" name="recipient_name" class="form-control" required value="{{ old('recipient_name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Tujuan</label>
                    <input type="text" name="phone" class="form-control" required placeholder="+62 812-3456-7890" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="type" class="form-select" required>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-1">
                    <label class="form-label fw-semibold">Pesan</label>
                    <textarea name="message" rows="3" class="form-control" required>{{ old('message') }}</textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalUji')).show();
    });
</script>
@endif
@endsection