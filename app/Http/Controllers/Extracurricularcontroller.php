<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// REKONSTRUKSI berdasarkan kolom tabel extracurriculars:
// student_id, nama_ekskul, jabatan, tahun_ajaran, keterangan.
// Route: extracurriculars.index (GET /ekskul), .store (POST /ekskul), .destroy (DELETE /ekskul/{id}).
class ExtracurricularController extends Controller
{
    public function index(Request $request)
    {
        $q     = trim((string) $request->input('q'));
        $tahun = $request->input('tahun');

        $items = Extracurricular::with('student')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama_ekskul', 'like', '%' . $q . '%')
                      ->orWhereHas('student', function ($s) use ($q) {
                          $s->where('name', 'like', '%' . $q . '%')
                            ->orWhere('nis', 'like', '%' . $q . '%');
                      });
                });
            })
            ->when($tahun, fn ($query) => $query->where('tahun_ajaran', $tahun))
            ->orderByDesc('id')
            ->get();

        // Ringkasan (seluruh data)
        $stat = [
            'total' => Extracurricular::count(),
            'siswa' => Extracurricular::distinct()->count('student_id'),
            'ekskul' => Extracurricular::distinct()->count('nama_ekskul'),
        ];

        $students = Student::orderBy('name')->get();

        $tahunList = Extracurricular::distinct()->pluck('tahun_ajaran')
            ->merge(DB::table('academic_years')->pluck('tahun_ajaran'))
            ->filter()->unique()->sortDesc()->values();

        $tahunAktif = DB::table('academic_years')->where('is_active', 1)->value('tahun_ajaran');

        $ekskulList = Extracurricular::distinct()->pluck('nama_ekskul')
            ->merge(['Pramuka', 'PMR', 'Paskibra', 'Basket', 'Voli', 'Futsal', 'Seni Tari', 'Pencak Silat'])
            ->unique()->values();

        return view('extracurriculars.index', compact(
            'items', 'stat', 'students', 'tahunList', 'tahunAktif', 'ekskulList', 'q', 'tahun'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'nama_ekskul'  => 'required|string|max:255',
            'jabatan'      => 'nullable|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
            'keterangan'   => 'nullable|string',
        ]);

        Extracurricular::create($data);

        return redirect()->route('extracurriculars.index')
            ->with('success', 'Data ekskul berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Extracurricular::findOrFail($id)->delete();

        return redirect()->route('extracurriculars.index')
            ->with('success', 'Data ekskul dihapus.');
    }
}