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
       Schema::create('empleados', function (Blueprint $table) {

    $table->id();

    $table->foreignId('departamento_id')
        ->nullable()
        ->constrained('departamentos')
        ->nullOnDelete();

    $table->string('codigo_empleado')->unique();

    $table->string('nombre');

    $table->string('apellido_paterno');

    $table->string('apellido_materno')->nullable();

    $table->string('correo')->nullable();

    $table->string('telefono')->nullable();

    $table->string('puesto')->nullable();

    $table->date('fecha_ingreso')->nullable();

    $table->time('hora_entrada')->default('09:30:00');

    $table->time('hora_salida')->default('17:30:00');

    $table->decimal('salario_diario', 10, 2)->nullable();

    $table->enum('estatus', [
        'ACTIVO',
        'INACTIVO',
        'VACACIONES',
        'BAJA'
    ])->default('ACTIVO');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
