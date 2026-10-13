<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti 'email_anda@contoh.com' dengan email yang persis Anda ketik di form login
        $user = User::where('email', 'adminsekolah01@gmail.com')->first();

        if ($user) {
            $user->password = Hash::make('@AdminSekolah00');
            $user->role = 'admin';
            $user->save();
        } else {
            User::create([
                'name' => 'Administrator',
                'email' => 'adminsekolah01@gmail.com',
                'password' => Hash::make('@AdminSekolah00'),
                'role' => 'admin',
            ]);
        }
    }
}