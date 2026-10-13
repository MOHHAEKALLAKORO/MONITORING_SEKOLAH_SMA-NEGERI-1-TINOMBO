<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\AcademicYear; // Tambahkan import AcademicYear di sini

class AcademicController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil daftar kelas dan mata pelajaran untuk filter
        $classes = Student::whereNotNull('class')->select('class')->distinct()->orderBy('class')->get();
        $subjects = Subject::orderBy('id')->get(); 
        
        $selectedClass = $request->input('kelas', 'X-IPA 3');
        $selectedSubject = $request->input('mapel', '');

        $students = Student::when($selectedClass, function($query) use ($selectedClass) {
            $query->where('class', $selectedClass);
        })->with(['grades.subject', 'grades.academicYear'])->get();

        return view('academic.index', compact('classes', 'subjects', 'students', 'selectedClass', 'selectedSubject'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => 'required|exists:students,id',
            'subject_id'       => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'nilai_tugas'      => 'nullable|numeric',
            'nilai_uts'        => 'nullable|numeric',
            'nilai_uas'        => 'nullable|numeric',
            'nilai_akhir'      => 'nullable|numeric',
            'predikat'         => 'nullable|string',
        ]);

        Grade::updateOrCreate(
            [
                'student_id'       => $data['student_id'], 
                'subject_id'       => $data['subject_id'],
                'academic_year_id' => $data['academic_year_id']
            ],
            $data
        );

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    public function create()
    {
        // Mengambil data siswa, mata pelajaran, dan tahun ajaran untuk form input nilai
        $students = Student::orderBy('id')->get();
        $subjects = Subject::orderBy('id')->get();
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();

        return view('academic.create', compact('students', 'subjects', 'academicYears'));
    }
}