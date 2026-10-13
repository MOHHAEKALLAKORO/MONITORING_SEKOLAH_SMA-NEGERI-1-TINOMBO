<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Counseling extends Model
{
    protected $table = 'counselings';

    protected $fillable = [
        'student_id',
        'tanggal',
        'jenis',
        'deskripsi',
        'poin',
        'tindak_lanjut',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /** Catatan yang sudah punya tindak lanjut. */
    public function scopeSudahDitindak(Builder $q): Builder
    {
        return $q->whereNotNull('tindak_lanjut')->where('tindak_lanjut', '!=', '');
    }

    /** Catatan yang belum punya tindak lanjut. */
    public function scopeBelumDitindak(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $w->whereNull('tindak_lanjut')->orWhere('tindak_lanjut', '');
        });
    }

    public function getSudahDitindakAttribute(): bool
    {
        return filled($this->tindak_lanjut);
    }
}