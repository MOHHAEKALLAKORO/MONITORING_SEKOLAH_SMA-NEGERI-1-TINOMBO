<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Pengirim WhatsApp lewat Fonnte (https://docs.fonnte.com).
 * Token disimpan di .env sebagai FONNTE_TOKEN.
 */
class WhatsAppSender
{
    /** @return array{ok: bool, reason: ?string} */
    public function send(string $phone, string $message): array
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            return ['ok' => false, 'reason' => 'FONNTE_TOKEN belum diisi di file .env'];
        }

        try {
            $response = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->timeout(20)
                ->post('https://api.fonnte.com/send', [
                    'target'  => $this->normalize($phone),
                    'message' => $message,
                ]);

            $json = $response->json() ?? [];

            if ($response->successful() && ($json['status'] ?? false) === true) {
                return ['ok' => true, 'reason' => null];
            }

            return ['ok' => false, 'reason' => $json['reason'] ?? ('HTTP ' . $response->status())];
        } catch (\Throwable $e) {
            return ['ok' => false, 'reason' => $e->getMessage()];
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
