@extends('layouts.app') 

@section('content')
<div class="container-fluid px-4">
    
    <!-- 1. Bagian Header / Judul Halaman -->
    <div class="mb-4">
        <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold small">Manajemen Akademik</span>
        <h2 class="fw-bold text-dark mt-2">Pengelolaan Nilai Siswa</h2>
        <p class="text-muted">Kelola, pantau, dan evaluasi nilai mata pelajaran siswa SMA Negeri 1 Tinombo</p>
    </div>

    <!-- 2. Bagian Tombol Aksi & Filter Pencarian (Berada di atas secara penuh) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Filter Data Nilai</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light border rounded-3 px-3 py-2 text-secondary fw-medium shadow-sm">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Import Nilai
                </button>
                <a href="{{ route('Akademik.create') }}" class="btn btn-success rounded-3 px-3 py-2 fw-medium shadow-sm text-decoration-none">
                    <i class="bi bi-plus-lg me-1"></i> Input Nilai Baru
                </a>
            </div>
        </div>

        <form method="GET" action="{{ url('/akademik') }}">
            <div class="row align-items-end g-3">
                <!-- Pilihan Kelas -->
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Pilih Kelas</label>
                    <select name="class" class="form-select rounded-3">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $item)
                            <option value="{{ $item->class }}" {{ request('class') == $item->class ? 'selected' : '' }}>
                                {{ $item->class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mata Pelajaran -->
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Mata Pelajaran</label>
                    <select name="subject" class="form-select rounded-3">
                <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                 <option value="{{ $subject->id }}" {{ (string) $selectedSubject === (string) $subject->id ? 'selected' : '' }}>
                   {{ $subject->nama_mapel }}
                </option>
                  @endforeach
               </select>
                </div>

                <!-- Tombol Filter -->
                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark w-100 rounded-3 py-2">
                        <i class="bi bi-search me-1"></i> Filter Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Tabel Data Nilai Siswa (Lebar penuh ke bawah) -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Daftar Nilai Akademik</h5>
            <span class="text-muted small">Menampilkan data semester ini (KKM: 75)</span>
        </div>
       <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-secondary small text-uppercase">
            <tr>
                <th class="ps-4 py-3">Siswa</th>
                <th class="py-3">Kelas</th>
                <th class="py-3 text-center">Nilai Tugas</th>
                <th class="py-3 text-center">Nilai UTS</th>
                <th class="py-3 text-center">Nilai UAS</th>
                <th class="py-3 text-center">Nilai Akhir</th>
                <th class="py-3 text-center">Status KKM</th>
                <th class="pe-4 py-3 text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    $grade = $student->grades->when($selectedSubject, function($q) use ($selectedSubject) {
                        return $q->where('subject_id', $selectedSubject);
                    })->first();
                @endphp
                <tr>
                    <td class="ps-4 fw-medium text-dark">
                        {{ $student->name }}
                        <div class="small text-muted">NISN: {{ $student->nisn ?? '-' }}</div>
                    </td>
                    <td>{{ $student->class }}</td>
                    <td class="text-center">{{ $grade->nilai_tugas ?? '-' }}</td>
                    <td class="text-center">{{ $grade->nilai_uts ?? '-' }}</td>
                    <td class="text-center">{{ $grade->nilai_uas ?? '-' }}</td>
                    <td class="text-center fw-bold">{{ $grade->nilai_akhir ?? '-' }}</td>
                    <td class="text-center">
                        @if($grade && $grade->nilai_akhir !== null)
                            <span class="badge {{ $grade->nilai_akhir >= 75 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $grade->nilai_akhir >= 75 ? 'Tuntas' : 'Belum Tuntas' }}
                            </span>
                        @else
                            <span class="text-muted small">Belum ada nilai</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <a href="#" class="btn btn-sm btn-light border rounded-2 text-primary" title="Edit Nilai">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data siswa untuk kelas yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection