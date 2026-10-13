<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';

    public const STATUS_BERHASIL = 'Berhasil';
    public const STATUS_GAGAL    = 'Gagal';
    public const STATUS_MENUNGGU = 'Menunggu';

    public const TYPES = [
        'Presensi Masuk',
        'Pengumuman Sekolah',
        'Tagihan Keuangan',
        'Catatan BK',
        'Lainnya',
    ];

    protected $fillable = [
        'recipient_name',
        'phone',
        'type',
        'message',
        'status',
    ];
}
