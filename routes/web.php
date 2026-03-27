<?php

use App\Http\Controllers\Act\ActivityTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProfessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WorkUnitController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\Act\ActivityScopeController;
use App\Http\Controllers\Act\ActivityMethodController;
use App\Http\Controllers\Act\MaterialTypeController;
use App\Http\Controllers\Act\BatchController;
use App\Http\Controllers\Act\ActivityFormatController;
use App\Http\Controllers\Act\TargetParticipantController;
use App\Http\Controllers\User\EmploymentTypeController;

use App\Http\Controllers\Act\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityNameController;
use App\Http\Controllers\UsulanDiklatController;
use Inertia\Inertia;

// Authentication Routes
Route::get('/test', function () {
    return Inertia::render('Test');
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Menu Layout Placeholder Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/usulan-diklat', [UsulanDiklatController::class, 'index'])->name('usulan-diklat');

    // Tabbed Pages Routes
    Route::get('/usulan-diklat/{activity}', [UsulanDiklatController::class, 'showKegiatan'])->name('usulan-diklat.show');
    Route::get('/usulan-diklat/{activity}/sasaran-profesi', [UsulanDiklatController::class, 'showSasaranProfesi'])->name('usulan-diklat.sasaran-profesi');
    Route::get('/usulan-diklat/{activity}/kak', [UsulanDiklatController::class, 'showKak'])->name('usulan-diklat.kak');
    Route::get('/usulan-diklat/{activity}/{tab}', [UsulanDiklatController::class, 'showUnderConstruction'])
        ->name('usulan-diklat.under-construction')
        ->where('tab', 'materi|narasumber|peserta|pengiriman|penilaian|sertifikat');

    // API Endpoints
    Route::post('/usulan-diklat/{activity}/professions', [UsulanDiklatController::class, 'storeProfession'])->name('usulan-diklat.professions.store');
    Route::delete('/usulan-diklat/{activity}/professions/{id}', [UsulanDiklatController::class, 'destroyProfession'])->name('usulan-diklat.professions.destroy');
    Route::view('/manajemen-sasaran-profesi', 'ManajemenSasaranProfesi')->name('manajemen-sasaran-profesi');
    Route::view('/monitoring-jpl', 'monitoringJpl')->name('monitoring-jpl');
    Route::view('/pagu', 'pagu')->name('pagu');
    Route::view('/input-nilai', 'usulan/detail/penilaian')->name('input-nilai');
    Route::view('/laporan-kegiatan', 'laporanKegiatan')->name('laporan-kegiatan');
    Route::view('/evaluasi1', 'evaluasi1')->name('evaluasi1');
    Route::view('/evaluasi2', 'evaluasi2')->name('evaluasi2');
    Route::view('/evaluasi3', 'evaluasi3')->name('evaluasi3');
    Route::view('/bank-data', 'bankData')->name('bank-data');

    Route::get('users/import', [UserController::class, 'importView'])->name('users.import.view');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::resource('kegiatan', ActivityController::class);
    Route::resource('professions', ProfessionController::class);
    Route::resource('work-units', WorkUnitController::class);
    Route::resource('positions', PositionController::class);
    // Kegiatan Master Data
    Route::resource('dictionaries/activity-types', ActivityTypeController::class);
    Route::resource('dictionaries/material-types', MaterialTypeController::class);
    Route::resource('dictionaries/activity-scopes', ActivityScopeController::class);
    Route::resource('dictionaries/activity-formats', ActivityFormatController::class);
    Route::resource('dictionaries/target-participants', TargetParticipantController::class);
    Route::resource('dictionaries/activity-methods', ActivityMethodController::class);
    Route::resource('dictionaries/batches', BatchController::class);
    Route::resource('dictionaries/activity-names', ActivityNameController::class);
    Route::resource('employment-types', EmploymentTypeController::class);
});
