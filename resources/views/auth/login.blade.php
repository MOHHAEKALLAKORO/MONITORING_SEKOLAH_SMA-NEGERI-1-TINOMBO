<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk{{ $portal ? ' ' . $portal['label'] : '' }} - E-Monitoring SMA Negeri 1 Tinombo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/tema-gelap.css') }}">
</head>
<body>
<div class="container d-flex align-items-center justify-content-center py-4" style="min-height: 100vh;">
    <div class="card border-0 shadow-sm rounded-4 p-4 w-100" style="max-width: 420px;">
        <div class="text-center mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background-color: #1f3a2c; color: #ffc107; border: 1px solid #25402f;">
                <i class="bi {{ $portal['icon'] ?? 'bi-mortarboard' }} fs-3"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ $portal ? 'Login ' . $portal['label'] : 'E-Monitoring' }}</h4>
            <p class="text-muted small mb-0">SMA Negeri 1 Tinombo</p>
            @if ($portal)
                <p class="small text-success mt-2 mb-0">Halaman ini khusus akun {{ $portal['label'] }}.</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ url()->current() }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kata Sandi</label>
                <input type="password" name="password" class="form-control rounded-3" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ingat saya di perangkat ini</label>
            </div>
            <button type="submit" class="btn btn-success w-100 rounded-3 py-2 fw-semibold">Masuk</button>
        </form>

        <hr class="my-4">
        <div class="text-center small text-muted">
            Masuk sebagai:
            <div class="mt-2 d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('login') }}" class="badge text-decoration-none {{ $portal ? 'bg-light text-dark border' : 'bg-success text-white' }}">Admin / Guru</a>
                <a href="{{ route('login.kepala') }}" class="badge text-decoration-none {{ ($portal['role'] ?? '') === 'kepala_sekolah' ? 'bg-success text-white' : 'bg-light text-dark border' }}">Kepala Sekolah</a>
                <a href="{{ route('login.wali') }}" class="badge text-decoration-none {{ ($portal['role'] ?? '') === 'wali_kelas' ? 'bg-success text-white' : 'bg-light text-dark border' }}">Wali Kelas</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>