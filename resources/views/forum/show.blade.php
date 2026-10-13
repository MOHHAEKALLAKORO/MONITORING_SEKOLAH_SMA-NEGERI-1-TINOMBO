@extends('layouts.app')

@section('content')
@php
    $me = auth()->user();
    $isAdmin = $me->role === 'admin';
    $isLeader = \App\Support\Roles::isLeader($me->role);
    $canDeleteTopic = $isAdmin || ($topic->user_id && $me->id === $topic->user_id);
@endphp
<div class="container-fluid px-4 py-4">

    <a href="{{ route('forum.index') }}" class="text-decoration-none text-muted small d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke forum
    </a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Topik -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                @if ($topic->is_pinned)
                    <i class="bi bi-pin-angle-fill text-warning me-1"></i>
                @endif
                <span class="badge bg-light text-dark border">{{ $topic->category }}</span>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ $topic->title }}</h3>
                <div class="small text-muted">
                    oleh <span class="fw-semibold">{{ $topic->user->name ?? 'Pengguna dihapus' }}</span>
                    <span class="badge {{ \App\Support\Roles::badgeClass($topic->user?->role) }}">{{ \App\Support\Roles::label($topic->user?->role) }}</span>
                    &bull; {{ $topic->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                @if ($isLeader)
                    <form action="{{ route('forum.pin', $topic) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pin-angle"></i> {{ $topic->is_pinned ? 'Lepas Sematan' : 'Sematkan' }}
                        </button>
                    </form>
                @endif
                @if ($canDeleteTopic)
                    <form action="{{ route('forum.destroy', $topic) }}" method="POST"
                          onsubmit="return confirm('Hapus topik ini beserta semua balasannya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                @endif
            </div>
        </div>
        <div style="white-space: pre-line;">{{ $topic->body }}</div>
    </div>

    <!-- Balasan -->
    <h5 class="fw-bold text-dark mb-3">{{ $replies->count() }} Balasan</h5>

    @foreach ($replies as $r)
        @php $canDeleteReply = $isAdmin || ($r->user_id && $me->id === $r->user_id); @endphp
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-3" id="balasan-{{ $r->id }}">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="small text-muted mb-2">
                    <span class="fw-semibold text-dark">{{ $r->user->name ?? 'Pengguna dihapus' }}</span>
                    <span class="badge {{ \App\Support\Roles::badgeClass($r->user?->role) }}">{{ \App\Support\Roles::label($r->user?->role) }}</span>
                    &bull; {{ $r->created_at->format('d/m/Y H:i') }}
                </div>
                @if ($canDeleteReply)
                    <form action="{{ route('forum.reply.destroy', $r) }}" method="POST"
                          onsubmit="return confirm('Hapus balasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus balasan"><i class="bi bi-trash"></i></button>
                    </form>
                @endif
            </div>
            <div style="white-space: pre-line;">{{ $r->body }}</div>
        </div>
    @endforeach

    <!-- Form balas -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h6 class="fw-bold text-dark mb-3">Tulis Balasan</h6>
        <form action="{{ route('forum.reply', $topic) }}" method="POST">
            @csrf
            <textarea name="body" rows="4" class="form-control rounded-3 mb-3" required placeholder="Tulis balasan Anda...">{{ old('body') }}</textarea>
            <div class="text-end">
                <button type="submit" class="btn btn-success rounded-3 px-4"><i class="bi bi-send me-1"></i> Kirim Balasan</button>
            </div>
        </form>
    </div>
</div>
@endsection