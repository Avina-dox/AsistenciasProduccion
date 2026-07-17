<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsistenciaController;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Controllers\HoraExtraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return view('welcome');
});
Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware('auth')
    ->name('dashboard');

    Route::get(
    '/asistencias/exportar',
    [ExportController::class,'asistencias']
)->name('asistencias.exportar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('asistencias', AsistenciaController::class);
});
Route::resource('empleados', EmpleadoController::class)->middleware('role:RH|Admin');


Route::resource(
    'usuarios',
    UsuarioController::class
)->middleware('role:Admin');


Route::patch(
    'empleados/{empleado}/estatus',
    [EmpleadoController::class, 'actualizarEstatus']
)->name('empleados.estatus');

Route::resource(
    'hora-extras',
    HoraExtraController::class
);
Route::middleware([
    'auth',
    'permission:ver horas extra'
])->group(function () {

    Route::resource(
        'hora-extras',
        HoraExtraController::class
    );
});
Route::patch(
    'hora-extras/{horaExtra}/aprobar',
    [HoraExtraController::class, 'aprobar']
)->name('hora-extras.aprobar');

Route::patch(
    'hora-extras/{horaExtra}/rechazar',
    [HoraExtraController::class, 'rechazar']
)->name('hora-extras.rechazar');
Route::resource('hora-extras', HoraExtraController::class);

Route::patch(
    'hora-extras/{horaExtra}/aprobar',
    [HoraExtraController::class, 'aprobar']
)->name('hora-extras.aprobar');

Route::patch(
    'hora-extras/{horaExtra}/rechazar',
    [HoraExtraController::class, 'rechazar']
)->name('hora-extras.rechazar');
require __DIR__ . '/auth.php';
