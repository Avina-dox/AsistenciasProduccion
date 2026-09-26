<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZktecoController;
use App\Http\Controllers\Api\AsistenciaApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/zkteco/test', [ZktecoController::class, 'test']);

Route::post('/zkteco/marcaciones', [ZktecoController::class, 'marcaciones']);



Route::middleware('auth:sanctum')->get('/asistencias/turno', [AsistenciaApiController::class, 'porTurno']);
Route::middleware('auth:sanctum')->get('/plantilla', [AsistenciaApiController::class, 'plantilla']);
Route::middleware('auth:sanctum')->get('/plantilla-detalle', [AsistenciaApiController::class, 'plantillaDetalle']);