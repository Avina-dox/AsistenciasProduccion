<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MicrosoftGraphMailService;

class SoporteController extends Controller
{
    public function reportar(
        Request $request,
        MicrosoftGraphMailService $mail
    ) {
        $request->validate([
            'mensaje' => 'required|string|max:2000',
        ]);

        $usuario = $request->user();

        $html = view('emails.soporte.reporte', [
            'mensaje' => $request->mensaje,
            'usuario' => $usuario,
            'pagina' => $request->input('pagina'),
        ])->render();

        $mail->sendHtml(
            'aux.sistemas@dasavena.com',
            'Reporte de problema (IT) - ' . $usuario->name,
            $html
        );

        return response()->json([
            'success' => true,
            'message' => 'Tu reporte fue enviado a IT correctamente.',
        ]);
    }
}
