@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Detail Catatan BK</h4>
                <span class="text-muted small">{{ $counseling->tanggal?->format('d/m/Y') }}</span>
            </div>
            @if ($counseling->sudah_ditindak)
                <span class="badge bg-success-subtle text-success px-3 py-2">Sudah Ditindaklanjuti</span>
            @else
                <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2">Belum Ditindaklanjuti</span>
            @endif
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="text-muted small">Nama Siswa</div>
                <div class="fw-bold">{{ $counseling->student->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">NIS / Kelas</div>
                <div class="fw-bold">{{ $counseling->student->nis ?? '-' }} / {{ $counseling->student->class ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Jenis Kasus / Layanan</div>
                <div class="fw-bold">{{ $counseling->jenis }}
                    @if ($counseling->poin > 0)
                        <span class="badge bg-danger-subtle text-danger ms-1">{{ $counseling->poin }} poin</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="text-muted small">Deskripsi</div>
            <div style="white-space: pre-line;">{{ $counseling->deskripsi }}</div>
        </div>

        <div class="mb-4">
            <div class="text-muted small">Tindak Lanjut</div>
            <div style="white-space: pre-line;">{{ $counseling->tindak_lanjut ?: 'Belum ada tindak lanjut.' }}</div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('counselings.index') }}" class="btn btn-light border rounded-3 px-4">Kembali</a>
            <a href="{{ route('counselings.edit', $counseling) }}" class="btn btn-warning text-dark fw-semibold rounded-3 px-4">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection 