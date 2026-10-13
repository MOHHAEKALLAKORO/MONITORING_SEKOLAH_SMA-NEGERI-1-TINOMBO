<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Counseling;
use Illuminate\Http\Request;

// REKONSTRUKSI dari tampilan reports/index.blade.php
// (variabel yang dipakai: $attendances, $counselings, $bulan, $tahun).
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $attendances = Attendance::with('student')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $counselings = Counseling::with('student')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        return view('reports.index', compact('attendances', 'counselings', 'bulan', 'tahun'));
    }
}