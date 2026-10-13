@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Pengaturan Akun</h2>
        <p class="text-muted mb-0">Kelola informasi profil dan keamanan sandi akun Anda.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!$user)
        <div class="alert alert-warning">
            Anda belum masuk. Silakan <a href="{{ url('/login') }}">masuk</a> terlebih dahulu untuk mengubah pengaturan akun.
        </div>
    @else
        <div class="row g-4">

            <!-- Informasi Profil -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-success me-2"></i> Informasi Profil</h5>

                    @if ($errors->getBag('profile')->any())
                        <div class="alert alert-danger py-2 small">
                            @foreach ($errors->getBag('profile')->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="profile">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" required value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                            <input type="email" name="email" class="form-control rounded-3" required value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Hak Akses (Role)</label>
                            <input type="text" class="form-control rounded-3 bg-light" value="{{ \App\Support\Roles::label($user->role) }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success rounded-3 px-3">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Keamanan & Kata Sandi -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock text-warning me-2"></i> Keamanan &amp; Kata Sandi</h5>

                    @if ($errors->getBag('password')->any())
                        <div class="alert alert-danger py-2 small">
                            @foreach ($errors->getBag('password')->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="section" value="password">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="form-control rounded-3" required autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kata Sandi Baru</label>
                            <input type="password" name="password" class="form-control rounded-3" required minlength="8" autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3" required minlength="8" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-warning rounded-3 px-3">
                            <i class="bi bi-key me-1"></i> Perbarui Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection