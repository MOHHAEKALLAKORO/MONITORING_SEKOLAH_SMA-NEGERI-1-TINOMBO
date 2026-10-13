<?php

namespace App\Http\Controllers;

use App\Models\Counseling;
use App\Models\Student;
use Illuminate\Http\Request;

class CounselingController extends Controller
{
    // Harus sama persis dengan isi ENUM kolom `jenis` di database
    private const JENIS = ['Prestasi', 'Pelanggaran', 'Konseling'];

    public function index(Request $request)
    {
        $search = $request->input('search');

        $counselings = Counseling::with('student')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('student', function ($s) use ($search) {
                    $s->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        // Statistik bulan ini (dihitung dari database)
        $bulanIni = fn () => Counseling::whereMonth('tanggal', now()->month)
                                       ->whereYear('tanggal', now()->year);

        $totalBulanIni  = $bulanIni()->count();
        $perluPerhatian = $bulanIni()->belumDitindak()->distinct()->count('student_id');
        $selesai        = $bulanIni()->sudahDitindak()->distinct()->count('student_id');

        return view('counselings.index', compact(
            'counselings', 'search', 'totalBulanIni', 'perluPerhatian', 'selesai'
        ));
    }

    public function create()
    {
        return view('counselings.form', [
            'counseling' => new Counseling(),
            'students'   => Student::orderBy('name')->get(),
            'jenisList'  => self::JENIS,
        ]);
    }

    public function store(Request $request)
    {
        Counseling::create($this->validated($request));

        return redirect()->route('counselings.index')
            ->with('success', 'Catatan BK berhasil ditambahkan!');
    }

    public function show(Counseling $counseling)
    {
        $counseling->load('student');

        return view('counselings.show', compact('counseling'));
    }

    public function edit(Counseling $counseling)
    {
        return view('counselings.form', [
            'counseling' => $counseling,
            'students'   => Student::orderBy('name')->get(),
            'jenisList'  => self::JENIS,
        ]);
    }

    public function update(Request $request, Counseling $counseling)
    {
        $counseling->update($this->validated($request));

        return redirect()->route('counselings.index')
            ->with('success', 'Catatan BK berhasil diperbarui!');
    }

    public function destroy(Counseling $counseling)
    {
        $counseling->delete();

        return redirect()->route('counselings.index')
            ->with('success', 'Catatan BK berhasil dihapus!');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'tanggal'       => 'required|date',
            'jenis'         => 'required|in:' . implode(',', self::JENIS),
            'deskripsi'     => 'required|string',
            'poin'          => 'nullable|integer|min:0',
            'tindak_lanjut' => 'nullable|string',
        ]);

        // Aman untuk kolom yang tidak boleh NULL
        $data['poin']          = $data['poin'] ?? 0;
        $data['tindak_lanjut'] = $data['tindak_lanjut'] ?? '';

        return $data;
    }
}