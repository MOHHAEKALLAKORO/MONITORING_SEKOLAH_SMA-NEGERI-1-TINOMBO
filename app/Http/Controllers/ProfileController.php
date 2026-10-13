<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// Route: profile.index (GET /pengaturan) dan profile.update (POST /pengaturan).
// Satu route melayani dua formulir, dibedakan oleh input tersembunyi "section":
//   section=profile  -> ubah nama & email
//   section=password -> ubah kata sandi
class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('profile.index', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403, 'Silakan masuk terlebih dahulu.');

        if ($request->input('section') === 'password') {
            return $this->updatePassword($request, $user);
        }

        return $this->updateProfile($request, $user);
    }

    private function updateProfile(Request $request, $user)
    {
        $data = $request->validateWithBag('profile', [
            'name'  => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->forceFill([
            'name'  => $data['name'],
            'email' => $data['email'],
        ])->save();

        return back()->with('success', 'Informasi profil berhasil disimpan!');
    }

    private function updatePassword(Request $request, $user)
    {
        $data = $request->validateWithBag('password', [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sama.',
            'password.min'       => 'Kata sandi baru minimal 8 karakter.',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.'], 'password');
        }

        $user->forceFill(['password' => Hash::make($data['password'])])->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui! Gunakan kata sandi baru saat masuk berikutnya.');
    }
}