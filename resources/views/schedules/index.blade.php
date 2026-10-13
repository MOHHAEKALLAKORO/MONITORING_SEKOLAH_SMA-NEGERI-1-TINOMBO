@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <span class="badge bg-success-subtle text-success mb-2">Manajemen Jadwal</span>
            <h2 class="fw-bold text-dark mb-1">Jadwal & tempat; Agenda Kelas</h2>
            <p class="text-muted mb-0">Atur dan pantau jadwal kegiatan belajar mengajar harian berdasarkan kelas dan waktu.</p>
        </div>
        <a href="{{ route('schedules.create', ['hari' => $hari]) }}" class="btn btn-success rounded-3 px-4 py-2 fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <form method="GET" action="{{ route('schedules.index') }}" id="filterForm">
                    <input type="hidden" name="hari" value="{{ $hari }}">
                    <label class="form-label small text-muted fw-semibold">Filter Kelas</label>
                    <select name="kelas" class="form-select rounded-3" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c }}" {{ $kelas === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-8">
                <label class="form-label small text-muted fw-semibold">Filter Hari Efektif</label>
                <div class="btn-group w-100" role="group">
                    @foreach ($hariList as $h)
                        <a href="{{ route('schedules.index', array_filter(['hari' => $h, 'kelas' => $kelas])) }}"
                           class="btn {{ $hari === $h ? 'btn-success' : 'btn-outline-success' }}">{{ $h }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Jadwal -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                Daftar Pelajaran • {{ $hari }}{{ $kelas ? ' • ' . $kelas : '' }}
            </h5>
            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">Total: {{ $schedules->count() }} Sesi</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" style="width: 5%;">No</th>
                        <th class="py-3" style="width: 12%;">Hari</th>
                        <th class="py-3" style="width: 20%;">Jam Pelajaran</th>
                        <th class="py-3" style="width: 31%;">Mata Pelajaran</th>
                        <th class="py-3" style="width: 17%;">Kelas &amp; Ruang</th>
                        <th class="pe-4 py-3 text-end" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $i => $s)
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">{{ $i + 1 }}</td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">{{ $s->hari }}</span></td>
                        <td>
                            <span class="fw-bold text-dark d-block">{{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }}</span>
                        </td>
                        <td>
                            <h6 class="fw-bold text-dark mb-0">{{ $s->subject->nama_mapel ?? '-' }}</h6>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark d-block">{{ $s->kelas }}</span>
                            <span class="text-muted small">{{ $s->ruangan }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('schedules.edit', $s) }}" class="btn btn-sm btn-light border text-primary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('schedules.destroy', $s) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus jadwal {{ $s->subject->nama_mapel ?? '' }} ({{ $s->kelas }})?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Belum ada jadwal untuk hari {{ $hari }}{{ $kelas ? ' di kelas ' . $kelas : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection