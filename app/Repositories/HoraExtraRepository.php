<?php

namespace App\Repositories;

use App\Models\Empleado;
use App\Models\HoraExtra;
use App\Models\HoraExtraDetalle;

class HoraExtraRepository
{
    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function listar()
    {
        return HoraExtra::with([
            'departamento',
            'estatus',
            'supervisor',
            'coordinador'
        ])
        ->latest()
        ->paginate(15);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener
    |--------------------------------------------------------------------------
    */

    public function obtener(HoraExtra $horaExtra): HoraExtra
    {
        return $horaExtra->load([
            'departamento',
            'estatus',
            'supervisor',
            'coordinador',
            'detalles.empleado'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Crear solicitud
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): HoraExtra
    {
        return HoraExtra::create($datos);
    }

    /*
    |--------------------------------------------------------------------------
    | Crear detalle
    |--------------------------------------------------------------------------
    */

    public function crearDetalle(array $datos): HoraExtraDetalle
    {
        return HoraExtraDetalle::create($datos);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        HoraExtra $horaExtra,
        array $datos
    ): bool {

        return $horaExtra->update($datos);

    }

    /*
    |--------------------------------------------------------------------------
    | Empleados por área
    |--------------------------------------------------------------------------
    */

    public function empleadosPorArea(
        int $departamentoId
    )
    {
        return Empleado::where(
            'departamento_id',
            $departamentoId
        )
        ->where(
            'estatus',
            'ACTIVO'
        )
        ->orderBy('nombre')
        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Empleados seleccionados
    |--------------------------------------------------------------------------
    */

    public function empleadosSeleccionados(
        array $empleados
    )
    {
        return Empleado::whereIn(
            'id',
            $empleados
        )->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Catálogo empleados activos
    |--------------------------------------------------------------------------
    */

    public function empleadosActivos()
    {
        return Empleado::where(
            'estatus',
            'ACTIVO'
        )
        ->orderBy('nombre')
        ->get();
    }
}