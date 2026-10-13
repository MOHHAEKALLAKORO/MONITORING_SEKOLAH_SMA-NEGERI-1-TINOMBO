@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill mb-2 fw-semibold">Diskusi Internal</span>
            <h2 class="fw-bold text-dark mb-1">Forum Guru &amp; Pimpinan</h2>
            <p class="text-muted mb-0">Ruang diskusi untuk guru, kepala sekolah, dan admin SMA Negeri 1 Tinombo</p>
        </div>
        <a href="{{ route('forum.create') }}" class="btn btn-success fw-semibold rounded-3 px-3 py-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Topik
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-3 mb-4">
        <form method="GET" action="{{ route('forum.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control rounded-3" placeholder="Cari judul atau isi topik...">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c }}" {{ $kategori === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success flex-grow-1"><i class="bi bi-search"></i> Cari</button>
                @if ($q || $kategori)
                    <a href="{{ route('forum.index') }}" class="btn btn-light border" title="Hapus filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Daftar topik -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse ($topics as $t)
                <a href="{{ route('forum.show', $t) }}" class="list-group-item list-group-item-action px-4 py-3">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            @if ($t->is_pinned)
                                <i class="bi bi-pin-angle-fill text-warning me-1" title="Disematkan"></i>
                            @endif
                            <span class="fw-bold text-dark">{{ $t->title }}</span>
                            <span class="badge bg-light text-dark border ms-1">{{ $t->category }}</span>
                            <div class="small text-muted mt-1">
                                oleh <span class="fw-semibold">{{ $t->user->name ?? 'Pengguna dihapus' }}</span>
                                <span class="badge {{ \App\Support\Roles::badgeClass($t->user?->role) }}">{{ \App\Support\Roles::label($t->user?->role) }}</span>
                                &bull; {{ $t->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="text-end text-muted small text-nowrap">
                            <i class="bi bi-chat-dots"></i> {{ $t->replies_count }} balasan<br>
                            Aktivitas: {{ $t->updated_at->format('d/m H:i') }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-5">
                    {{ ($q || $kategori) ? 'Tidak ada topik yang cocok.' : 'Belum ada topik. Jadilah yang pertama membuat topik!' }}
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">
        {{ $topics->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection