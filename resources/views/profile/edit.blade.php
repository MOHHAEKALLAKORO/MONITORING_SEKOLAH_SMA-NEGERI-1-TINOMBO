@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Pengaturan Akun</h2>
        <p class="text-muted mb-0">Kelola informasi profil administrator dan keamanan sandi akun Anda.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Informasi Profil -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-success me-2"></i> Informasi Profil</h5>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name ?? 'Administrator' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Alamat Email</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email ?? 'admin@school.com' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Hak Akses (Role)</label>
                        <input type="text" class="form-control rounded-3 bg-light" value="{{ $user->role ?? 'Administrator' }}" disabled>
                    </div>
                    <button type="submit" class="btn btn-success px-4 rounded-3 shadow-sm">
                        <i class="bi bi-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Keamanan & Ganti Sandi -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock text-warning me-2"></i> Keamanan & Kata Sandi</h5>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="form-control rounded-3" placeholder="••••••••">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Kata Sandi Baru</label>
                        <input type="password" name="new_password" class="form-control rounded-3" placeholder="••••••••">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control rounded-3" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-warning px-4 rounded-3 shadow-sm text-dark fw-medium">
                        <i class="bi bi-key me-2"></i> Perbarui Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection