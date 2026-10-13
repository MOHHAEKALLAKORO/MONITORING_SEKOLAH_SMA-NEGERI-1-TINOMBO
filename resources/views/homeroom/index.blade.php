@extends('layouts.app')

@section('content')
@php
    $badge = [
        'Hadir' => 'bg-success-subtle text-success',
        'Izin'  => 'bg-primary-subtle text-primary',
        'Sakit' => 'bg-warning-subtle text-warning-emphasis',
        'Alpa'  => 'bg-danger-subtle text-danger',
    ];
@endphp
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill mb-2 fw-semibold">Wali Kelas</span>
            <h2 class="fw-bold text-dark mb-1">Kelas Saya{{ $kelas ? ' • ' . $kelas : '' }}</h2>
            <p class="text-muted mb-0">Ringkasan kehadiran hari ini, {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}</p>
        </div>
        @if ($kelas)
            <a href="{{ route('presensi.index', ['kelas' => $kelas, 'tanggal' => $today]) }}" class="btn btn-success fw-semibold rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-ui-checks me-1"></i> Buka Presensi Kelas
            </a>
        @endif
    </div>

    @if (!$kelas)
        <div class="alert alert-warning">
            Akun Anda belum ditetapkan ke sebuah kelas. Hubungi admin agar kelas Anda diisi di menu <strong>Kelola Pengguna</strong>.
        </div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small">Jumlah Siswa</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $students->count() }}</h3>
                </div>
            </div>
            @foreach (['Hadir' => 'text-success', 'Izin' => 'text-primary', 'Sakit' => 'text-dark', 'Alpa' => 'text-danger'] as $st => $cls)
                <div class="col-6 col-lg">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="text-muted small">{{ $st }}</div>
                        <h3 class="fw-bold {{ $cls }} mb-0">{{ $stat[$st] }}</h3>
                    </div>
                </div>
            @endforeach
            <div class="col-6 col-lg">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small">Belum Dicatat</div>
                    <h3 class="fw-bold text-secondary mb-0">{{ $stat['Belum'] }}</h3>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">No</th>
                            <th class="py-3">Nama Siswa</th>
                            <th class="py-3">NIS</th>
                            <th class="py-3">Kehadiran Hari Ini</th>
                            <th class="pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $i => $s)
                            @php $a = $todayByStudent[$s->id] ?? null; @endphp
                            <tr>
                                <td class="ps-4 text-secondary">{{ $i + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $s->name }}</td>
                                <td class="text-muted">{{ $s->nis }}</td>
                                <td>
                                    @if ($a)
                                        <span class="badge {{ $badge[$a->status] ?? 'bg-light text-dark' }} px-2 py-1">{{ $a->status }}</span>
                                    @else
                                        <span class="badge bg-light text-secondary border px-2 py-1">Belum dicatat</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('presensi.create', ['tanggal' => $today]) }}" class="btn btn-sm btn-light border text-success me-1" title="Catat presensi"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{ route('report-cards.show', $s->id) }}" class="btn btn-sm btn-light border text-primary" title="Lihat raport"><i class="bi bi-file-earmark-text"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada siswa di kelas {{ $kelas }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection