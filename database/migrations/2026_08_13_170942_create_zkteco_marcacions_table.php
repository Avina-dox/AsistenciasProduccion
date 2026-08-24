<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zkteco_marcaciones', function (Blueprint $table) {
            $table->id();

            // ID original del evento en ZKAccess
            $table->unsignedBigInteger('log_id')->unique();

            // Identificador interno de usuario en ZKAccess
            $table->unsignedBigInteger('user_id');

            // Número de empleado
            $table->string('numero_empleado', 50)->nullable();

            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();

            // Fecha y hora de la marcación
            $table->dateTime('check_time');

            // I = Entrada, O = Salida
            $table->string('check_type', 10);

            // Método de verificación utilizado por ZKTeco
            $table->integer('verify_code')->nullable();

            // Dispositivo/punto donde se realizó la marcación
            $table->integer('sensor_id')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('numero_empleado');
            $table->index('check_time');
            $table->index('check_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zkteco_marcaciones');
    }
};