@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-4">
                <div class="mb-3">
                    <i class="bi bi-tools text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">{{ $title }}</h3>
                <p class="text-muted mt-2">Fitur <strong>{{ $title }}</strong> sedang dalam tahap pengembangan dan akan segera hadir di sistem E-Monitoring PKBM Punsu Jaya.</p>
                <div class="mt-4">
                    <a href="/" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection