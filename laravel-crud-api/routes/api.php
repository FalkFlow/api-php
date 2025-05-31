<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\herramientasController;
use App\Http\Controllers\Api\manualController;
use App\Http\Controllers\Api\materialController;
use App\Http\Controllers\Api\seguridadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpleadoAuthController;
use App\Http\Controllers\Api\TransbankController;
use App\Http\Controllers\Api\SucursalController;

# Transbank Routes
Route::post('/transbank/create', [TransbankController::class, 'createTransaction']);
Route::post('/transbank/callback', [TransbankController::class, 'callback'])->name('transbank.callback');

# Sucursal Routes
Route::post('/stock/descontar', [SucursalController::class, 'descontarStock']);
Route::get('/sucursales/stock/{nombre}', [SucursalController::class, 'verStockSucursal']);

# Resource Routes
Route::resource('herramientas', herramientasController::class);
Route::resource('manuales', manualController::class);
Route::resource('materiales', materialController::class);
Route::resource('seguridades',seguridadController::class);

# Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

# Empleado Authentication Routes
Route::prefix('empleado')->group(function () {
    Route::post('/register', [EmpleadoAuthController::class, 'register']);
    Route::post('/login', [EmpleadoAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [EmpleadoAuthController::class, 'logout']);
    });
});






