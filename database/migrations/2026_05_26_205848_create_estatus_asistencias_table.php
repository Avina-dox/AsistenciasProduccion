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
        Schema::create('estatus_asistencias', function (Blueprint $table) {

    $table->id();

    $table->string('codigo')->unique();

    $table->string('nombre');

    $table->string('color')->nullable();

    $table->boolean('descuenta_salario')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus_asistencias');
    }
};
