@extends('layouts.app')

@section('content')
@php $isEdit = $user->exists; @endphp
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" method="POST" autocomplete="off">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="name" class="form-control rounded-3" required value="{{ old('name', $user->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email (dipakai untuk login)</label>
                    <input type="email" name="email" class="form-control rounded-3" required value="{{ old('email', $user->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Peran</label>
                    <select name="role" id="role" class="form-select rounded-3" required>
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}" {{ old('role', $user->role ?? 'guru') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" id="kelasWrap" style="display: none;">
                    <label class="form-label fw-semibold">Kelas yang Diampu <span class="text-muted fw-normal">(khusus Wali Kelas)</span></label>
                    <input type="text" name="kelas" id="kelas" list="daftarKelas" class="form-control rounded-3"
                           placeholder="Contoh: X-IPA 1" value="{{ old('kelas', $user->kelas) }}">
                    <datalist id="daftarKelas">
                        @foreach ($classes as $c)
                            <option value="{{ $c }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kata Sandi {!! $isEdit ? '<span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span>' : '' !!}</label>
                    <input type="password" name="password" class="form-control rounded-3" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password">
                </div>
            </div>
            <div class="form-text mt-2">Kata sandi minimal 8 karakter.</div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-success rounded-3 px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pengguna' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        var role = document.getElementById('role');
        var wrap = document.getElementById('kelasWrap');
        var kelas = document.getElementById('kelas');
        function toggle() {
            var wali = role.value === 'wali_kelas';
            wrap.style.display = wali ? '' : 'none';
            kelas.required = wali;
        }
        role.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endsection