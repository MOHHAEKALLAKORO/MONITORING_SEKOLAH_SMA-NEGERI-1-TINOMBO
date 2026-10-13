<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Support\Roles;
use Illuminate\Http\Request;

/** Halaman "Kelas Saya" untuk wali kelas. */
class HomeroomController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->role === Roles::WALI_KELAS, 403, 'Halaman ini khusus Wali Kelas.');

        $kelas = $user->kelas;
        $today = now()->toDateString();

        $students = collect();
        $todayByStudent = collect();
        $stat = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0, 'Belum' => 0];

        if ($kelas) {
            $students = Student::where('class', $kelas)->orderBy('name')->get();

            $todayByStudent = Attendance::whereDate('tanggal', $today)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');

            foreach ($students as $s) {
                $status = $todayByStudent[$s->id]->status ?? null;
                if ($status && isset($stat[$status])) {
                    $stat[$status]++;
                } else {
                    $stat['Belum']++;
                }
            }
        }

        return view('homeroom.index', compact('kelas', 'today', 'students', 'todayByStudent', 'stat'));
    }
}