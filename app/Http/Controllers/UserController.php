<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/** Kelola pengguna: hanya admin (dijaga oleh middleware EnsureAdmin di route). */
class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', [
            'user'    => new User(),
            'roles'   => Roles::LABELS,
            'classes' => $this->classList(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'role'     => ['required', Rule::in(array_keys(Roles::LABELS))],
            'kelas'    => ['nullable', 'string', 'max:50', Rule::requiredIf($request->input('role') === Roles::WALI_KELAS)],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'kelas.required' => 'Kelas wajib diisi untuk akun Wali Kelas.',
        ]);

        $user = new User();
        $user->forceFill([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'kelas'    => $data['role'] === Roles::WALI_KELAS ? $data['kelas'] : null,
            'password' => Hash::make($data['password']),
        ])->save();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.form', [
            'user'    => $user,
            'roles'   => Roles::LABELS,
            'classes' => $this->classList(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(array_keys(Roles::LABELS))],
            'kelas'    => ['nullable', 'string', 'max:50', Rule::requiredIf($request->input('role') === Roles::WALI_KELAS)],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'kelas.required' => 'Kelas wajib diisi untuk akun Wali Kelas.',
        ]);

        // Cegah admin mencabut hak admin dirinya sendiri (bisa terkunci dari sistem)
        if ($user->id === $request->user()->id && $data['role'] !== Roles::ADMIN) {
            return back()->withInput()->withErrors([
                'role' => 'Anda tidak bisa mengubah peran akun Anda sendiri dari Admin.',
            ]);
        }

        $fill = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
            'kelas' => $data['role'] === Roles::WALI_KELAS ? $data['kelas'] : null,
        ];

        if (!empty($data['password'])) {
            $fill['password'] = Hash::make($data['password']);
        }

        $user->forceFill($fill)->save();

        return redirect()->route('users.index')->with('success', 'Data pengguna diperbarui!');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna dihapus.');
    }

    private function classList()
    {
        return Student::whereNotNull('class')->distinct()->orderBy('class')->pluck('class');
    }
}