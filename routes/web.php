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

// Authentication Routes
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    // Menu Layout Placeholder Routes
    Route::view('/dashboard', 'dashboard');
    Route::view('/usulan-diklat', 'placeholder');
    Route::view('/manajemen-sasaran-profesi', 'ManajemenSasaranProfesi');
    Route::view('/monitoring-jpl', 'monitoringJpl');
    Route::view('/pagu', 'placeholder');
    Route::view('/input-nilai', 'placeholder');
    Route::view('/laporan-kegiatan', 'placeholder');
    Route::view('/evaluasi1', 'evaluasi1');
    Route::view('/evaluasi2', 'evaluasi2');
    Route::view('/evaluasi3', 'evaluasi3');
    Route::view('/bank-data', 'placeholder');

    Route::resource('users', UserController::class);
    Route::resource('kegiatan', ActivityController::class);
    Route::resource('professions', ProfessionController::class);
    Route::resource('work-units', WorkUnitController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('activity-types', ActivityTypeController::class);
    Route::resource('activity-scopes', ActivityScopeController::class);
    Route::resource('activity-methods', ActivityMethodController::class);
    Route::resource('material-types', MaterialTypeController::class);
    Route::resource('batches', BatchController::class);
    Route::resource('activity-formats', ActivityFormatController::class);
    Route::resource('target-participants', TargetParticipantController::class);
    Route::resource('employment-types', EmploymentTypeController::class);
});
