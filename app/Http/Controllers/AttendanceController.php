<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        [$tanggal, $kelas, $search] = $this->filters($request);

        $base = $this->baseQuery($tanggal, $kelas, $search);

        $attendances = (clone $base)->with('student')->get()
            ->sortBy(fn ($a) => $a->student->name ?? '')
            ->values();

        // Angka kartu: dihitung dari data pada tanggal & filter yang dipilih
        $counts = (clone $base)->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $stat = [];
        foreach (Attendance::STATUS as $s) {
            $stat[$s] = (int) ($counts[$s] ?? 0);
        }

        $classes = Student::whereNotNull('class')->distinct()->orderBy('class')->pluck('class');

        return view('attendances.index', compact('attendances', 'stat', 'classes', 'tanggal', 'kelas', 'search'));
    }

    public function create(Request $request)
    {
        return view('attendances.form', [
            'attendance' => new Attendance(['tanggal' => $this->normalizeDate($request->input('tanggal'))]),
            'students'   => Student::orderBy('name')->get(),
            'statusList' => Attendance::STATUS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Satu siswa hanya punya satu catatan per tanggal (kalau sudah ada, diperbarui)
        Attendance::updateOrCreate(
            ['student_id' => $data['student_id'], 'tanggal' => $data['tanggal']],
            ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null]
        );

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil disimpan!');
    }

    public function edit(Attendance $attendance)
    {
        return view('attendances.form', [
            'attendance' => $attendance,
            'students'   => Student::orderBy('name')->get(),
            'statusList' => Attendance::STATUS,
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $this->validated($request);
        $attendance->update($data);

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil diperbarui!');
    }

    public function destroy(Attendance $attendance)
    {
        $tanggal = $attendance->tanggal?->format('Y-m-d');
        $attendance->delete();

        return redirect()->route('attendances.index', ['tanggal' => $tanggal])
            ->with('success', 'Presensi berhasil dihapus!');
    }

    /** Unduh rekap sebagai CSV (bisa dibuka di Excel), sesuai filter yang aktif. */
    public function export(Request $request)
    {
        [$tanggal, $kelas, $search] = $this->filters($request);

        $rows = $this->baseQuery($tanggal, $kelas, $search)->with('student')->get()
            ->sortBy(fn ($a) => $a->student->name ?? '')
            ->values();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['Tanggal', 'NISN', 'Nama Siswa', 'Kelas', 'Status', 'Keterangan'], ';');
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->tanggal?->format('d/m/Y'),
                    $r->student->nisn ?? '',
                    $r->student->name ?? '',
                    $r->student->class ?? '',
                    $r->status,
                    $r->keterangan,
                ], ';');
            }
            fclose($out);
        }, 'rekap-presensi-' . $tanggal . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ---------- helper ----------

    private function filters(Request $request): array
    {
        return [
            $this->normalizeDate($request->input('tanggal')),
            $request->input('kelas'),
            $request->input('search'),
        ];
    }

    private function normalizeDate(?string $value): string
    {
        return ($value && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
            ? $value
            : now()->toDateString();
    }

    private function baseQuery(string $tanggal, ?string $kelas, ?string $search)
    {
        return Attendance::query()
            ->whereDate('tanggal', $tanggal)
            ->when($kelas, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('class', $kelas)))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('student', function ($s) use ($search) {
                    $s->where(function ($w) use ($search) {
                        $w->where('name', 'like', '%' . $search . '%')
                          ->orWhere('nisn', 'like', '%' . $search . '%');
                    });
                });
            });
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'student_id' => 'required|exists:students,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:' . implode(',', Attendance::STATUS),
            'keterangan' => 'nullable|string',
        ]);
    }
}