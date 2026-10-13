@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h5 class="mb-4 text-secondary fw-bold"><i class="fa-solid fa-user-plus me-2"></i> Form Tambah Siswa Baru</h5>

            <!-- Menampilkan error validasi jika ada -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/students" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" class="form-control" id="nisn" name="nisn" value="{{ old('nisn') }}" required placeholder="Contoh: 0051234567">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap Siswa</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Fauzan">
                </div>

                <div class="mb-3">
                    <label for="class" class="form-label">Kelas</label>
                    <input type="text" class="form-control" id="class" name="class" value="{{ old('class') }}" required placeholder="Contoh: X RPL 1">
                </div>

                <div class="mb-3">
                    <label for="parent_phone" class="form-label">No. WhatsApp Orang Tua / Wali</label>
                    <input type="text" class="form-control" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required placeholder="Contoh: 081234567890">
                    <div class="form-text">Gunakan awalan 08 atau 62 agar integrasi WhatsApp nantinya berjalan lancar.</div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/students" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i> Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection