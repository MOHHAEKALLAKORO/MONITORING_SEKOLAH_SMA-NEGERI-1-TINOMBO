@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- 1. Banner Sambutan / Hero Section -->
    <div class="card border-0 shadow-sm rounded-4 bg-success text-warning p-4 mb-4 position-relative overflow-hidden">
        <div class="position-relative z-1">
            <span class="badge fw-bold bg-warning text-success px-3 py-1 rounded-pill mb-2 fw-semibold">Tahun Ajaran 2025/2026</span>
            <h3 class="fw-bold mb-2">Selamat Datang {{ auth()->check() ? auth()->user()->name : 'Tamu' }}</h3>
            <p class="mb-0 text-warning-70">Sistem E-Monitoring Akademik, Presensi, dan Catatan BK • <strong>SMA Negeri 1 Tinombo</strong></p>
        </div>
        <div class="position-absolute end-0 bottom-0 translate-middle-y me-4 opacity-10 d-none d-md-block">
           <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMA Negeri 1 Tinombo"
     style="height: 88px; width: 88px; object-fit: contain; background: #ffffff; border-radius: 18px; padding: 6px;">
        </div>
    </div>

    <!-- 2. Ringkasan Statistik Cepat -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-4 fs-4 me-3">
                        <i class="text-warning bi bi-people"></i>
                    </div>
                    <div>
                        <span class="text-warning small d-block">Total Siswa</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalStudents ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 fs-4 me-3">
                        <i class="bi bi-ui-checks"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Hadir Hari Ini</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalHadir ?? 0 }} Siswa</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning-emphasis p-3 rounded-4 fs-4 me-3">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Sakit / Izin</span>
                        <h4 class="fw-bold text-dark mb-0">{{ ($totalSakit ?? 0) + ($totalIzin ?? 0) }} Siswa</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle text-danger p-3 rounded-4 fs-4 me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Alpha Hari Ini</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalAlpha ?? 0 }} Siswa</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Baris Utama: Grafik di Kiri (col-lg-8) & Status Hari Ini di Kanan (col-lg-4) -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Grafik Perkembangan Nilai -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Perkembangan Nilai</h5>
                        <p class="text-muted small mb-0">Perbandingan nilai beberapa evaluasi terakhir siswa SMA Negeri 1 Tinombo</p>
                    </div>
                    <div>
                        <select class="form-select form-select-sm rounded-3 px-3">
                            <option selected>Semester Ini</option>
                            <option>Semester Lalu</option>
                        </select>
                    </div>
                </div>

                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="perkembanganNilaiChart"></canvas>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-4 mt-3">
                    <div class="d-flex align-items-center">
                        <span style="width: 16px; height: 4px; background-color: #198754; display: inline-block; border-radius: 2px;" class="me-2"></span>
                        <span class="text-secondary small fw-medium">Nilai</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span style="width: 16px; height: 4px; background-color: #adb5bd; display: inline-block; border-radius: 2px; border-style: dashed;" class="me-2"></span>
                        <span class="text-secondary small fw-medium">KKM (75)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status Hari Ini -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">Status Hari Ini</h5>
                
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 bg-light rounded-3">
                        <span class="text-secondary small fw-medium">Hadir</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-circle bg-success p-1"></span>
                            <span class="fw-bold text-dark small">{{ $totalHadir ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 bg-light rounded-3">
                        <span class="text-secondary small fw-medium">Sakit</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-circle bg-warning p-1"></span>
                            <span class="fw-bold text-dark small">{{ $totalSakit ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 bg-light rounded-3">
                        <span class="text-secondary small fw-medium">Izin</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-circle bg-primary p-1"></span>
                            <span class="fw-bold text-dark small">{{ $totalIzin ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 bg-light rounded-3">
                        <span class="text-secondary small fw-medium">Alpha</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-circle bg-danger p-1"></span>
                            <span class="fw-bold text-dark small">{{ $totalAlpha ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 bg-light rounded-3">
                        <span class="text-secondary small fw-medium">Belum diabsen</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-circle bg-secondary p-1"></span>
                            <span class="fw-bold text-dark small">0</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto">
                    <a href="/presensi" class="btn btn-light w-100 rounded-3 text-secondary small fw-medium py-2 border">
                        Lihat Presensi Lengkap <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Panel Perlu Intervensi -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                        <h5 class="fw-bold text-dark mb-0">Perlu Intervensi</h5>
                    </div>
                    <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-semibold">1 Siswa</span>
                </div>
                <p class="text-muted small mb-3">Kehadiran, nilai, poin, catatan BK, dan kontak orang tua</p>

                <div class="card border border-light-subtle shadow-sm rounded-4 p-3 bg-light-subtle">
                    <div class="mb-2">
                        <h6 class="fw-bold text-dark mb-0">bagus</h6>
                        <span class="text-muted small">NIS 99999 • Kelas X-IPA 3</span>
                    </div>

                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge bg-danger-subtle text-danger px-2 py-1 fw-normal">Alpha hari ini</span>
                        <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 fw-normal">Kehadiran 0%</span>
                        <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 fw-normal">Nilai rata-rata 65 (di bawah KKM)</span>
                        <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 fw-normal">Di bawah KKM: matematika</span>
                        <span class="badge bg-primary-subtle text-primary px-2 py-1 fw-normal">1 catatan BK terbuka</span>
                        <span class="badge bg-info-subtle text-info-emphasis px-2 py-1 fw-normal">Belum dihubungi 7 hari</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <div class="small text-secondary">
                            Hadir: <strong class="text-dark">0%</strong> • Poin: <strong class="text-dark">100</strong> • Nilai: <strong class="text-dark">65</strong>
                        </div>
                        <div class="d-flex gap-2">
    <a href="{{ route('students.index') }}" class="btn btn-sm btn-light border text-primary me-1" title="Lihat Detail">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('counselings.index') }}" class="btn btn-sm btn-light border text-secondary me-1" title="Buku BK">
        <i class="bi bi-journal-text"></i>
    </a>
    <a href="https://wa.me/" target="_blank" class="btn btn-sm btn-success text-white" title="Kirim WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Akses Cepat Modul Utama -->
    <h5 class="fw-bold text-dark mb-3">Akses Cepat Modul Utama</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-success-subtle text-success p-3 rounded-3 fs-4 me-3">
                        <i class="bi bi-ui-checks"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Presensi Siswa</h5>
                        <p class="text-muted small mb-0">Kelola kehadiran harian, sakit, izin, atau alpa siswa dengan mudah.</p>
                    </div>
                </div>
                <a href="/presensi" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-auto w-100">Buka Presensi <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-warning-subtle text-warning-emphasis p-3 rounded-3 fs-4 me-3">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Buku Penghubung BK</h5>
                        <p class="text-muted small mb-0">Catat rekapitulasi prestasi dan poin pelanggaran karakter siswa.</p>
                    </div>
                </div>
                <a href="/buku-bk" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 mt-auto w-100">Buka Buku BK <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-success-subtle text-success p-3 rounded-3 fs-4 me-3">
                        <i class="bi bi-award"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Raport Semester</h5>
                        <p class="text-muted small mb-0">Rekap nilai akhir, presensi, dan catatan BK untuk cetak buku raport.</p>
                    </div>
                </div>
                <a href="/raport" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-auto w-100">Buka Raport <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Skrip Grafik Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('perkembanganNilaiChart').getContext('2d');
        
        const kkmLinePlugin = {
            id: 'kkmLine',
            beforeDraw(chart) {
                const { ctx, chartArea: { left, right }, scales: { y } } = chart;
                const yPos = y.getPixelForValue(75);
                
                ctx.save();
                ctx.beginPath();
                ctx.strokeStyle = '#ced4da';
                ctx.lineWidth = 1.5;
                ctx.setLineDash([6, 6]);
                ctx.moveTo(left, yPos);
                ctx.lineTo(right, yPos);
                ctx.stroke();
                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Tugas 1', 'UH 1', 'Tugas 2', 'PTS', 'UH 2', 'UH 3'],
                datasets: [{
                    label: 'Nilai',
                    data: [82, 85, 88, 86, 90, 92],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#198754',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#212529',
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        min: 50,
                        max: 100,
                        ticks: { stepSize: 5, color: '#6c757d', font: { size: 11 } },
                        grid: { color: '#f8f9fa' }
                    },
                    x: {
                        ticks: { color: '#6c757d', font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            },
            plugins: [kkmLinePlugin]
        });
    });
</script>
@endsection