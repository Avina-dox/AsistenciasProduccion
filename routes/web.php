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
Route::get(
    '/asistencias/pdf',
    [ExportController::class, 'pdf']
)->middleware('auth')
  ->name('asistencias.pdf');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('asistencias', AsistenciaController::class);
});
Route::resource('empleados', EmpleadoController::class)
    ->except(['destroy'])
    ->middleware('role:RH|Admin|Supervisor|Coordinacion');

Route::delete(
    'empleados/{empleado}',
    [EmpleadoController::class, 'destroy']
)->name('empleados.destroy')
    ->middleware('role:RH|Admin|Coordinacion');


Route::resource(
    'usuarios',
    UsuarioController::class
)->middleware('role:Admin');


Route::patch(
    'empleados/{empleado}/estatus',
    [EmpleadoController::class, 'actualizarEstatus']
)->name('empleados.estatus')
    ->middleware('role:RH|Admin|Supervisor|Coordinacion');

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

Route::post(
    '/asistencias/enviar-pdf',
    [ExportController::class, 'enviarPdf']
)->middleware('auth')
  ->name('asistencias.enviarPdf');


