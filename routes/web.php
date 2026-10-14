<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AcademicSettingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CounselingController;
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
use App\Http\Controllers\WhatsAppController;
// --- Login / Logout ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    
    Route::get('/login/kepala-sekolah', [AuthController::class, 'showLogin'])
        ->defaults('portal', 'kepala_sekolah')->name('login.kepala');
    Route::post('/login/kepala-sekolah', [AuthController::class, 'login'])
        ->defaults('portal', 'kepala_sekolah')->middleware('throttle:5,1');
    
    Route::get('/login/wali-kelas', [AuthController::class, 'showLogin'])
        ->defaults('portal', 'wali_kelas')->name('login.wali');
    Route::post('/login/wali-kelas', [AuthController::class, 'login'])
        ->defaults('portal', 'wali_kelas')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- Dashboard & Umum ---
Route::get('/', [StudentController::class, 'dashboard']);
Route::get('/dashboard', [StudentController::class, 'dashboard']);
Route::get('/kelas-saya', [HomeroomController::class, 'index'])->middleware('auth')->name('homeroom.index');

// --- Presensi / Attendance ---
Route::middleware(['auth'])->group(function () {
    Route::get('presensi/export', [AttendanceController::class, 'export'])->name('presensi.export');
    Route::post('presensi/import', [AttendanceController::class, 'import'])->name('attendances.import');
    Route::resource('presensi', AttendanceController::class)
        ->except('show')
        ->names('presensi')
        ->parameters(['presensi' => 'attendance']);
});

// --- Data Siswa ---
Route::middleware(['auth'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create']);
    Route::post('/students', [StudentController::class, 'store']);
});

// --- Jadwal Pelajaran ---
Route::resource('jadwal', ScheduleController::class)
    ->except('show')
    ->names('schedules')
    ->parameters(['jadwal' => 'schedule']);

// --- Buku BK (Konseling) ---
Route::get('/buku-bk', [CounselingController::class, 'index'])->name('counselings.index');
Route::resource('buku-bk', CounselingController::class)
    ->names('counselings')
    ->parameters(['buku-bk' => 'counseling']);

// --- Forum ---
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

// --- Kelola Pengguna (Khusus Admin) ---
Route::resource('pengguna', UserController::class)
    ->except('show')
    ->names('users')
    ->parameters(['pengguna' => 'user'])
    ->middleware(['auth', EnsureAdmin::class]);

// --- Akademik & Pengaturan Mapel ---
Route::get('/akademik', [AcademicController::class, 'index'])->name('academic.index');
Route::post('/akademik', [AcademicController::class, 'store'])->name('academic.store');
Route::get('/akademik/create', [AcademicController::class, 'create'])->name('Akademik.create');
Route::get('/akademik/pengaturan', [AcademicSettingController::class, 'index'])->name('academic.settings');
Route::post('/akademik/mapel', [AcademicSettingController::class, 'storeSubject'])->name('subjects.store');
Route::delete('/akademik/mapel/{id}', [AcademicSettingController::class, 'destroySubject'])->name('subjects.destroy');
Route::post('/akademik/tahun-ajaran', [AcademicSettingController::class, 'storeAcademicYear'])->name('academic_years.store');
Route::delete('/akademik/tahun-ajaran/{id}', [AcademicSettingController::class, 'destroyAcademicYear'])->name('academic_years.destroy');

// --- Ekstrakurikuler ---
Route::get('/ekskul', [ExtracurricularController::class, 'index'])->name('extracurriculars.index');
Route::post('/ekskul', [ExtracurricularController::class, 'store'])->name('extracurriculars.store');
Route::delete('/ekskul/{id}', [ExtracurricularController::class, 'destroy'])->name('extracurriculars.destroy');

// --- Laporan ---
Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan-bulanan', [ReportController::class, 'index']);

// --- Log WhatsApp ---
Route::get('/log-whatsapp', [WhatsAppLogController::class, 'index'])->name('whatsapp.logs');
Route::post('/log-whatsapp', [WhatsAppLogController::class, 'store'])->name('whatsapp.logs.store');
Route::get('/report-cards/{id}/send-wa', [ReportCardController::class, 'sendWhatsApp'])->name('report-cards.send-wa');

// --- Raport ---
Route::get('/raport', [ReportCardController::class, 'index'])->name('report-cards.index');
Route::get('/raport-semester', [ReportCardController::class, 'index']);
Route::get('/raport-semester/{id}', [ReportCardController::class, 'show'])->name('report-cards.show');
Route::get('/report-cards/{id}/send-wa', [ReportCardController::class, 'sendWhatsApp'])->name('report-cards.send-wa');

// --- Pengaturan Akun ---
Route::get('/pengaturan', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/pengaturan-akun', [ProfileController::class, 'index']);
Route::post('/pengaturan', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/test-wa', [WhatsAppController::class, 'sendWhatsAppNotification']);
Route::get('/kirim-wa', [WhatsAppController::class, 'kirimPesanWhatsApp']);