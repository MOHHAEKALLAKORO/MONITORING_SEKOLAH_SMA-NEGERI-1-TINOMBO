@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">Buat Topik Baru</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('forum.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category" class="form-select rounded-3" required>
                        @foreach ($categories as $c)
                            <option value="{{ $c }}" {{ old('category', 'Umum') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">"Pengumuman" hanya untuk Admin dan Kepala Sekolah.</div>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul</label>
                    <input type="text" name="title" class="form-control rounded-3" required maxlength="200" value="{{ old('title') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Isi Topik</label>
                    <textarea name="body" rows="8" class="form-control rounded-3" required>{{ old('body') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('forum.index') }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4">Terbitkan Topik</button>
            </div>
        </form>
    </div>
</div>
@endsection