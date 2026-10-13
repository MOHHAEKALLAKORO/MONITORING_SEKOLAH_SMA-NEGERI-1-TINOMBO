@extends('layouts.app')

@section('content')
@php $isEdit = $schedule->exists; @endphp
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">{{ $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('schedules.update', $schedule) : route('schedules.store') }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Hari</label>
                    <select name="hari" class="form-select rounded-3" required>
                        @foreach ($hariList as $h)
                            <option value="{{ $h }}" {{ old('hari', $schedule->hari) === $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control rounded-3" required
                           value="{{ old('jam_mulai', $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control rounded-3" required
                           value="{{ old('jam_selesai', $schedule->jam_selesai ? substr($schedule->jam_selesai, 0, 5) : '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($subjects as $m)
                            <option value="{{ $m->id }}" {{ (string) old('subject_id', $schedule->subject_id) === (string) $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" list="daftarKelas" class="form-control rounded-3" required
                           placeholder="Contoh: X-IPA 1"
                           value="{{ old('kelas', $schedule->kelas) }}">
                    <datalist id="daftarKelas">
                        @foreach ($classes as $c)
                            <option value="{{ $c }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ruangan</label>
                    <input type="text" name="ruangan" class="form-control rounded-3" required
                           placeholder="Contoh: Ruang 03"
                           value="{{ old('ruangan', $schedule->ruangan) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('schedules.index', ['hari' => old('hari', $schedule->hari)]) }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Jadwal' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection