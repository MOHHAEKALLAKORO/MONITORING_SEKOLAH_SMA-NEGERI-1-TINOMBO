<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Http;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data siswa beserta nilai untuk rekap raport
        $students = Student::with(['grades.subject', 'grades.academicYear'])->orderBy('name')->paginate(10);

        return view('report-cards.index', compact('students'));
    }

    public function show($id)
    {
        // Mengambil data siswa beserta relasi nilai dan catatan konseling
        $student = Student::with(['grades.subject', 'grades.academicYear', 'counselings'])->findOrFail($id);

        // Mengambil data konseling untuk siswa ini
        $counselings = $student->counselings ?? [];

        // Variabel rekap kehadiran
        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlpa = 0;

        return view('report-cards.show', compact(
            'student', 
            'counselings', 
            'totalHadir', 
            'totalSakit', 
            'totalIzin', 
            'totalAlpa'
        ));
    }

    public function sendWhatsApp($id)
    {
        $student = Student::findOrFail($id);

        // Sesuaikan nama kolom jika di database Anda bukan 'phone' (misal: $student->no_hp)
        $targetPhone = $student->phone ?? null; 

        if (!$targetPhone) {
            return back()->with('error', 'Nomor telepon untuk siswa ' . $student->name . ' kosong di database.');
        }

        try {
            $response = Http::timeout(10)->post('http://localhost:3000/send-message', [
                'phone' => $targetPhone,
                'message' => 'Halo ' . $student->name . ', berikut adalah tautan rekap hasil belajar semester Anda.'
            ]);

            if ($response->successful() && $response->json('status') === true) {
                return back()->with('success', 'Pesan WhatsApp berhasil dikirim ke ' . $student->name . '!');
            }

            $errorMsg = $response->json('message') ?? 'Gagal dari server Node.js';
            return back()->with('error', 'Gateway Error: ' . $errorMsg);
            
        } catch (\Exception $e) {
            // Ini akan memunculkan pesan error persis ke layar jika koneksi ke port 3000 gagal
            return back()->with('error', 'Koneksi ke Node.js gagal: ' . $e->getMessage());
        }
    }
}