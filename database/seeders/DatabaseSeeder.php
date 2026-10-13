<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student; // <-- Tambahkan baris ini di atas
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data Contoh Siswa
        Student::create([
            'nisn' => '99999',
            'name' => 'Bagus',
            'class' => 'X-IPA 3',
            'parent_phone' => '08123456789',
        ]);

        Student::create([
            'nisn' => '99998',
            'name' => 'Siti Aminah',
            'class' => 'X-IPA 3',
            'parent_phone' => '08123456788',
        ]);
    }
}