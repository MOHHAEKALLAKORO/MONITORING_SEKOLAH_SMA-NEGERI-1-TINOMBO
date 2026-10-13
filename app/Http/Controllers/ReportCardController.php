<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

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
        // (Pastikan relasi 'counselings' sudah ada di Model Student, atau ambil manual seperti di bawah)
        $counselings = $student->counselings ?? [];

        // Variabel rekap kehadiran (jika belum ada tabelnya, bisa di-set 0 sementara)
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
}