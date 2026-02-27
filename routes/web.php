<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\ProfessionController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/users', [UserController::class, 'index']);
// Route::get('/users/{id}', [UserController::class, 'show']);
// Route::post('/users', [UserController::class, 'store']);

Route::get('/professions', [ProfessionController::class, 'index']);
