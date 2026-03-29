<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfessionController;
use App\Http\Controllers\User\WorkUnitController;
use App\Http\Controllers\User\PositionsController;
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

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/professions', [ProfessionController::class, 'index']);
Route::post('/professions', [ProfessionController::class, 'store']);
Route::get('/professions/{id}', [ProfessionController::class, 'show']);
Route::put('/professions/{id}', [ProfessionController::class, 'update']);
Route::delete('/professions/{id}', [ProfessionController::class, 'destroy']);

Route::get('/workunits', [WorkUnitController::class, 'index']);
Route::post('/workunits', [WorkUnitController::class, 'store']);
Route::get('/workunits/{id}', [WorkUnitController::class, 'show']);
Route::put('/workunits/{id}', [WorkUnitController::class, 'update']);
Route::delete('/workunits/{id}', [WorkUnitController::class, 'destroy']);

Route::get('/positions', [PositionsController::class, 'index']);
Route::post('/positions', [PositionsController::class, 'store']);
Route::get('/positions/{id}', [PositionsController::class, 'show']);
Route::put('/positions/{id}', [PositionsController::class, 'update']);
Route::delete('/positions/{id}', [PositionsController::class, 'destroy']);

Route::get('/activities', [ActivityController::class, 'index']);
Route::post('/activities', [ActivityController::class, 'store']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::put('/activities/{id}', [ActivityController::class, 'update']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

Route::get('/activity-types', [ActivityTypeController::class, 'index']);
Route::post('/activity-types', [ActivityTypeController::class, 'store']);
Route::get('/activity-types/{id}', [ActivityTypeController::class, 'show']);
Route::put('/activity-types/{id}', [ActivityTypeController::class, 'update']);
Route::delete('/activity-types/{id}', [ActivityTypeController::class, 'destroy']);

Route::get('/activity-scopes', [ActivityScopeController::class, 'index']);
Route::post('/activity-scopes', [ActivityScopeController::class, 'store']);
Route::get('/activity-scopes/{id}', [ActivityScopeController::class, 'show']);
Route::put('/activity-scopes/{id}', [ActivityScopeController::class, 'update']);
Route::delete('/activity-scopes/{id}', [ActivityScopeController::class, 'destroy']);

Route::get('/activity-methods', [ActivityMethodController::class, 'index']);
Route::post('/activity-methods', [ActivityMethodController::class, 'store']);
Route::get('/activity-methods/{id}', [ActivityMethodController::class, 'show']);
Route::put('/activity-methods/{id}', [ActivityMethodController::class, 'update']);
Route::delete('/activity-methods/{id}', [ActivityMethodController::class, 'destroy']);

Route::get('/material-types', [MaterialTypeController::class, 'index']);
Route::post('/material-types', [MaterialTypeController::class, 'store']);
Route::get('/material-types/{id}', [MaterialTypeController::class, 'show']);
Route::put('/material-types/{id}', [MaterialTypeController::class, 'update']);
Route::delete('/material-types/{id}', [MaterialTypeController::class, 'destroy']);

Route::get('/batches', [BatchController::class, 'index']);
Route::post('/batches', [BatchController::class, 'store']);
Route::get('/batches/{id}', [BatchController::class, 'show']);
Route::put('/batches/{id}', [BatchController::class, 'update']);
Route::delete('/batches/{id}', [BatchController::class, 'destroy']);

Route::get('/activity-formats', [ActivityFormatController::class, 'index']);
Route::post('/activity-formats', [ActivityFormatController::class, 'store']);
Route::get('/activity-formats/{id}', [ActivityFormatController::class, 'show']);
Route::put('/activity-formats/{id}', [ActivityFormatController::class, 'update']);
Route::delete('/activity-formats/{id}', [ActivityFormatController::class, 'destroy']);

Route::get('/target-participants', [TargetParticipantController::class, 'index']);
Route::post('/target-participants', [TargetParticipantController::class, 'store']);
Route::get('/target-participants/{id}', [TargetParticipantController::class, 'show']);
Route::put('/target-participants/{id}', [TargetParticipantController::class, 'update']);
Route::delete('/target-participants/{id}', [TargetParticipantController::class, 'destroy']);

Route::get('/employment-types', [EmploymentTypeController::class, 'index']);
Route::post('/employment-types', [EmploymentTypeController::class, 'store']);
Route::get('/employment-types/{id}', [EmploymentTypeController::class, 'show']);
Route::put('/employment-types/{id}', [EmploymentTypeController::class, 'update']);
Route::delete('/employment-types/{id}', [EmploymentTypeController::class, 'destroy']);

Route::get('/budget-categories', [BudgetCategoryController::class, 'index']);
Route::post('/budget-categories', [BudgetCategoryController::class, 'store']);
Route::get('/budget-categories/{id}', [BudgetCategoryController::class, 'show']);
Route::put('/budget-categories/{id}', [BudgetCategoryController::class, 'update']);
Route::delete('/budget-categories/{id}', [BudgetCategoryController::class, 'destroy']);

Route::get('/budgets', [BudgetController::class, 'index']);
Route::post('/budgets', [BudgetController::class, 'store']);
Route::get('/budgets/{id}', [BudgetController::class, 'show']);
Route::put('/budgets/{id}', [BudgetController::class, 'update']);
Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);

Route::get('/activity-professions', [ActivityProfessionController::class, 'index']);
Route::post('/activity-professions', [ActivityProfessionController::class, 'store']);
Route::get('/activity-professions/{id}', [ActivityProfessionController::class, 'show']);
Route::put('/activity-professions/{id}', [ActivityProfessionController::class, 'update']);
Route::delete('/activity-professions/{id}', [ActivityProfessionController::class, 'destroy']);

Route::get('/activity-kak-files', [ActivityKakFileController::class, 'index']);
Route::post('/activity-kak-files', [ActivityKakFileController::class, 'store']);
Route::get('/activity-kak-files/{id}', [ActivityKakFileController::class, 'show']);
Route::put('/activity-kak-files/{id}', [ActivityKakFileController::class, 'update']);
Route::delete('/activity-kak-files/{id}', [ActivityKakFileController::class, 'destroy']);

Route::get('/activity-materials', [ActivityMaterialController::class, 'index']);
Route::post('/activity-materials', [ActivityMaterialController::class, 'store']);
Route::get('/activity-materials/{id}', [ActivityMaterialController::class, 'show']);
Route::put('/activity-materials/{id}', [ActivityMaterialController::class, 'update']);
Route::delete('/activity-materials/{id}', [ActivityMaterialController::class, 'destroy']);

Route::get('/activity-speakers', [ActivitySpeakerController::class, 'index']);
Route::post('/activity-speakers', [ActivitySpeakerController::class, 'store']);
Route::get('/activity-speakers/{id}', [ActivitySpeakerController::class, 'show']);
Route::put('/activity-speakers/{id}', [ActivitySpeakerController::class, 'update']);
Route::delete('/activity-speakers/{id}', [ActivitySpeakerController::class, 'destroy']);

Route::get('/activity-moderators', [ActivityModeratorController::class, 'index']);
Route::post('/activity-moderators', [ActivityModeratorController::class, 'store']);
Route::get('/activity-moderators/{id}', [ActivityModeratorController::class, 'show']);
Route::put('/activity-moderators/{id}', [ActivityModeratorController::class, 'update']);
Route::delete('/activity-moderators/{id}', [ActivityModeratorController::class, 'destroy']);

Route::get('/activity-participants', [ActivityParticipantController::class, 'index']);
Route::post('/activity-participants', [ActivityParticipantController::class, 'store']);
Route::get('/activity-participants/{id}', [ActivityParticipantController::class, 'show']);
Route::put('/activity-participants/{id}', [ActivityParticipantController::class, 'update']);
Route::delete('/activity-participants/{id}', [ActivityParticipantController::class, 'destroy']);

Route::get('/activity-statuses', [ActivityStatusController::class, 'index']);
Route::post('/activity-statuses', [ActivityStatusController::class, 'store']);
Route::get('/activity-statuses/{id}', [ActivityStatusController::class, 'show']);
Route::put('/activity-statuses/{id}', [ActivityStatusController::class, 'update']);
Route::delete('/activity-statuses/{id}', [ActivityStatusController::class, 'destroy']);

Route::get('/activity-scores', [ActivityScoreController::class, 'index']);
Route::post('/activity-scores', [ActivityScoreController::class, 'store']);
Route::get('/activity-scores/{id}', [ActivityScoreController::class, 'show']);
Route::put('/activity-scores/{id}', [ActivityScoreController::class, 'update']);
Route::delete('/activity-scores/{id}', [ActivityScoreController::class, 'destroy']);
