<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZktecoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/zkteco/test', [ZktecoController::class, 'test']);

Route::post('/zkteco/marcaciones', [ZktecoController::class, 'marcaciones']);