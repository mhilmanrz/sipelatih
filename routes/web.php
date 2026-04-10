<?php

use App\Http\Controllers\Act\ActivityController;
use App\Http\Controllers\Act\ActivityFormatController;
use App\Http\Controllers\Act\ActivityMaterialController;
use App\Http\Controllers\Act\ActivityMethodController;
use App\Http\Controllers\Act\ActivityModeratorController;
use App\Http\Controllers\Act\ActivityParticipantController;
use App\Http\Controllers\Act\ActivityProfessionController;
use App\Http\Controllers\Act\ActivityReportController;
use App\Http\Controllers\Act\ActivityScopeController;
use App\Http\Controllers\Act\ActivityScoreController;
use App\Http\Controllers\Act\ActivitySpeakerController;
use App\Http\Controllers\Act\ActivityStatusController;
use App\Http\Controllers\Act\ActivityTypeController;
use App\Http\Controllers\Act\BatchController;
use App\Http\Controllers\Act\BudgetCategoryController;
use App\Http\Controllers\Act\FundSourceController;
use App\Http\Controllers\Act\MaterialTypeController;
use App\Http\Controllers\Act\TargetParticipantController; // Added ProfileController
use App\Http\Controllers\ActivityNameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringJplController;
use App\Http\Controllers\PaguController;
use App\Http\Controllers\ProfessionCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\EmploymentTypeController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\User\ProfessionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WorkUnitController;
use App\Http\Controllers\UsulanDiklatController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/usulan-diklat', [UsulanDiklatController::class, 'index'])->name('usulan-diklat');

    // Import Kegiatan Routes
    Route::get('/usulan-diklat/import-kegiatan/template', [UsulanDiklatController::class, 'downloadTemplate'])->name('kegiatan.import.template');
    Route::get('/usulan-diklat/import-kegiatan', [UsulanDiklatController::class, 'importPage'])->name('kegiatan.import.page');
    Route::post('/usulan-diklat/import-kegiatan', [UsulanDiklatController::class, 'importStore'])->name('kegiatan.import.store');

    // Import Kegiatan Per Peserta Routes
    Route::get('/usulan-diklat/import-kegiatan-per-peserta/template', [UsulanDiklatController::class, 'downloadTemplatePerPeserta'])->name('kegiatan.import-per-peserta.template');
    Route::get('/usulan-diklat/import-kegiatan-per-peserta', [UsulanDiklatController::class, 'importPerPesertaPage'])->name('kegiatan.import-per-peserta.page');
    Route::post('/usulan-diklat/import-kegiatan-per-peserta', [UsulanDiklatController::class, 'importPerPesertaStore'])->name('kegiatan.import-per-peserta.store');

    Route::get('/usulan-diklat/{id}', [UsulanDiklatController::class, 'show'])->name('usulan-diklat.show');

    Route::view('/manajemen-sasaran-profesi', 'ManajemenSasaranProfesi');
    Route::get('/monitoring-jpl', [MonitoringJplController::class, 'index'])->name('monitoring.jpl.index');
    Route::get('/pagu/import/template', [PaguController::class, 'downloadTemplate'])->name('pagu.import.template');
    Route::get('/pagu/import', [PaguController::class, 'importPage'])->name('pagu.import.page');
    Route::post('/pagu/import', [PaguController::class, 'importStore'])->name('pagu.import.store');
    Route::resource('/pagu', PaguController::class);
    Route::get('/laporan-kegiatan/template', [ActivityReportController::class, 'downloadTemplate'])->name('kegiatan.laporan.template');
    Route::get('/laporan-kegiatan', [ActivityReportController::class, 'index'])->name('kegiatan.laporan.index');
    Route::post('/laporan-kegiatan', [ActivityReportController::class, 'store'])->name('kegiatan.laporan.store');
    Route::put('/laporan-kegiatan/{id}', [ActivityReportController::class, 'update'])->name('kegiatan.laporan.update');
    Route::view('/evaluasi1', 'evaluasi1');
    Route::view('/evaluasi2', 'evaluasi2');
    Route::view('/evaluasi3', 'evaluasi3');

    Route::get('users/import', [UserController::class, 'importView'])->name('users.import.view');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('kegiatan', ActivityController::class);
    Route::post('kegiatan/{kegiatan}/sasaran-profesi', [ActivityProfessionController::class, 'store'])->name('kegiatan.sasaran-profesi.store');
    Route::delete('kegiatan/{kegiatan}/sasaran-profesi/{id}', [ActivityProfessionController::class, 'destroy'])->name('kegiatan.sasaran-profesi.destroy');
    Route::post('kegiatan/{kegiatan}/materi', [ActivityMaterialController::class, 'store'])->name('kegiatan.materi.store');
    Route::delete('kegiatan/{kegiatan}/materi/{id}', [ActivityMaterialController::class, 'destroy'])->name('kegiatan.materi.destroy');
    Route::post('kegiatan/{kegiatan}/narasumber', [ActivitySpeakerController::class, 'store'])->name('kegiatan.narasumber.store');
    Route::delete('kegiatan/{kegiatan}/narasumber/{id}', [ActivitySpeakerController::class, 'destroy'])->name('kegiatan.narasumber.destroy');
    Route::post('kegiatan/{kegiatan}/moderator', [ActivityModeratorController::class, 'store'])->name('kegiatan.moderator.store');
    Route::delete('kegiatan/{kegiatan}/moderator/{id}', [ActivityModeratorController::class, 'destroy'])->name('kegiatan.moderator.destroy');
    Route::get('kegiatan/{kegiatan}/peserta/tambah', [ActivityParticipantController::class, 'create'])->name('kegiatan.peserta.create');
    Route::post('kegiatan/{kegiatan}/peserta', [ActivityParticipantController::class, 'store'])->name('kegiatan.peserta.store');
    Route::put('kegiatan/{kegiatan}/peserta/{id}/sertifikat', [ActivityParticipantController::class, 'updateCertificate'])->name('kegiatan.peserta.update_certificate');
    Route::delete('kegiatan/{kegiatan}/peserta/{id}', [ActivityParticipantController::class, 'destroy'])->name('kegiatan.peserta.destroy');
    Route::get('kegiatan/{kegiatan}/peserta/available-users', [ActivityParticipantController::class, 'availableUsers'])->name('kegiatan.peserta.available-users');

    Route::put('kegiatan/{kegiatan}/input-nilai/{participant_id}', [ActivityScoreController::class, 'update'])->name('kegiatan.input-nilai.update');

    // Pengiriman (Status Tracker) Routes
    Route::post('kegiatan/{kegiatan}/submit', [ActivityStatusController::class, 'submit'])->name('kegiatan.submit');
    Route::post('kegiatan/{kegiatan}/cancel-submit', [ActivityStatusController::class, 'cancel'])->name('kegiatan.cancel_submit');

    // Excel Import Routes
    Route::get('kegiatan/peserta/template', [ActivityParticipantController::class, 'downloadTemplate'])->name('kegiatan.peserta.template');
    Route::get('kegiatan/{kegiatan}/peserta/import', [ActivityParticipantController::class, 'importPage'])->name('kegiatan.peserta.import.page');
    Route::post('kegiatan/{kegiatan}/peserta/import', [ActivityParticipantController::class, 'importStore'])->name('kegiatan.peserta.import.store');

    Route::resource('fund-sources', FundSourceController::class);
    Route::resource('professions', ProfessionController::class);
    Route::resource('profession-categories', ProfessionCategoryController::class);
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
    Route::resource('dictionaries/budget-categories', BudgetCategoryController::class);
    Route::resource('employment-types', EmploymentTypeController::class);
});
