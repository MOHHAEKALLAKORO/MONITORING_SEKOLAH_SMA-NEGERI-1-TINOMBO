@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Rekapitulasi Raport Semester</h2>
            <p class="text-muted mb-0">Pilih siswa untuk melihat dan mencetak buku laporan hasil belajar semester.</p>
        </div>
    </div>

    <!-- Tabel Daftar Siswa untuk Raport -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">{{ $index + 1 }}</td>
                            <td><code>{{ $student->nis ?? '-' }}</code></td>
                            <td class="fw-bold text-dark">{{ $student->nama_lengkap ?? $student->name }}</td>
                            <td>{{ $student->kelas->nama_kelas ?? 'Kelas Belum Diatur' }}</td>
                            <td class="text-center">
                                <a href="{{ route('report-cards.show', $student->id) }}" class="btn btn-sm btn-success px-3 rounded-pill shadow-sm">
                                    <i class="bi bi-file-earmark-text me-1"></i> Buka Raport
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Belum ada data siswa yang tersedia untuk dicetak raportnya.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection