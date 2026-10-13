<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
Route::get('presensi/export', [AttendanceController::class, 'export'])->name('presensi.export');
 
Route::resource('presensi', AttendanceController::class)
    ->except('show')
    ->names('presensi')
    ->parameters(['presensi' => 'attendance']);
use App\Http\Controllers\Controller;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AcademicSettingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CounselingController;
Route::resource('jadwal', ScheduleController::class)
    ->except('show')
    ->names('schedules')
    ->parameters(['jadwal' => 'schedule']);

Route::resource('buku-bk', CounselingController::class)
    ->names('counselings')
    ->parameters(['buku-bk' => 'counseling']);
 
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WhatsAppLogController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\HomeroomController;

// --- Login / Logout ---

Route::middleware('guest')->group(function () {
    // Login umum (admin, guru, dan semua peran)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
 
    // Login khusus Kepala Sekolah
    Route::get('/login/kepala-sekolah', [AuthController::class, 'showLogin'])
        ->defaults('portal', 'kepala_sekolah')->name('login.kepala');
    Route::post('/login/kepala-sekolah', [AuthController::class, 'login'])
        ->defaults('portal', 'kepala_sekolah')->middleware('throttle:5,1');
 
    // Login khusus Wali Kelas
    Route::get('/login/wali-kelas', [AuthController::class, 'showLogin'])
        ->defaults('portal', 'wali_kelas')->name('login.wali');
    Route::post('/login/wali-kelas', [AuthController::class, 'login'])
        ->defaults('portal', 'wali_kelas')->middleware('throttle:5,1');
});
 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
 
// 3) Halaman "Kelas Saya" untuk wali kelas:
Route::get('/kelas-saya', [HomeroomController::class, 'index'])
    ->middleware('auth')->name('homeroom.index');

// Rute Utama / Dashboard
Route::get('/', [StudentController::class, 'dashboard']);
Route::get('/dashboard', [StudentController::class, 'dashboard']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
 
// --- Forum (wajib login) ---
Route::middleware('auth')->prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/buat', [ForumController::class, 'create'])->name('create');
    Route::post('/', [ForumController::class, 'store'])->name('store');
    Route::delete('/balasan/{reply}', [ForumController::class, 'destroyReply'])->name('reply.destroy');
    Route::get('/{topic}', [ForumController::class, 'show'])->name('show');
    Route::delete('/{topic}', [ForumController::class, 'destroy'])->name('destroy');
    Route::post('/{topic}/pin', [ForumController::class, 'togglePin'])->name('pin');
    Route::post('/{topic}/balas', [ForumController::class, 'reply'])->name('reply');
});
 
// --- Kelola Pengguna (khusus admin) ---
Route::resource('pengguna', UserController::class)
    ->except('show')
    ->names('users')
    ->parameters(['pengguna' => 'user'])
    ->middleware(['auth', EnsureAdmin::class]);


// Rute Data Siswa & Presensi (yang sudah ada)
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create']);
Route::post('/students', [StudentController::class, 'store']);
Route::resource('jadwal', ScheduleController::class)
    ->except('show')
    ->names('schedules')
    ->parameters(['jadwal' => 'schedule']);
// Rute tambahan untuk mendukung akses /attendances
Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.list');
// Rute untuk Menu Baru di Sidebar (Halaman Sementara/Placeholder)
Route::get('/akademik', [AcademicController::class, 'index'])->name('academic.index');
Route::post('/akademik', [AcademicController::class, 'store'])->name('academic.store');
Route::get('/akademik/create', [AcademicController::class, 'create'])->name('Akademik.create');
Route::get('/buku-bk', function () { return view('placeholder', ['title' => 'Buku Penghubung BK']); });
Route::get('/ekskul', function () { return view('placeholder', ['title' => 'Ekskul & Prestasi']); });
Route::get('/laporan', function () { return view('placeholder', ['title' => 'Laporan Bulanan']); });
Route::get('/log-wa', function () { return view('placeholder', ['title' => 'Log WhatsApp']); });
Route::get('/rapor', function () { return view('placeholder', ['title' => 'Rapor Semester']); });
Route::get('/pengaturan', function () { return view('placeholder', ['title' => 'Pengaturan Akun']); });


// Rute Pengaturan Akademik (Mapel & Tahun Ajaran)
Route::get('/akademik/pengaturan', [AcademicSettingController::class, 'index'])->name('academic.settings');
Route::post('/akademik/mapel', [AcademicSettingController::class, 'storeSubject'])->name('subjects.store');
Route::delete('/akademik/mapel/{id}', [AcademicSettingController::class, 'destroySubject'])->name('subjects.destroy');
Route::post('/akademik/tahun-ajaran', [AcademicSettingController::class, 'storeAcademicYear'])->name('academic_years.store');
Route::delete('/akademik/tahun-ajaran/{id}', [AcademicSettingController::class, 'destroyAcademicYear'])->name('academic_years.destroy');




Route::get('/buku-bk', [CounselingController::class, 'index'])->name('counselings.index');
Route::resource('buku-bk', CounselingController::class)
    ->names('counselings')
    ->parameters(['buku-bk' => 'counseling']);

// Rute Ekstrakurikuler & Prestasi
Route::get('/ekskul', [ExtracurricularController::class, 'index'])->name('extracurriculars.index');
Route::post('/ekskul', [ExtracurricularController::class, 'store'])->name('extracurriculars.store');
Route::delete('/ekskul/{id}', [ExtracurricularController::class, 'destroy'])->name('extracurriculars.destroy');




// Rute Laporan Bulanan
Route::get('/laporan-bulanan', [ReportController::class, 'index'])->name('reports.index');
// Rute Laporan Bulanan (Mendukung /laporan dan /laporan-bulanan)
Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan-bulanan', [ReportController::class, 'index']);

Route::get('/log-whatsapp', [WhatsAppLogController::class, 'index'])->name('whatsapp.logs');
Route::post('/log-whatsapp', [WhatsAppLogController::class, 'store'])->name('whatsapp.logs.store');


// Rute Raport Semester yang mengarah ke Controller
Route::get('/raport', [ReportCardController::class, 'index'])->name('report-cards.index');
Route::get('/raport-semester', [ReportCardController::class, 'index']);
Route::get('/raport-semester/{id}', [ReportCardController::class, 'show'])->name('report-cards.show');

// Rute Pengaturan Akun
Route::get('/pengaturan', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/pengaturan-akun', [ProfileController::class, 'index']);
Route::post('/pengaturan', [ProfileController::class, 'update'])->name('profile.update');