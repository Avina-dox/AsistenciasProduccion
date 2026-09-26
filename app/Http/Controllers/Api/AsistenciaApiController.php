<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaApiController extends Controller
{
    // GET /api/asistencias/turno?fecha=2026-09-14&turno=dia|noche
    //
    // Regresa el personal que asistió (estatus "Asistencia" o "Retardo")
    // ese día en ese turno. "dia" incluye tanto "Diurno" (nombre viejo)
    // como "MATUTINO" (nombre nuevo, lo reemplazó) — ambos son el mismo
    // turno, solo cambió el nombre en el catálogo.
    public function porTurno(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'turno' => 'required|in:dia,noche',
        ]);

        $nombresTurno = $request->turno === 'dia'
            ? ['Diurno', 'MATUTINO']
            : ['Nocturno'];

        $filas = DB::table('asistencias')
            ->join('empleados', 'empleados.id', '=', 'asistencias.empleado_id')
            ->join('turnos', 'turnos.id', '=', 'empleados.turno_id')
            ->join('departamentos', 'departamentos.id', '=', 'empleados.departamento_id')
            ->join('estatus_asistencias', 'estatus_asistencias.id', '=', 'asistencias.estatus_asistencia_id')
            ->whereIn('turnos.nombre', $nombresTurno)
            ->whereIn('estatus_asistencias.codigo', ['A', 'R'])
            ->where('asistencias.fecha', $request->fecha)
            ->where('empleados.estatus', 'ACTIVO')
            ->select(
                'empleados.id as empleado_id',
                DB::raw("CONCAT(empleados.nombre, ' ', empleados.apellido_paterno, ' ', empleados.apellido_materno) as nombre_completo"),
                'departamentos.nombre as area',
                'turnos.nombre as turno',
                'estatus_asistencias.nombre as estatus_asistencia'
            )
            ->orderBy('nombre_completo')
            ->get();

        return response()->json(['data' => $filas]);
    }
    
    public function plantilla(Request $request)
    {
        $request->validate(['turno' => 'required|in:dia,noche']);

        $nombresTurno = $request->turno === 'dia' ? ['Diurno', 'MATUTINO'] : ['Nocturno'];

        $personal = DB::table('empleados')
            ->join('turnos', 'turnos.id', '=', 'empleados.turno_id')
            ->whereIn('turnos.nombre', $nombresTurno)
            ->where('empleados.estatus', 'ACTIVO')
            ->count();

        $objetivo = DB::table('plantilla_autorizada')->where('turno', $request->turno)->value('objetivo');

        $porcentaje = $objetivo > 0 ? round(($personal / $objetivo) * 100, 1) : null;

        return response()->json(['data' => ['turno' => $request->turno, 'personal' => $personal, 'objetivo' => $objetivo, 'porcentaje' => $porcentaje]]);
    }

    public function plantillaDetalle(Request $request)
    {
        $request->validate(['turno' => 'required|in:dia,noche']);

        $nombresTurno = $request->turno === 'dia' ? ['Diurno', 'MATUTINO'] : ['Nocturno'];

        $porArea = DB::table('empleados')
            ->join('turnos', 'turnos.id', '=', 'empleados.turno_id')
            ->join('departamentos', 'departamentos.id', '=', 'empleados.departamento_id')
            ->whereIn('turnos.nombre', $nombresTurno)
            ->where('empleados.estatus', 'ACTIVO')
            ->select('departamentos.nombre as area', DB::raw('COUNT(*) as personal'))
            ->groupBy('departamentos.nombre')
            ->orderByDesc('personal')
            ->get();

        return response()->json(['data' => $porArea]);
    }
}