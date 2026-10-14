<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function sendWhatsAppNotification()
    {
        $response = Http::post('http://localhost:3000/send-message', [
            'phone'   => '087765308058', // Ganti dengan nomor WhatsApp tujuan
            'message' => 'Halo, ini adalah pesan otomatis dari sistem sekolah.'
        ]);

        if ($response->successful()) {
            return 'Pesan WhatsApp berhasil dikirim!';
        } else {
            return 'Gagal mengirim pesan ke Node.js gateway.';
        }
    }
}