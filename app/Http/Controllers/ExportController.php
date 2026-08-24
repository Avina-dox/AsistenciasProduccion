<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AsistenciasExport;
use App\Services\AsistenciaService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Services\MicrosoftGraphMailService;

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

  
  public function pdf(Request $request)
{
    $desde = $request->desde;
    $hasta = $request->hasta;
    $departamento = $request->departamento;
    $turno = $request->turno;
    $estatus = $request->estatus ?? 'ACTIVO';

    $reporte = $this->service->obtenerReporte(
        $desde,
        $hasta,
        $departamento,
        $turno,
        $estatus
    );

    $pdf = Pdf::loadView(
        'exports.asistencias-pdf',
        $reporte
    );

    $pdf->setPaper('letter', 'landscape');

    return $pdf->download(
        'Asistencias_'
        . now()->format('Ymd_His')
        . '.pdf'
    );
}
public function enviarPdf(
    Request $request,
    MicrosoftGraphMailService $mail
) {
    $desde = $request->desde;
    $hasta = $request->hasta;

    $departamento = $request->departamento;
    $turno = $request->turno;
    $estatus = $request->estatus ?? 'ACTIVO';

    // Obtener exactamente el mismo reporte que usa Excel/PDF
    $reporte = $this->service->obtenerReporte(
        $desde,
        $hasta,
        $departamento,
        $turno,
        $estatus
    );

    // Generar PDF
    $pdf = Pdf::loadView(
        'exports.asistencias-pdf',
        $reporte
    );

    $pdf->setPaper('letter', 'landscape');

    $fileName =
        'Asistencias_' .
        now()->format('Ymd_His') .
        '.pdf';

    $filePath = storage_path(
        'app/' . $fileName
    );

    file_put_contents(
        $filePath,
        $pdf->output()
    );

    // Obtener usuarios con rol RH
    $destinatarios = User::role('RH')
        ->whereNotNull('email')
        ->pluck('email')
        ->unique()
        ->values()
        ->toArray();

    if (empty($destinatarios)) {
        return back()->with(
            'error',
            'No hay usuarios con rol RH para enviar el reporte.'
        );
    }

    $remitente = auth()->user()?->email;

    $html = "
        <h2>Reporte de Asistencias</h2>

        <p>
            Se ha generado un reporte de asistencias.
        </p>

        <p>
            <strong>Desde:</strong>
            " . \Carbon\Carbon::parse($desde)->format('d/m/Y') . "
        </p>

        <p>
            <strong>Hasta:</strong>
            " . \Carbon\Carbon::parse($hasta)->format('d/m/Y') . "
        </p>

        <p>
            <strong>Generado por:</strong>
            {$remitente}
        </p>

        <p>
            El reporte se encuentra adjunto en formato PDF.
        </p>
    ";

    $mail->sendHtmlWithAttachment(
        $destinatarios,
        'Reporte de Asistencias',
        $html,
        $filePath,
        $fileName
    );

    // Eliminar archivo temporal
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    return back()->with(
        'success',
        'Reporte PDF enviado correctamente a RH.'
    );
}
}
