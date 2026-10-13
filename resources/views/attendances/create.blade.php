@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <span class="badge bg-success-subtle text-success mb-2">Manajemen Presensi</span>
            <h2 class="fw-bold text-dark mb-1">Catat Presensi Manual</h2>
            <p class="text-muted mb-0">Formulir pencatatan kehadiran siswa secara manual.</p>
        </div>
        <a href="{{ route('attendances.index') }}" class="btn btn-light border rounded-3 px-3 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="student_id" class="form-label fw-semibold text-secondary small">Pilih Siswa</label>
                <select name="student_id" id="student_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->nama }} (Kelas: {{ $student->kelas }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold text-secondary small">Tanggal</label>
                <input type="date" class="form-control rounded-3" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label for="jam_masuk" class="form-label fw-semibold text-secondary small">Jam Masuk</label>
                <input type="time" class="form-control rounded-3" id="jam_masuk" name="jam_masuk" value="{{ date('H:i') }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fw-semibold text-secondary small">Status Kehadiran</label>
                <select name="status" id="status" class="form-select rounded-3" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpa">Alpa</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label fw-semibold text-secondary small">Keterangan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" class="form-control rounded-3" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('attendances.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection