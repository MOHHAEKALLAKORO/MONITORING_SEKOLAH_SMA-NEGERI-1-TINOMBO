@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill mb-2 fw-semibold">Bimbingan Konseling</span>
            <h2 class="fw-bold text-dark mb-1">Buku Penghubung BK</h2>
            <p class="text-muted mb-0">Catatan konseling, catatan bimbingan, dan pemantauan disiplin siswa SMA Negeri 1 Tinombo</p>
        </div>
        <div>
            <a href="{{ route('counselings.create') }}" class="btn btn-warning text-dark fw-semibold rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Catatan BK
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Ringkas BK (bulan ini) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 fs-4"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <span class="text-muted small d-block">Total Catatan Bulan Ini</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalBulanIni }} Kasus / Bimbingan</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle text-warning-emphasis p-3 rounded-4 fs-4"><i class="bi bi-exclamation-triangle"></i></div>
                    <div>
                        <span class="text-muted small d-block">Perlu Perhatian Khusus</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $perluPerhatian }} Siswa</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success p-3 rounded-4 fs-4"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <span class="text-muted small d-block">Selesai Ditangani</span>
                        <h4 class="fw-bold text-success mb-0">{{ $selesai }} Siswa</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Buku BK -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0">Riwayat Catatan Bimbingan Konseling</h5>
            <form method="GET" action="{{ route('counselings.index') }}" class="input-group w-auto">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm rounded-start-3" placeholder="Cari nama atau NIS siswa...">
                <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                @if ($search)
                    <a href="{{ route('counselings.index') }}" class="btn btn-outline-secondary btn-sm rounded-end-3" title="Hapus pencarian"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        <th class="py-3">Nama Siswa</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Jenis Kasus / Layanan</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($counselings as $c)
                    <tr>
                        <td class="ps-4 text-secondary small">{{ $c->tanggal?->format('d/m/Y') }}</td>
                        <td>
                            <h6 class="fw-bold text-dark mb-0">{{ $c->student->name ?? '-' }}</h6>
                            <small class="text-muted">NIS: {{ $c->student->nis ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $c->student->class ?? '-' }}</span></td>
                        <td>
                            <span class="fw-medium text-dark">{{ $c->jenis }}</span>
                            @if ($c->poin > 0)
                                <span class="badge bg-danger-subtle text-danger ms-1">{{ $c->poin }} poin</span>
                            @endif
                            <p class="text-muted small mb-0 text-truncate" style="max-width: 250px;">{{ $c->deskripsi }}</p>
                        </td>
                        <td>
                            @if ($c->sudah_ditindak)
                                <span class="badge bg-success-subtle text-success px-2 py-1">Sudah Ditindaklanjuti</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1">Belum Ditindaklanjuti</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('counselings.show', $c) }}" class="btn btn-sm btn-light border text-primary me-1" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('counselings.edit', $c) }}" class="btn btn-sm btn-light border text-secondary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('counselings.destroy', $c) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus catatan BK {{ $c->student->name ?? '' }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            {{ $search ? 'Tidak ada catatan yang cocok dengan pencarian.' : 'Belum ada catatan BK.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection