<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    /** Harus sama persis dengan isi ENUM kolom `status` di database. */
    public const STATUS = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

    protected $fillable = [
        'student_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}