<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\herramientasController;
use App\Http\Controllers\Api\manualController;
use App\Http\Controllers\Api\materialController;
use App\Http\Controllers\Api\seguridadController;



Route::resource('herramientas', herramientasController::class)->except(['create', 'edit']);
Route::resource('manuales', manualController::class)->except(['create', 'edit']);
Route::resource('materiales', materialController::class)->except(['create','edit']);
Route::resource('seguridades',seguridadController::class)->except(['create','edit']);