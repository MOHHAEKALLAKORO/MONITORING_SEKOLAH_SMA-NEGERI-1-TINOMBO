<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Pengirim WhatsApp lewat Gateway Lokal (Baileys / Node.js).
 */
class WhatsAppSender
{
    /** @return array{ok: bool, reason: ?string} */
    public function send(string $phone, string $message): array
    {
        try {
            // Mengirim request ke gateway lokal Node.js (port 3000)
            $response = Http::timeout(20)
                ->post('http://localhost:3000/send-message', [
                    'phone'   => $this->normalize($phone),
                    'message' => $message,
                ]);

            $json = $response->json() ?? [];

            // Memeriksa respons sukses dari gateway lokal
            if ($response->successful() && ($json['status'] ?? false) === true) {
                return ['ok' => true, 'reason' => null];
            }

            return ['ok' => false, 'reason' => $json['message'] ?? $json['reason'] ?? ('HTTP ' . $response->status())];
        } catch (\Throwable $e) {
            return ['ok' => false, 'reason' => 'Server WhatsApp Gateway (Node.js) belum menyala: ' . $e->getMessage()];
        }
    }

    /** 0812-3456-7890 / +62 812... → 628123456789 (hanya angka). */
    private function normalize(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits;
    }
}