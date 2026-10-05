<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plantilla autorizada (personal objetivo) por turno y departamento.
     * Alimenta "Cobertura por Área" del dashboard; antes estaba fija en
     * DashboardService, aquí se migran esos mismos valores.
     */
    public function up(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->unsignedInteger('plantilla_autorizada')
                ->nullable()
                ->after('hora_salida');
        });

        $objetivos = [
            'MATUTINO' => [
                'Mezclado' => 14,
                'Formado' => 10,
                'Horneado' => 26,
                'Empaque Granola' => 22,
                'Empaque Barras' => 17,
            ],
            'NOCTURNO' => [
                'Mezclado' => 10,
                'Formado' => 8,
                'Horneado' => 22,
                'Empaque Granola' => 18,
                'Empaque Barras' => 14,
            ],
        ];

        foreach ($objetivos as $turno => $areas) {
            foreach ($areas as $departamento => $objetivo) {
                DB::table('horarios')
                    ->join('turnos', 'horarios.turno_id', '=', 'turnos.id')
                    ->join('departamentos', 'horarios.departamento_id', '=', 'departamentos.id')
                    ->where('turnos.nombre', $turno)
                    ->where('departamentos.nombre', $departamento)
                    ->update(['horarios.plantilla_autorizada' => $objetivo]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropColumn('plantilla_autorizada');
        });
    }
};
