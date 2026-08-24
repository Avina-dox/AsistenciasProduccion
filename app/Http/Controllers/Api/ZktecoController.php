<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZktecoMarcacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZktecoController extends Controller
{
    public function test(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token || !hash_equals(
            (string) config('services.zkteco.token'),
            $token
        )) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Conexión ZKTeco funcionando correctamente',
            'server_time' => now()->toDateTimeString(),
        ]);
    }

    public function marcaciones(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token || !hash_equals(
            (string) config('services.zkteco.token'),
            $token
        )) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 401);
        }

        $validated = $request->validate([
            'events' => ['required', 'array', 'min:1'],

            'events.*.log_id' => ['required', 'integer'],
            'events.*.user_id' => ['required', 'integer'],

            'events.*.employee_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'events.*.name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'events.*.lastname' => [
                'nullable',
                'string',
                'max:255',
            ],

            'events.*.check_time' => [
                'required',
                'date',
            ],

            'events.*.check_type' => [
                'required',
                'in:I,O',
            ],

            'events.*.verify_code' => [
                'nullable',
                'integer',
            ],

            'events.*.sensor_id' => [
                'nullable',
                'integer',
            ],
        ]);

        $insertados = 0;
        $duplicados = 0;

        DB::transaction(function () use (
            $validated,
            &$insertados,
            &$duplicados
        ) {
            foreach ($validated['events'] as $event) {

                $existe = ZktecoMarcacion::where(
                    'log_id',
                    $event['log_id']
                )->exists();

                if ($existe) {
                    $duplicados++;
                    continue;
                }

                ZktecoMarcacion::create([
                    'log_id' => $event['log_id'],
                    'user_id' => $event['user_id'],
                    'numero_empleado' => $event['employee_number'] ?? null,
                    'nombre' => $event['name'] ?? null,
                    'apellido' => $event['lastname'] ?? null,
                    'check_time' => $event['check_time'],
                    'check_type' => $event['check_type'],
                    'verify_code' => $event['verify_code'] ?? null,
                    'sensor_id' => $event['sensor_id'] ?? null,
                ]);

                $insertados++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Marcaciones procesadas correctamente',
            'insertados' => $insertados,
            'duplicados' => $duplicados,
        ]);
    }
}