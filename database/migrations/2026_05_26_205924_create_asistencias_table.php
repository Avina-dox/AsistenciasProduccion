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
       Schema::create('asistencias', function (Blueprint $table) {

    $table->id();

    $table->foreignId('empleado_id')
        ->constrained('empleados')
        ->cascadeOnDelete();

    $table->foreignId('estatus_asistencia_id')
        ->constrained('estatus_asistencias');

    $table->date('fecha');

    $table->time('hora_entrada')->nullable();

    $table->time('hora_salida')->nullable();

    $table->integer('minutos_retardo')->default(0);

    $table->text('comentarios')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
