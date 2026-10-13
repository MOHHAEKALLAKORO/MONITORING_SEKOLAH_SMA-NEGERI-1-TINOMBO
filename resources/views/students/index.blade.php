@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="card p-4">
    <!-- Pesan Sukses jika data berhasil disimpan -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-secondary fw-bold">Daftar Seluruh Siswa</h5>
        <a href="/students/create" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Siswa Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>No. WhatsApp Wali</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->nisn }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->parent_phone }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                            Belum ada data siswa yang tersimpan. Silakan klik tombol "Tambah Siswa Baru".
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection