@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <span class="badge bg-success-subtle text-success mb-2">Monitoring Kehadiran</span>
            <h2 class="fw-bold text-dark mb-1">Presensi Siswa</h2>
            <p class="text-muted mb-0">Pantau, verifikasi, dan kelola data kehadiran harian siswa</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Tombol untuk memicu Modal Import -->
            <button type="button" class="btn btn-success rounded-3 px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Import Sidik Jari
            </button>
           <a href="{{ route('presensi.create') }}" class="btn btn-success rounded-3 px-4 py-2 fw-semibold">
    <i class="bi bi-plus-lg me-1"></i> Catat Presensi
</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Bagian Filter & Tabel Kehadiran yang sudah ada -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
        <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Daftar Kehadiran Siswa Harian</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Nama Siswa</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Status Kehadiran</th>
                        <th class="py-3">Jam Masuk</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances ?? [] as $att)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $att->student->nama ?? '-' }}</td>
                        <td>{{ $att->student->kelas ?? '-' }}</td>
                        <td><span class="badge bg-success-subtle text-success">{{ $att->status }}</span></td>
                        <td>{{ $att->jam_masuk ?? '-' }}</td>
                        <td class="pe-4 text-end">-</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            Belum ada data presensi untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Import Sidik Jari -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="importModalLabel">Import Data Mesin Sidik Jari</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('attendances.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted small mb-3">Unggah file laporan ekspor (.csv / .xlsx) dari mesin presensi sidik jari untuk mencatat kehadiran siswa secara otomatis.</p>
                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-semibold small text-secondary">Pilih File Laporan</label>
                        <input class="form-control rounded-3" type="file" id="file_excel" name="file_excel" accept=".csv, .xlsx, .xls" required>
                    </div>
                </div>
                <div class="modal-header border-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">
                        <i class="bi bi-upload me-1"></i> Unggah & Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection