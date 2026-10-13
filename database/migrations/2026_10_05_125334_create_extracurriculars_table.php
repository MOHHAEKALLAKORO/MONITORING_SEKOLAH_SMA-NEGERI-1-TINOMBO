<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('extracurriculars', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->string('nama_ekskul'); // Contoh: Pramuka, Paskibra, Futsal, Basket
        $table->string('jabatan')->nullable(); // Contoh: Ketua, Anggota
        $table->string('tahun_ajaran');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}
};
