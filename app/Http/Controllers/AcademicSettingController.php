<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicSettingController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Pengaturan Akademik']);
    }

    public function storeSubject(Request $request)
    {
        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function destroySubject($id)
    {
        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function storeAcademicYear(Request $request)
    {
        return back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function destroyAcademicYear($id)
    {
        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}