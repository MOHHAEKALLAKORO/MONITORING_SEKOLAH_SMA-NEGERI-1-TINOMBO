@extends('layouts.app')

@section('content')
@php $isEdit = $counseling->exists; @endphp
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">{{ $isEdit ? 'Edit Catatan BK' : 'Tambah Catatan BK' }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('counselings.update', $counseling) : route('counselings.store') }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Siswa</label>
                    <select name="student_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}" {{ (string) old('student_id', $counseling->student_id) === (string) $s->id ? 'selected' : '' }}>
                                {{ $s->name }} — {{ $s->class }} (NIS: {{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control rounded-3" required
                           value="{{ old('tanggal', $counseling->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Jenis Catatan</label>
                    <select name="jenis" class="form-select rounded-3" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($jenisList as $j)
                            <option value="{{ $j }}" {{ old('jenis', $counseling->jenis) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Poin</label>
                    <input type="number" name="poin" min="0" class="form-control rounded-3"
                           value="{{ old('poin', $counseling->poin ?? 0) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control rounded-3" required
                              placeholder="Uraikan kasus atau layanan yang diberikan">{{ old('deskripsi', $counseling->deskripsi) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Tindak Lanjut <span class="text-muted fw-normal">(isi jika sudah ditangani)</span></label>
                    <textarea name="tindak_lanjut" rows="3" class="form-control rounded-3"
                              placeholder="Kosongkan jika belum ada tindak lanjut">{{ old('tindak_lanjut', $counseling->tindak_lanjut) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('counselings.index') }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-warning text-dark fw-semibold rounded-3 px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Catatan' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection