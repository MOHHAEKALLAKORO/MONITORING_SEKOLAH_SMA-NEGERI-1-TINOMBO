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
    Schema::create('counselings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->date('tanggal');
        $table->enum('jenis', ['Prestasi', 'Pelanggaran', 'Konseling']);
        $table->text('deskripsi');
        $table->integer('poin')->default(0); // Positif untuk prestasi, negatif/positif untuk pelanggaran
        $table->string('tindak_lanjut')->nullable();
        $table->timestamps();
    });
}
};
