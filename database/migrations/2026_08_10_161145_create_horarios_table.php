<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {

            $table->id();

            $table->foreignId('turno_id')
                ->constrained('turnos')
                ->cascadeOnDelete();

            $table->foreignId('departamento_id')
                ->constrained('departamentos')
                ->cascadeOnDelete();

            $table->time('hora_entrada');

            $table->time('hora_salida');

            $table->timestamps();

            $table->unique([
                'turno_id',
                'departamento_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};