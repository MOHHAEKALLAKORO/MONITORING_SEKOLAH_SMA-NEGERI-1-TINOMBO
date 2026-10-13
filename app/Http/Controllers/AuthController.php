<?php

namespace App\Http\Controllers;

use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Halaman login. $portal diisi lewat route defaults:
     * null = login umum, 'kepala_sekolah' / 'wali_kelas' = login khusus peran.
     */
    public function showLogin(?string $portal = null)
    {
        $config = $portal ? (Roles::PORTALS[$portal] ?? abort(404)) : null;

        return view('auth.login', ['portal' => $config]);
    }

    public function login(Request $request, ?string $portal = null)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $config = $portal ? (Roles::PORTALS[$portal] ?? abort(404)) : null;

        // Login khusus peran: hanya akun dengan peran itu yang bisa masuk
        if ($config) {
            $credentials['role'] = $config['role'];
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($this->homeFor(Auth::user()));
        }

        $message = $config
            ? 'Email atau kata sandi salah, atau akun ini bukan akun ' . $config['label'] . '.'
            : 'Email atau kata sandi salah.';

        return back()->withErrors(['email' => $message])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /** Halaman tujuan setelah login. */
    private function homeFor($user): string
    {
        return $user->role === Roles::WALI_KELAS
            ? route('homeroom.index')
            : '/';
    }
}