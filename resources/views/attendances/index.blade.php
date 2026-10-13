@extends('layouts.app')

@section('content')
@php
    $badge = [
        'Hadir' => 'bg-success-subtle text-success',
        'Izin'  => 'bg-primary-subtle text-primary',
        'Sakit' => 'bg-warning-subtle text-warning-emphasis',
        'Alpa'  => 'bg-danger-subtle text-danger',
    ];
    $avatar = [
        'Hadir' => 'bg-success-subtle text-success',
        'Izin'  => 'bg-primary-subtle text-primary',
        'Sakit' => 'bg-warning-subtle text-warning-emphasis',
        'Alpa'  => 'bg-danger-subtle text-danger',
    ];
@endphp
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill mb-2 fw-semibold">Monitoring Kehadiran</span>
            <h2 class="fw-bold text-dark mb-1">Presensi Siswa</h2>
            <p class="text-muted mb-0">Pantau, verifikasi, dan kelola data kehadiran harian siswa SMA Negeri 1 Tinombo</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('presensi.export', array_filter(['tanggal' => $tanggal, 'kelas' => $kelas, 'search' => $search])) }}"
               class="btn btn-outline-success fw-semibold rounded-3 px-3 py-2">
                <i class="bi bi-download me-1"></i> Unduh Rekap
            </a>
            <a href="{{ route('presensi.create', ['tanggal' => $tanggal]) }}" class="btn btn-success fw-semibold rounded-3 px-3 py-2 shadow-sm">
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

    <!-- Kartu statistik (sesuai tanggal & filter) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #198754 !important;">
                <span class="text-muted small d-block">Hadir</span>
                <h3 class="fw-bold text-success mb-0">{{ $stat['Hadir'] }} <small class="fs-6 text-muted fw-normal">Siswa</small></h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #0d6efd !important;">
                <span class="text-muted small d-block">Izin / Dispensasi</span>
                <h3 class="fw-bold text-primary mb-0">{{ $stat['Izin'] }} <small class="fs-6 text-muted fw-normal">Siswa</small></h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #ffc107 !important;">
                <span class="text-muted small d-block">Sakit</span>
                <h3 class="fw-bold text-dark mb-0">{{ $stat['Sakit'] }} <small class="fs-6 text-muted fw-normal">Siswa</small></h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #dc3545 !important;">
                <span class="text-muted small d-block">Tanpa Keterangan (Alpa)</span>
                <h3 class="fw-bold text-danger mb-0">{{ $stat['Alpa'] }} <small class="fs-6 text-muted fw-normal">Siswa</small></h3>
            </div>
        </div>
    </div>

    <!-- Daftar kehadiran -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Daftar Kehadiran Siswa Harian</h5>
                <small class="text-muted">Menampilkan data presensi tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}{{ $kelas ? ' • ' . $kelas : '' }}</small>
            </div>

            <form method="GET" action="{{ route('presensi.index') }}" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" style="width: 190px;" placeholder="Cari nama / NIS...">
                <select name="kelas" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c }}" {{ $kelas === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm" style="width: 150px;" onchange="this.form.submit()">
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Nama Siswa</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Status Kehadiran</th>
                        <th class="py-3">Keterangan / Catatan</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $a)
                    @php
                        $nama = $a->student->name ?? '-';
                        $parts = preg_split('/\s+/', trim($nama));
                        $inisial = mb_strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold {{ $avatar[$a->status] ?? 'bg-light text-dark' }}" style="width: 38px; height: 38px; font-size: 0.8rem;">{{ $inisial }}</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $nama }}</h6>
                                    <small class="text-muted">NIS: {{ $a->student->nis ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $a->student->class ?? '-' }}</span></td>
                        <td><span class="badge {{ $badge[$a->status] ?? 'bg-light text-dark' }} px-2 py-1">{{ $a->status }}</span></td>
                        <td class="text-muted small">{{ $a->keterangan ?: '-' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('presensi.edit', $a) }}" class="btn btn-sm btn-light border text-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('presensi.destroy', $a) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus presensi {{ $nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">Belum ada data presensi untuk filter ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection