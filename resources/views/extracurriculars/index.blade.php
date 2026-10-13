@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-info-subtle text-info-emphasis px-3 py-1 rounded-pill mb-2 fw-semibold">Pengembangan Diri</span>
            <h2 class="fw-bold text-dark mb-1">Ekskul &amp; Prestasi</h2>
            <p class="text-muted mb-0">Catatan keikutsertaan ekstrakurikuler dan prestasi siswa SMA Negeri 1 Tinombo</p>
        </div>
        <button type="button" class="btn btn-success fw-semibold rounded-3 px-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </button>
    </div>

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

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Total Data Keikutsertaan</div>
                <h4 class="fw-bold text-dark mb-0">{{ $stat['total'] }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Siswa yang Terlibat</div>
                <h4 class="fw-bold text-success mb-0">{{ $stat['siswa'] }} Siswa</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-muted small">Jenis Ekskul</div>
                <h4 class="fw-bold text-dark mb-0">{{ $stat['ekskul'] }} Ekskul</h4>
            </div>
        </div>
    </div>

    <!-- Filter + tabel -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="p-4 border-bottom bg-light-subtle">
            <form method="GET" action="{{ route('extracurriculars.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari nama siswa, NIS, atau ekskul...">
                </div>
                <div class="col-md-3">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-search"></i> Cari</button>
                    @if ($q || $tahun)
                        <a href="{{ route('extracurriculars.index') }}" class="btn btn-sm btn-light border" title="Hapus filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Nama Siswa</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Ekskul</th>
                        <th class="py-3">Jabatan</th>
                        <th class="py-3">Tahun Ajaran</th>
                        <th class="py-3">Keterangan / Prestasi</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $i => $e)
                    <tr>
                        <td class="ps-4 text-secondary">{{ $i + 1 }}</td>
                        <td>
                            <h6 class="fw-bold text-dark mb-0">{{ $e->student->name ?? '-' }}</h6>
                            <small class="text-muted">NIS: {{ $e->student->nis ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $e->student->class ?? '-' }}</span></td>
                        <td class="fw-semibold text-dark">{{ $e->nama_ekskul }}</td>
                        <td>{{ $e->jabatan ?: '-' }}</td>
                        <td>{{ $e->tahun_ajaran }}</td>
                        <td class="text-muted small" style="max-width: 260px;">{{ $e->keterangan ?: '-' }}</td>
                        <td class="pe-4 text-end">
                            <form action="{{ route('extracurriculars.destroy', $e->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus data ekskul {{ $e->nama_ekskul }} milik {{ $e->student->name ?? '' }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            {{ ($q || $tahun) ? 'Tidak ada data yang cocok.' : 'Belum ada data ekskul. Klik "Tambah Data" untuk mulai.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('extracurriculars.store') }}" method="POST" class="modal-content rounded-4">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Data Ekskul / Prestasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Siswa</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $s)
                                <option value="{{ $s->id }}" {{ (string) old('student_id') === (string) $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} — {{ $s->class }} (NIS: {{ $s->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Ekskul</label>
                        <input type="text" name="nama_ekskul" list="daftarEkskul" class="form-control" required
                               placeholder="Contoh: Pramuka" value="{{ old('nama_ekskul') }}">
                        <datalist id="daftarEkskul">
                            @foreach ($ekskulList as $n)
                                <option value="{{ $n }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" name="jabatan" list="daftarJabatan" class="form-control"
                               placeholder="Contoh: Anggota" value="{{ old('jabatan') }}">
                        <datalist id="daftarJabatan">
                            <option value="Ketua">
                            <option value="Wakil Ketua">
                            <option value="Sekretaris">
                            <option value="Bendahara">
                            <option value="Anggota">
                        </datalist>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" list="daftarTahun" class="form-control" required
                               placeholder="2026/2027" value="{{ old('tahun_ajaran', $tahunAktif) }}">
                        <datalist id="daftarTahun">
                            @foreach ($tahunList as $t)
                                <option value="{{ $t }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan / Prestasi <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="keterangan" rows="3" class="form-control"
                                  placeholder="Contoh: Juara 2 lomba tingkat kabupaten">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalTambah')).show();
    });
</script>
@endif
@endsection