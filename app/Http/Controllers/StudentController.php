<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $students = Student::when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
        })->orderBy('name')->paginate(10);

        return view('students.index', compact('students'));
    }

    // Tambahkan method ini untuk menangani redirect setelah login / akses dashboard siswa
   public function dashboard()
{
    $totalStudents = Student::count();
    return view('dashboard', compact('totalStudents')); // Tanpa awalan 'students.'
}
    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn'  => 'required|unique:students,nisn',
            'name'  => 'required|string|max:255',
            'class' => 'required|string',
            'parent_phone'  => 'required|string|max:20',
        ]);

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }
}