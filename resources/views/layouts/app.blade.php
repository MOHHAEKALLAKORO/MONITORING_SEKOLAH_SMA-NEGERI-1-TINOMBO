<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Monitoring - SMA Negeri 1 Tinombo</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons: salinan lokal dulu (lihat langkah copy), CDN sebagai cadangan -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        #sidebar {
            min-width: 280px;
            max-width: 280px;
            min-height: 100vh;
            background: #eef6f1;
            border-right: 1px solid #9aa09c;
            color: #333;
        }
        #sidebar ul.components li a {
            color: #1d61a5;
            text-decoration: none;
            transition: 0.2s;
            font-weight: 500;
        }
        #sidebar ul.components li a:hover {
            background-color: #f8f9fa;
            color: #198754;
        }
        #sidebar ul.components li.active > a {
            background-color: #e8f5e9;
            color: #198754;
            font-weight: bold;
        }
    </style>

    <!-- Pustaka Chart.js untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tema-gelap.css') }}">
</head>
<body>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav id="sidebar" class="d-none d-md-block">
            <div class="sidebar-header p-3">
                <div class="d-flex align-items-center gap-2 mb-3 px-1">
    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMA Negeri 1 Tinombo"
         style="height: 48px; width: 48px; object-fit: contain; background: #ffffff; border-radius: 12px; padding: 4px;">
    <div class="lh-sm">
        <div class="fw-bold" style="color: #ffc107; font-size: 0.95rem;">E-Monitoring</div>
        <div class="small" style="color: #93ab9d;">SMA Negeri 1 Tinombo</div>
    </div>
</div>
                <a href="/" class="text-decoration-none d-flex align-items-center justify-content-between p-3 mb-0 rounded-3 text-white  {{ Request::is('/') || Request::is('dashboard') ? 'bg-success-subtle text-success fw-bold shadow-sm' : 'text-dark bg-white' }}" style="background-color: #13211a !important;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-grid-3x3-gap-fill fs-4 text-warning me-2"></i>
                        <span class="lh-sm text-warning" style="font-size: 0.95rem; color: #effd2f;">Dashboard<br>Monitoring</span>
                    </div>
                    <span class="rounded-circle bg-white" style="width: 8px; height: 8px; display: inline-block;"></span>
                </a>
            </div>

            <ul class="list-unstyled components px-2">
                <li class="mb-1 {{ Request::is('akademik*') ? 'active' : '' }}">
                <div class="p-2 mb-2 rounded-3" style="background-color: #13211a">
                    <a href="/akademik" class="text-decoration-none d-flex align-items-center text-warning">
                        <i class="bi bi-mortarboard fs-5 text-warning me-3"></i>
                        <span class="fw-bold">Akademik & Nilai</span>
                    </a>    
                     
                   
                </li>

                <li class="mb-1 {{ Request::is('jadwal*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/jadwal" class="text-decoration-none d-flex align-items-center text-white">
                        <i class="bi bi-calendar-event fs-5 text-warning me-3"></i>
                        <span class="text-warning">Jadwal & Agenda Kelas</span>
                    </a> 
                     
                  
                </li>

                <li class="mb-1 {{ Request::is('buku-bk*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/buku-bk" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-journal-bookmark fs-5 text-warning me-3"></i>
                        <span class="fw-semibold text-warning">Buku Penghubung BK</span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('ekskul*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/ekskul" class="text-decoration-none d-flex align-items-center rounded-3  py-1  justify-content-between text-white">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-trophy fs-5 text-warning me-3"></i>
                            <span class="fw-semibold text-warning">Ekskul & Prestasi</span>
                        </div>
                        <span class="badge rounded-pill ms-2" style="background-color: #f3e8ff; color: #7e22ce; font-size: 0.65rem;"></span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('presensi*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/presensi" class="text-decoration-none d-flex align-items-center rounded-3 py-1 text-white">
                        <i class="bi bi-ui-checks fs-5 text-warning me-3"></i>
                        <span class="fw-semibold text-warning">Presensi Siswa</span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('students*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/students" class="text-decoration-none d-flex align-items-center rounded-3  py-2 text-white">
                        <i class="bi bi-people fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Data Siswa</span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('laporan*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/laporan" class="text-decoration-none d-flex align-items-center rounded-3  py-2 text-white">
                        <i class="bi bi-bar-chart fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Laporan Bulanan</span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('log*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/log-whatsapp" class="text-decoration-none d-flex align-items-center rounded-3  py-2 text-white">
                        <i class="bi bi-whatsapp fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Log WhatsApp</span>
                    </a>
                </li>

                <li class="mb-1 {{ Request::is('raport*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/raport" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-award fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Raport Semester</span>
                    </a>
                </li>

                @if (auth()->check() && auth()->user()->role === 'wali_kelas')
                <li class="mb-1 {{ Request::is('kelas-saya*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/kelas-saya" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-house-heart fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Kelas Saya</span>
                    </a>
                </li>
                @endif

                <li class="mb-1 {{ Request::is('forum*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/forum" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-chat-square-text fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Forum Guru</span>
                    </a>
                </li>

                @if (auth()->check() && auth()->user()->role === 'admin')
                <li class="mb-1 {{ Request::is('pengguna*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/pengguna" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-person-gear fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Kelola Pengguna</span>
                    </a>
                </li>
                @endif

                <li class="mb-1 {{ Request::is('pengaturan*') ? 'active' : '' }}">
                    <div class="p-2 mb-2 rounded-3" style="background-color: #13211a;">
                    <a href="/pengaturan" class="text-decoration-none d-flex align-items-center rounded-3 py-2 text-white">
                        <i class="bi bi-gear fs-5 text-warning me-3"></i>
                        <span class="text-warning fw-semibold">Pengaturan Akun</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- PAGE CONTENT CONTAINER -->
        <div id="content" class="d-flex flex-column flex-grow-1 min-width: 0;">
            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
                <div class="container-fluid">
                    <span class="p-4 rounded-4 fw-bold text-warning  mb-2" style="background-color: #13211a">Panel {{ auth()->check() ? (auth()->user()->role === 'admin' ? 'Administrator' : \App\Support\Roles::label(auth()->user()->role)) : 'Tamu' }}</span>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; font-weight: bold;">
                                {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) : '?' }}
                            </div>
                            <span class="d-none d-sm-inline lh-sm">
                                <span class="fw-semibold d-block">{{ auth()->check() ? auth()->user()->name : 'Tamu' }}</span>
                                @auth
                                    <small class="text-muted">{{ \App\Support\Roles::label(auth()->user()->role) }}</small>
                                @endauth
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 text-small" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                @auth
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                    </form>
                                @else
                                    <a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i> Masuk</a>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- MAIN CONTENT AREA -->
            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    if (window.Chart) {
        Chart.defaults.color = '#93ab9d';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.08)';
    }
</script>
</body>
</html>