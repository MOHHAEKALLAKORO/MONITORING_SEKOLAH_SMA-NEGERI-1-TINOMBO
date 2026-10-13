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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->string('hari'); // Senin, Selasa, dll.
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        $table->string('kelas'); // Contoh: X IPA 1, XI IPS 2
        $table->string('ruangan')->nullable(); // Contoh: R. 03
        $table->timestamps();
    });
}
};
