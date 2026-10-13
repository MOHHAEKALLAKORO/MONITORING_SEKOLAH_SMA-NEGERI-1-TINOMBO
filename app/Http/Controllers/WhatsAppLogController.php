<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppLog;
use App\Services\WhatsAppSender;
use Illuminate\Http\Request;

class WhatsAppLogController extends Controller
{
    public function index()
    {
        $logs = WhatsAppLog::latest()->limit(100)->get();

        $stat = [
            'total'    => WhatsAppLog::count(),
            'berhasil' => WhatsAppLog::where('status', WhatsAppLog::STATUS_BERHASIL)->count(),
            'gagal'    => WhatsAppLog::where('status', WhatsAppLog::STATUS_GAGAL)->count(),
            'menunggu' => WhatsAppLog::where('status', WhatsAppLog::STATUS_MENUNGGU)->count(),
        ];

        $types = WhatsAppLog::TYPES;

        return view('whatsapp-logs.index', compact('logs', 'stat', 'types'));
    }

    public function store(Request $request, WhatsAppSender $sender)
    {
        $data = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'phone'          => ['required', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
            'type'           => 'required|in:' . implode(',', WhatsAppLog::TYPES),
            'message'        => 'required|string|max:1000',
        ], [
            'phone.regex' => 'Nomor tujuan tidak valid. Contoh: +62 812-3456-7890',
        ]);

        // Catat dulu, lalu kirim, lalu perbarui statusnya
        $log = WhatsAppLog::create($data + ['status' => WhatsAppLog::STATUS_MENUNGGU]);

        $result = $sender->send($data['phone'], $data['message']);

        $log->update([
            'status' => $result['ok'] ? WhatsAppLog::STATUS_BERHASIL : WhatsAppLog::STATUS_GAGAL,
        ]);

        if ($result['ok']) {
            return redirect()->route('whatsapp.logs')
                ->with('success', 'Pesan diterima layanan WhatsApp dan sedang dikirim.');
        }

        return redirect()->route('whatsapp.logs')
            ->withErrors(['send' => 'Pesan gagal dikirim: ' . $result['reason']]);
    }
}
