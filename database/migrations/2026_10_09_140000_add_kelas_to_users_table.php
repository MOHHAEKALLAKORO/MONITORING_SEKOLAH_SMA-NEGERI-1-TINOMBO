<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kelas yang diampu wali kelas (kosong untuk peran lain)
        if (!Schema::hasColumn('users', 'kelas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('kelas', 50)->nullable()->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'kelas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kelas');
            });
        }
    }
};