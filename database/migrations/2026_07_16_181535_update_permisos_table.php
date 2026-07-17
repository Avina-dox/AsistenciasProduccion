<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permisos', function (Blueprint $table) {

            $table->string('folio')
                ->unique()
                ->after('id');

            $table->foreignId('tipo_permiso_id')
                ->after('empleado_id')
                ->constrained('tipos_permisos');

            $table->boolean('goce_sueldo')
                ->default(false)
                ->after('motivo');

            $table->foreignId('estatus_permiso_id')
                ->after('goce_sueldo')
                ->constrained('estatus_permisos');

            $table->foreignId('registrado_por')
                ->after('estatus_permiso_id')
                ->constrained('users');

            $table->foreignId('autorizado_por')
                ->nullable()
                ->after('registrado_por')
                ->constrained('users');

            $table->timestamp('fecha_autorizacion')
                ->nullable();

            $table->text('observaciones_supervisor')
                ->nullable();

            $table->text('observaciones_rh')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {

            $table->dropForeign(['tipo_permiso_id']);
            $table->dropForeign(['estatus_permiso_id']);
            $table->dropForeign(['registrado_por']);
            $table->dropForeign(['autorizado_por']);

            $table->dropColumn([
                'folio',
                'tipo_permiso_id',
                'goce_sueldo',
                'estatus_permiso_id',
                'registrado_por',
                'autorizado_por',
                'fecha_autorizacion',
                'observaciones_supervisor',
                'observaciones_rh',
            ]);

        });
    }
};