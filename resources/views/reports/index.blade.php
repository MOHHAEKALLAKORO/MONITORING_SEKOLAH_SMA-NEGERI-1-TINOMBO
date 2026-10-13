@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Laporan Bulanan Akademik</h4>
            <p class="text-muted mb-0">Rekapitulasi presensi dan catatan Bimbingan Konseling (BK) siswa per bulan.</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary px-4 py-2 rounded-3 shadow-sm d-flex align-items-center">
            <i class="bi bi-printer me-2"></i> Cetak Laporan
        </button>
    </div>
    <div class="text-center border-bottom pb-4 mb-4">
    <h5 class="fw-bold text-dark text-uppercase mb-1">Pemerintah Provinsi Sulawesi Tengah</h5>
    <h5 class="fw-bold text-dark text-uppercase mb-1">SMA NEGERI 1 TINOMBO</h5>
    <p class="text-muted small mb-0">Sistem E-Monitoring Akademik, Presensi, dan Catatan BK Siswa</p>
</div>

    <!-- Filter Bulan & Tahun -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-medium text-secondary small mb-1">Pilih Bulan</label>
                    <select name="bulan" class="form-select rounded-3">
                        @php
                            $listBulan = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                        @endphp
                        @foreach($listBulan as $key => $namaBulan)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium text-secondary small mb-1">Pilih Tahun</label>
                    <select name="tahun" class="form-select rounded-3">
                        @for($t = date('Y'); $t >= date('Y') - 2; $t--)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success px-4 rounded-3 shadow-sm w-100 mt-3">
                        <i class="bi bi-filter me-2"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik Singkat -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-success-subtle text-success p-3">
                <div class="d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Total Rekap Presensi Bulan Ini</h6>
                        <h5 class="fw-bold mb-0">{{ count($attendances) }} Catatan Kehadiran</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-warning-subtle text-warning-emphasis p-3">
                <div class="d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Total Catatan BK Bulan Ini</h6>
                        <h3 class="fw-bold mb-0">{{ count($counselings) }} Catatan (Prestasi/Pelanggaran)</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap Presensi -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-ui-checks me-2 text-success"></i> Rincian Presensi Siswa</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $item)
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td>
                                <div class=<div class="fw-bold text-dark">{{ $item->student->name ?? '-' }}</div></div>
                                <small class="text-muted">NIS: {{ $item->student->nis ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge px-3 py-2 rounded-pill 
                                    @if($item->status == 'Hadir') bg-success-subtle text-success 
                                    @elseif($item->status == 'Sakit') bg-warning-subtle text-warning 
                                    @elseif($item->status == 'Izin') bg-info-subtle text-info 
                                    @else bg-danger-subtle text-danger @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada data presensi pada bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap Buku BK -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-bookmark me-2 text-warning"></i> Rincian Buku BK (Prestasi / Pelanggaran)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($counselings as $index => $item)
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td>
                                <div class=<div class="fw-bold text-dark">{{ $item->student->name ?? '-' }}</div></div>
                                <small class="text-muted">NIS: {{ $item->student->nis ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge px-3 py-2 rounded-pill 
                                    @if($item->jenis == 'Prestasi') bg-success-subtle text-success 
                                    @elseif($item->jenis == 'Pelanggaran') bg-danger-subtle text-danger 
                                    @else bg-primary-subtle text-primary @endif">
                                    {{ $item->jenis }}
                                </span>
                            </td>
                            <td>{{ $item->deskripsi }}</td>
                            <td class="text-center fw-bold @if($item->poin > 0) text-success @elseif($item->poin < 0) text-danger @else text-muted @endif">
                                {{ $item->poin > 0 ? '+' . $item->poin : $item->poin }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada catatan BK pada bulan ini.
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