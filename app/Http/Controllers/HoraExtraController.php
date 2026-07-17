<?php

namespace App\Http\Controllers;

use App\Models\HoraExtra;
use App\Services\HoraExtraService;
use Illuminate\Http\Request;

class HoraExtraController extends Controller
{
    public function __construct(
        protected HoraExtraService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view(
            'hora-extras.index',
            [
                'solicitudes' => $this->service->listar(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'hora-extras.create',
            $this->service->datosFormulario()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $datos = $request->validate([

            'tipo' => 'required|in:AREA,EMPLEADO',

            'departamento_id' => 'required|exists:departamentos,id',

            'fecha' => 'required|date',

            'hora_inicio' => 'required',

            'hora_fin' => 'required|after:hora_inicio',

            'motivo' => 'required',

            'empleados' => 'nullable|array',

            'empleados.*' => 'exists:empleados,id',

        ]);

        $this->service->crear($datos);

        return redirect()
            ->route('hora-extras.index')
            ->with(
                'success',
                'Solicitud registrada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar
    |--------------------------------------------------------------------------
    */

    public function show(HoraExtra $horaExtra)
    {
        return view(
            'hora-extras.show',
            [
                'horaExtra' => $this->service->detalle($horaExtra),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */

    public function edit(HoraExtra $horaExtra)
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        HoraExtra $horaExtra
    ) {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | Aprobar
    |--------------------------------------------------------------------------
    */

    public function aprobar(HoraExtra $horaExtra)
    {
        $this->service->aprobar($horaExtra);

        return back()->with(
            'success',
            'Solicitud aprobada correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Rechazar
    |--------------------------------------------------------------------------
    */

    public function rechazar(
        Request $request,
        HoraExtra $horaExtra
    ) {

        $request->validate([

            'observaciones_coordinacion'
                => 'required|string',

        ]);

        $this->service->rechazar(

            $horaExtra,

            $request->observaciones_coordinacion

        );

        return back()->with(
            'success',
            'Solicitud rechazada correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function destroy(HoraExtra $horaExtra)
    {
        //
    }
}