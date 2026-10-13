@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Tombol Kembali & Cetak -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('report-cards.index') }}" class="btn btn-light rounded-3 px-3 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
        </a>
        <button onclick="window.print()" class="btn btn-success rounded-3 px-4 shadow-sm">
            <i class="bi bi-printer me-2"></i> Cetak Raport PDF
        </button>
        
    </div>
    <div class="text-center border-bottom pb-4 mb-4">
    <h4 class="fw-bold text-dark text-uppercase mb-1">Pemerintah Provinsi Sulawesi Tengah</h4>
    <h3 class="fw-bold text-dark text-uppercase mb-1">SMA NEGERI 1 TINOMBO</h3>
    <p class="text-muted small mb-0">Sistem E-Monitoring Akademik, Presensi, dan Catatan BK Siswa</p>
</div>
    

    <!-- Lembar Kertas Raport -->
    <div class="card border-0 shadow-sm rounded-4 p-5 bg-white mb-4">
        <div class="text-center border-bottom pb-4 mb-4">
            <h3 class="fw-bold text-dark text-uppercase mb-1">Laporan Hasil Belajar Semester</h3>
            <h5 class="text-secondary mb-0">E-Monitoring Akademik Sekolah</h5>
        </div>

        <!-- Informasi Siswa -->
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-medium ps-0" style="width: 140px;">Nama Siswa</td>
                        <td class="fw-bold text-dark">: {{ $student->nama_lengkap ?? $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">Nomor Induk (NISN)</td>
                        <td>: {{ $student->nisn?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
    <table class="table table-borderless mb-0">
        <tr>
            <td class="text-muted fw-medium ps-0" style="width: 140px;">Kelas</td>
            <!-- Ubah dari relasi ke kolom langsung $student->class -->
            <td class="fw-bold text-dark">: {{ $student->class ?? '-' }}</td>
        </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">Tahun Ajaran</td>
                        <td>: 2025/2026 (Semester Ganjil)</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Rekapitulasi Kehadiran -->
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-ui-checks text-success me-2"></i> Rekapitulasi Kehadiran</h5>
        <div class="row g-3 mb-4">
            <div class="col-3">
                <div class="p-3 border rounded-3 text-center bg-light">
                    <span class="text-muted small d-block">Hadir</span>
                    <h4 class="fw-bold text-success mb-0">{{ $totalHadir }}</h4>
                </div>
            </div>
            <div class="col-3">
                <div class="p-3 border rounded-3 text-center bg-light">
                    <span class="text-muted small d-block">Sakit</span>
                    <h4 class="fw-bold text-warning mb-0">{{ $totalSakit }}</h4>
                </div>
            </div>
            <div class="col-3">
                <div class="p-3 border rounded-3 text-center bg-light">
                    <span class="text-muted small d-block">Izin</span>
                    <h4 class="fw-bold text-info mb-0">{{ $totalIzin }}</h4>
                </div>
            </div>
            <div class="col-3">
                <div class="p-3 border rounded-3 text-center bg-light">
                    <span class="text-muted small d-block">Alpa</span>
                    <h4 class="fw-bold text-danger mb-0">{{ $totalAlpa }}</h4>
                </div>
            </div>
        </div>

        <!-- Catatan Bimbingan Konseling -->
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-bookmark text-warning me-2"></i> Catatan Karakter & BK</h5>
        <div class="table-responsive mb-5">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Catatan</th>
                        <th>Deskripsi / Kejadian</th>
                        <th class="text-center">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counselings as $index => $counseling)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ date('d/m/Y', strtotime($counseling->tanggal)) }}</td>
                        <td>
                            <span class="badge {{ $counseling->jenis == 'Prestasi' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $counseling->jenis }}
                            </span>
                        </td>
                        <td>{{ $counseling->deskripsi }}</td>
                        <td class="text-center fw-bold">{{ $counseling->poin > 0 ? '+' . $counseling->poin : $counseling->poin }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Tidak ada catatan pelanggaran atau prestasi khusus semester ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan / Pengesahan -->
        <div class="row mt-5 pt-4">
            <div class="col-4 text-center">
                <p class="text-muted mb-5">Orang Tua / Wali Murid</p>
                <br><br>
                <p class="fw-bold border-bottom d-inline-block px-4 mb-0">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <p class="text-muted mb-5">Wali Kelas / Administrator</p>
                <br><br>
                <p class="fw-bold border-bottom d-inline-block px-4 mb-0">Administrator Sekolah</p>
            </div>
        </div>
    </div>
</div>
@endsection