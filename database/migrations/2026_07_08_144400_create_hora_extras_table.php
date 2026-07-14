<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hora_extras', function (Blueprint $table) {

            $table->id();

            // Folio consecutivo
            $table->string('folio')->unique();

            // AREA o EMPLEADO
            $table->enum('tipo', [
                'AREA',
                'EMPLEADO',
            ]);

            // Departamento que solicita
            $table->foreignId('departamento_id')
                ->constrained('departamentos')
                ->cascadeOnUpdate();

            // Fecha de las horas extra
            $table->date('fecha');

            // Horario
            $table->time('hora_inicio');

            $table->time('hora_fin');

            // Motivo
            $table->text('motivo');

            // Estado de la solicitud
            $table->foreignId('estatus_hora_extra_id')
                ->constrained('estatus_hora_extras')
                ->cascadeOnUpdate();

            // Usuario que registra (Supervisor o Coordinación)
            $table->foreignId('registrado_por')
                ->constrained('users')
                ->cascadeOnUpdate();

            // Usuario que autoriza (Coordinación)
            $table->foreignId('autorizado_por')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate();

            // Fecha en que se autorizó
            $table->timestamp('fecha_autorizacion')
                ->nullable();

            // Comentarios del supervisor
            $table->text('observaciones_supervisor')
                ->nullable();

            // Comentarios de coordinación
            $table->text('observaciones_coordinacion')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hora_extras');
    }
};