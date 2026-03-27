<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfessionController;
use App\Http\Controllers\User\WorkUnitController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\Act\ActivityController;
use App\Http\Controllers\Act\ActivityTypeController;
use App\Http\Controllers\Act\ActivityScopeController;
use App\Http\Controllers\Act\ActivityMethodController;
use App\Http\Controllers\Act\MaterialTypeController;
use App\Http\Controllers\Act\BatchController;
use App\Http\Controllers\Act\ActivityFormatController;
use App\Http\Controllers\Act\TargetParticipantController;
use App\Http\Controllers\User\EmploymentTypeController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\Act\ActivityProfessionController;
use App\Http\Controllers\Act\ActivityKakFileController;
use App\Http\Controllers\Act\ActivityMaterialController;
use App\Http\Controllers\Act\ActivitySpeakerController;
use App\Http\Controllers\Act\ActivityModeratorController;
use App\Http\Controllers\Act\ActivityParticipantController;
use App\Http\Controllers\Act\ActivityStatusController;
use App\Http\Controllers\Act\ActivityScoreController;
use App\Http\Controllers\MonitoringJplController;

// --- Custom / Nested Routes ---
Route::prefix('activities/{activityId}')->group(function () {
    Route::get('professions', [ActivityProfessionController::class, 'getByActivity']);
    Route::get('materials', [ActivityMaterialController::class, 'getByActivity']);
    Route::get('speakers', [ActivitySpeakerController::class, 'getByActivity']);
    Route::get('moderators', [ActivityModeratorController::class, 'getByActivity']);
    Route::get('participants', [ActivityParticipantController::class, 'getByActivity']);
    Route::post('participants/import', [ActivityParticipantController::class, 'import']);
    Route::get('statuses', [ActivityStatusController::class, 'getByActivity']);
});

// Single actions
Route::get('monitoring-jpl', [MonitoringJplController::class, 'index']);
Route::get('activity-participants/template', [ActivityParticipantController::class, 'downloadTemplate']);
Route::get('activity-kak-files/download-template', [ActivityKakFileController::class, 'downloadTemplate']);
Route::get('activity-kak-files/{id}/download', [ActivityKakFileController::class, 'download']);

// Activity Kak Files explicitly uses POST for updates (due to multipart forms).
Route::post('activity-kak-files/{id}', [ActivityKakFileController::class, 'update']);
Route::apiResource('activity-kak-files', ActivityKakFileController::class)->except(['update']);

// --- Standard API Resources ---
Route::apiResources([
    'users'                 => UserController::class,
    'professions'           => ProfessionController::class,
    'workunits'             => WorkUnitController::class,
    'positions'             => PositionController::class,
    'activities'            => ActivityController::class,
    'activity-types'        => ActivityTypeController::class,
    'activity-scopes'       => ActivityScopeController::class,
    'activity-methods'      => ActivityMethodController::class,
    'material-types'        => MaterialTypeController::class,
    'batches'               => BatchController::class,
    'activity-formats'      => ActivityFormatController::class,
    'target-participants'   => TargetParticipantController::class,
    'employment-types'      => EmploymentTypeController::class,
    'budget-categories'     => BudgetCategoryController::class,
    'budgets'               => BudgetController::class,
    'activity-professions'  => ActivityProfessionController::class,
    'activity-materials'    => ActivityMaterialController::class,
    'activity-speakers'     => ActivitySpeakerController::class,
    'activity-moderators'   => ActivityModeratorController::class,
    'activity-participants' => ActivityParticipantController::class,
    'activity-statuses'     => ActivityStatusController::class,
    'activity-scores'       => ActivityScoreController::class,
]);
