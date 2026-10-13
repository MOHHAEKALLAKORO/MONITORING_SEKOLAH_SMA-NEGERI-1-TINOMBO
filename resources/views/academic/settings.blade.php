@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pengaturan Akademik</h2>
            <p class="text-muted mb-0">Kelola master data mata pelajaran dan tahun ajaran aktif sekolah.</p>
        </div>
        <a href="{{ url('/akademik') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Nilai Siswa
        </a>
    </div>

    <div class="row">
        <!-- Kolom Mata Pelajaran -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-book text-success me-2"></i>Daftar Mata Pelajaran</h5>
                    <button type="button" class="btn btn-success btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Mapel
                    </button>
                </div>
                <div class="card-body px-4">
                    @if(session('success_subject'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
                            <small>{{ session('success_subject') }}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light fs-7 text-secondary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Mata Pelajaran</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">{{ $subject->kode_mapel }}</span></td>
                                    <td class="fw-medium text-dark">{{ $subject->nama_mapel }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Belum ada mata pelajaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Tahun Ajaran -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-event text-success me-2"></i>Tahun Ajaran & Semester</h5>
                    <button type="button" class="btn btn-success btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addYearModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Tahun
                    </button>
                </div>
                <div class="card-body px-4">
                    @if(session('success_year'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
                            <small>{{ session('success_year') }}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light fs-7 text-secondary">
                                <tr>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($academicYears as $year)
                                <tr>
                                    <td class="fw-medium text-dark">{{ $year->tahun_ajaran }}</td>
                                    <td>{{ $year->semester }}</td>
                                    <td class="text-center">
                                        @if($year->is_active)
                                            <span class="badge bg-success-subtle text-success px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('academic_years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Belum ada tahun ajaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Mata Pelajaran -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Tambah Mata Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Kode Mapel</label>
                        <input type="text" name="kode_mapel" class="form-control rounded-3" placeholder="Contoh: MAT-10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-control rounded-3" placeholder="Contoh: Matematika" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Tahun Ajaran -->
<div class="modal fade" id="addYearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Tambah Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('academic_years.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control rounded-3" placeholder="Contoh: 2026/2027" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Semester</label>
                        <select name="semester" class="form-select rounded-3" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck">
                        <label class="form-check-label text-secondary" for="isActiveCheck">
                            Jadikan Tahun Ajaran Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection