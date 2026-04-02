<?php

use App\Http\Controllers\Act\ActivityTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProfessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\RoleController;
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
use App\Http\Controllers\ProfileController; // Added ProfileController
use App\Http\Controllers\ActivityNameController;

// Authentication Routes
Route::get('/login', function () {
    return view('login');
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
    Route::get('/usulan-diklat', [\App\Http\Controllers\UsulanDiklatController::class, 'index'])->name('usulan-diklat');
    Route::get('/usulan-diklat/{id}', [\App\Http\Controllers\UsulanDiklatController::class, 'show'])->name('usulan-diklat.show');
    
    // Import Kegiatan Routes
    Route::get('/usulan-diklat/import-kegiatan/template', [\App\Http\Controllers\UsulanDiklatController::class, 'downloadTemplate'])->name('kegiatan.import.template');
    Route::get('/usulan-diklat/import-kegiatan', [\App\Http\Controllers\UsulanDiklatController::class, 'importPage'])->name('kegiatan.import.page');
    Route::post('/usulan-diklat/import-kegiatan', [\App\Http\Controllers\UsulanDiklatController::class, 'importStore'])->name('kegiatan.import.store');
    
    // Import Kegiatan Per Peserta Routes
    Route::get('/usulan-diklat/import-kegiatan-per-peserta/template', [\App\Http\Controllers\UsulanDiklatController::class, 'downloadTemplatePerPeserta'])->name('kegiatan.import-per-peserta.template');
    Route::get('/usulan-diklat/import-kegiatan-per-peserta', [\App\Http\Controllers\UsulanDiklatController::class, 'importPerPesertaPage'])->name('kegiatan.import-per-peserta.page');
    Route::post('/usulan-diklat/import-kegiatan-per-peserta', [\App\Http\Controllers\UsulanDiklatController::class, 'importPerPesertaStore'])->name('kegiatan.import-per-peserta.store');

    Route::view('/manajemen-sasaran-profesi', 'ManajemenSasaranProfesi');
    Route::get('/monitoring-jpl', [\App\Http\Controllers\MonitoringJplController::class, 'index'])->name('monitoring.jpl.index');
    Route::resource('/pagu', \App\Http\Controllers\PaguController::class);
    Route::view('/input-nilai', 'usulan/detail/penilaian');
    Route::view('/laporan-kegiatan', 'laporanKegiatan');
    Route::view('/evaluasi1', 'evaluasi1');
    Route::view('/evaluasi2', 'evaluasi2');
    Route::view('/evaluasi3', 'evaluasi3');
    Route::view('/bank-data', 'bankData');

    Route::get('users/import', [UserController::class, 'importView'])->name('users.import.view');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('kegiatan', ActivityController::class);
    Route::post('kegiatan/{kegiatan}/sasaran-profesi', [App\Http\Controllers\Act\ActivityProfessionController::class, 'store'])->name('kegiatan.sasaran-profesi.store');
    Route::delete('kegiatan/{kegiatan}/sasaran-profesi/{id}', [App\Http\Controllers\Act\ActivityProfessionController::class, 'destroy'])->name('kegiatan.sasaran-profesi.destroy');
    Route::post('kegiatan/{kegiatan}/materi', [App\Http\Controllers\Act\ActivityMaterialController::class, 'store'])->name('kegiatan.materi.store');
    Route::delete('kegiatan/{kegiatan}/materi/{id}', [App\Http\Controllers\Act\ActivityMaterialController::class, 'destroy'])->name('kegiatan.materi.destroy');
    Route::post('kegiatan/{kegiatan}/narasumber', [App\Http\Controllers\Act\ActivitySpeakerController::class, 'store'])->name('kegiatan.narasumber.store');
    Route::delete('kegiatan/{kegiatan}/narasumber/{id}', [App\Http\Controllers\Act\ActivitySpeakerController::class, 'destroy'])->name('kegiatan.narasumber.destroy');
    Route::post('kegiatan/{kegiatan}/moderator', [App\Http\Controllers\Act\ActivityModeratorController::class, 'store'])->name('kegiatan.moderator.store');
    Route::delete('kegiatan/{kegiatan}/moderator/{id}', [App\Http\Controllers\Act\ActivityModeratorController::class, 'destroy'])->name('kegiatan.moderator.destroy');
    Route::get('kegiatan/{kegiatan}/peserta/tambah', [App\Http\Controllers\Act\ActivityParticipantController::class, 'create'])->name('kegiatan.peserta.create');
    Route::post('kegiatan/{kegiatan}/peserta', [App\Http\Controllers\Act\ActivityParticipantController::class, 'store'])->name('kegiatan.peserta.store');
    Route::delete('kegiatan/{kegiatan}/peserta/{id}', [App\Http\Controllers\Act\ActivityParticipantController::class, 'destroy'])->name('kegiatan.peserta.destroy');
    Route::get('kegiatan/{kegiatan}/peserta/available-users', [App\Http\Controllers\Act\ActivityParticipantController::class, 'availableUsers'])->name('kegiatan.peserta.available-users');

    // Pengiriman (Status Tracker) Routes
    Route::post('kegiatan/{kegiatan}/submit', [App\Http\Controllers\Act\ActivityStatusController::class, 'submit'])->name('kegiatan.submit');
    Route::post('kegiatan/{kegiatan}/cancel-submit', [App\Http\Controllers\Act\ActivityStatusController::class, 'cancel'])->name('kegiatan.cancel_submit');

    // Excel Import Routes
    Route::get('kegiatan/peserta/template', [App\Http\Controllers\Act\ActivityParticipantController::class, 'downloadTemplate'])->name('kegiatan.peserta.template');
    Route::get('kegiatan/{kegiatan}/peserta/import', [App\Http\Controllers\Act\ActivityParticipantController::class, 'importPage'])->name('kegiatan.peserta.import.page');
    Route::post('kegiatan/{kegiatan}/peserta/import', [App\Http\Controllers\Act\ActivityParticipantController::class, 'importStore'])->name('kegiatan.peserta.import.store');

    Route::resource('fund-sources', App\Http\Controllers\Act\FundSourceController::class);
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
    Route::get('dictionaries/activity-names/template', [ActivityNameController::class, 'downloadTemplate'])->name('activity-names.template');
    Route::post('dictionaries/activity-names/import', [ActivityNameController::class, 'import'])->name('activity-names.import');
    Route::resource('dictionaries/activity-names', ActivityNameController::class);
    Route::resource('dictionaries/budget-categories', App\Http\Controllers\Act\BudgetCategoryController::class);
    Route::resource('employment-types', EmploymentTypeController::class);
});
