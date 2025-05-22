<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\herramientasController;
use App\Http\Controllers\Api\manualController;
use App\Http\Controllers\Api\materialController;
use App\Http\Controllers\Api\seguridadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpleadoAuthController;



Route::resource('herramientas', herramientasController::class)->except(['create', 'edit']);
Route::resource('manuales', manualController::class)->except(['create', 'edit']);
Route::resource('materiales', materialController::class)->except(['create','edit']);
Route::resource('seguridades',seguridadController::class)->except(['create','edit']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::prefix('empleado')->group(function () {
    Route::post('/register', [EmpleadoAuthController::class, 'register']);
    Route::post('/login', [EmpleadoAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [EmpleadoAuthController::class, 'logout']);
    });
});
