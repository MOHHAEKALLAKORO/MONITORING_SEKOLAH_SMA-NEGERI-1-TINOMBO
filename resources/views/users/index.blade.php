@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill mb-2 fw-semibold">Khusus Admin</span>
            <h2 class="fw-bold text-dark mb-1">Kelola Pengguna</h2>
            <p class="text-muted mb-0">Buat dan atur akun admin, kepala sekolah, wali kelas, dan guru</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-success fw-semibold rounded-3 px-3 py-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Peran</th>
                        <th class="py-3">Kelas</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            {{ $u->name }}
                            @if ($u->id === auth()->id())
                                <span class="badge bg-light text-dark border ms-1">Anda</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td><span class="badge {{ \App\Support\Roles::badgeClass($u->role) }} px-2 py-1">{{ \App\Support\Roles::label($u->role) }}</span></td>
                        <td>{{ $u->kelas ?: '-' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-light border text-primary me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                            @if ($u->id !== auth()->id())
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus pengguna {{ $u->name }}? Kiriman forumnya tetap ada.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection