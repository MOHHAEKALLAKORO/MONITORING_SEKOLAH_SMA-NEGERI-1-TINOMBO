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
}