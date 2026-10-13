<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Extracurricular extends Model
{
    protected $table = 'extracurriculars';

    protected $fillable = [
        'student_id',
        'nama_ekskul',
        'jabatan',
        'tahun_ajaran',
        'keterangan',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}