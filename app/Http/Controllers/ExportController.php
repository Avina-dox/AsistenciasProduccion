<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AsistenciasExport;
use App\Services\AsistenciaService;

class ExportController extends Controller
{
    protected AsistenciaService $service;

    public function __construct(
        AsistenciaService $service
    ) {
        $this->service = $service;
    }

    public function asistencias(Request $request)
    {
        $desde = $request->desde;

        $hasta = $request->hasta;

        $departamento = $request->departamento;

        $turno = $request->turno;

        $estatus = $request->estatus ?? 'ACTIVO';

        return Excel::download(

            new AsistenciasExport(

                $this->service,

                $desde,

                $hasta,

                $departamento,

                $turno,

                $estatus

            ),

            'Asistencias_'
            . now()->format('Ymd_His')
            . '.xlsx'

        );
    }
}