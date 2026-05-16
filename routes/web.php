<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvidenceController as AdminEvidenceController;
use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\WaspangController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\TematikController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\EvidenceController as KaryawanEvidenceController;
use App\Http\Controllers\TeamLeader\DashboardController as TLDashboardController;
use App\Http\Controllers\TeamLeader\ProjectController as TLProjectController;
use App\Http\Controllers\TeamLeader\AssignmentController as TLAssignmentController;
use App\Http\Controllers\TeamLeader\MappingController as TLMappingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/kelolauser', KelolaUserController::class)->names('kelolauser');
    Route::resource('/evidence', AdminEvidenceController::class)->names('evidence');
    Route::resource('/laporan', LaporanController::class)->names('laporan');
    Route::resource('/waspang', WaspangController::class)->names('waspang');
    Route::resource('/po', PurchaseOrderController::class)->names('po');
    Route::resource('/tematik', TematikController::class)->names('tematik');
});

Route::middleware('auth')->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/evidence', KaryawanEvidenceController::class)->names('evidence');
});

Route::middleware('auth')->prefix('teamleader')->name('teamleader.')->group(function () {
    Route::get('/dashboard', [TLDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/project', TLProjectController::class)->names('project');
    Route::resource('/assignment', TLAssignmentController::class)->names('assignment');
    Route::resource('/mapping', TLMappingController::class)->names('mapping');
});