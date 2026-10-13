<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// REKONSTRUKSI: sesuaikan jika model aslinya punya aturan lain.
class Student extends Model
{
    protected $guarded = [];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function counselings(): HasMany
    {
        return $this->hasMany(Counseling::class);
    }
}
