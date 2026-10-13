@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <span class="badge bg-success-subtle text-success mb-2">Manajemen Presensi</span>
            <h2 class="fw-bold text-dark mb-1">Presensi Sidik Jari</h2>
            <p class="text-muted mb-0">Kelola dan unggah data laporan kehadiran siswa dari mesin absensi sekolah.</p>
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

    <!-- Card Form Import -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3">Unggah File Laporan Mesin Absensi</h5>
        <form action="{{ route('attendances.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file_excel" class="form-label small text-muted fw-semibold">Pilih File (.csv / .xlsx)</label>
                <input class="form-control rounded-3" type="file" id="file_excel" name="file_excel" accept=".csv, .xlsx, .xls" required>
            </div>
            <button type="submit" class="btn btn-success rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-upload me-2"></i> Unggah & Proses Data
            </button>
        </form>
    </div>

    <!-- Tabel Log Presensi -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Daftar Kehadiran Siswa Terakhir</h5>
            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">Total: {{ isset($attendances) ? $attendances->count() : 0 }} Data</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" style="width: 5%;">No</th>
                        <th class="py-3" style="width: 25%;">Nama Siswa</th>
                        <th class="py-3" style="width: 15%;">Kelas</th>
                        <th class="py-3" style="width: 20%;">Jam Masuk</th>
                        <th class="py-3" style="width: 15%;">Status</th>
                        <th class="pe-4 py-3 text-end" style="width: 20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($attendances)
                        @forelse ($attendances as $i => $att)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $i + 1 }}</td>
                            <td><h6 class="fw-bold text-dark mb-0">{{ $att->student->nama ?? '-' }}</h6></td>
                            <td><span class="text-muted">{{ $att->student->kelas ?? '-' }}</span></td>
                            <td><span class="fw-semibold text-dark">{{ $att->jam_masuk ?? '-' }}</span></td>
                            <td>
                                <span class="badge bg-success-subtle text-success px-2 py-1">{{ $att->status }}</span>
                            </td>
                            <td class="pe-4 text-end text-muted small">{{ $att->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data presensi sidik jari yang diimpor.
                            </td>
                        </tr>
                        @endforelse
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection