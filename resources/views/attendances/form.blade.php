@extends('layouts.app')

@section('content')
@php $isEdit = $attendance->exists; @endphp
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">{{ $isEdit ? 'Edit Presensi' : 'Catat Presensi' }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('presensi.update', $attendance) : route('presensi.store') }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Siswa</label>
                    <select name="student_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}" {{ (string) old('student_id', $attendance->student_id) === (string) $s->id ? 'selected' : '' }}>
                                {{ $s->name }} — {{ $s->class }} (NIS: {{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control rounded-3" required
                           value="{{ old('tanggal', $attendance->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Kehadiran</label>
                    <select name="status" class="form-select rounded-3" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach ($statusList as $st)
                            <option value="{{ $st }}" {{ old('status', $attendance->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Keterangan / Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea name="keterangan" rows="2" class="form-control rounded-3"
                              placeholder="Contoh: Surat dokter dilampirkan">{{ old('keterangan', $attendance->keterangan) }}</textarea>
                </div>
            </div>

            @unless ($isEdit)
                <div class="form-text mt-2">Kalau siswa sudah punya catatan di tanggal yang sama, catatan lama akan diperbarui (tidak dobel).</div>
            @endunless

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('presensi.index', ['tanggal' => old('tanggal', $attendance->tanggal?->format('Y-m-d'))]) }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Presensi' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection