@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">Input Nilai Baru Siswa</h4>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('academic.store') }}" method="POST">
            @csrf
            
            <!-- Pilih Siswa -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Siswa</label>
                <select name="student_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name ?? $student->nis }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Mata Pelajaran -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <select name="subject_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
    <label class="form-label fw-semibold">Tahun Akademik</label>
    <select name="academic_year_id" class="form-select rounded-3" required>
        <option value="">-- Pilih Tahun Akademik --</option>
        @foreach($academicYears as $year)
            <option value="{{ $year->id }}">{{ $year->tahun_ajaran }} - {{ $year->semester }}</option>
        @endforeach
    </select>
</div>

            <!--  Nilai Tugas -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai Tugas</label>
                <input type="number" name="nilai_tugas" class="form-control rounded-3" min="0" max="100" required>
            </div>
            
            <!-- Nilai UTS -->
           <div class="mb-3">
                <label class="form-label fw-semibold">Nilai UTS</label>
                <input type="number" name="nilai_uts" class="form-control rounded-3" min="0" max="100" required>
           </div>
           <!-- Nilai UAS -->
           <div class="mb-3">
                <label class="form-label fw-semibold">Nilai UAS</label>
                <input type="number" name="nilai_uas" class="form-control rounded-3" min="0" max="100" required>
           </div>
        
            <!-- Tombol Simpan -->
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ url('/akademik') }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>
@endsection