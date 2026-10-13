<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function index(Request $request)
    {
        $hariList = self::HARI;

        // Default: hari ini (Senin-Jumat), selain itu Senin
        $default = $hariList[now()->dayOfWeekIso - 1] ?? 'Senin';
        $hari = $request->input('hari', $default);
        if (!in_array($hari, $hariList, true)) {
            $hari = 'Senin';
        }

        $kelas = $request->input('kelas');

        $schedules = Schedule::with('subject')
            ->where('hari', $hari)
            ->when($kelas, fn ($q) => $q->where('kelas', $kelas))
            ->orderBy('jam_mulai')
            ->get();

        $classes = $this->classList();

        return view('schedules.index', compact('schedules', 'classes', 'hariList', 'hari', 'kelas'));
    }

    public function create()
    {
        return view('schedules.form', [
            'schedule' => new Schedule(['hari' => request('hari', 'Senin')]),
            'classes'  => $this->classList(),
            'subjects' => Subject::orderBy('nama_mapel')->get(['id', 'nama_mapel']),
            'hariList' => self::HARI,
        ]);
    }

    public function store(Request $request)
    {
        Schedule::create($this->validated($request));

        return redirect()
            ->route('schedules.index', ['hari' => $request->hari])
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.form', [
            'schedule' => $schedule,
            'classes'  => $this->classList(),
            'subjects' => Subject::orderBy('nama_mapel')->get(['id', 'nama_mapel']),
            'hariList' => self::HARI,
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($this->validated($request));

        return redirect()
            ->route('schedules.index', ['hari' => $request->hari])
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $hari = $schedule->hari;
        $schedule->delete();

        return redirect()
            ->route('schedules.index', ['hari' => $hari])
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'hari'        => 'required|in:' . implode(',', self::HARI),
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'subject_id'  => 'required|exists:subjects,id',
            'kelas'       => 'required|string|max:50',
            'ruangan'     => 'required|string|max:50',
        ]);
    }

    /** Daftar kelas: dari data siswa + kelas yang sudah ada di jadwal. */
    private function classList()
    {
        $fromStudents  = Student::query()->whereNotNull('class')->distinct()->pluck('class');
        $fromSchedules = Schedule::query()->distinct()->pluck('kelas');

        return $fromStudents->merge($fromSchedules)->filter()->unique()->sort()->values();
    }
}