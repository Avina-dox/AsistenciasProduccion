<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantilla_autorizada', function (Blueprint $table) {
            $table->id();
            $table->string('turno', 10)->unique(); // 'dia' | 'noche'
            $table->unsignedInteger('objetivo');
            $table->timestamps();
        });

        DB::table('plantilla_autorizada')->insert([
            ['turno' => 'dia', 'objetivo' => 91, 'created_at' => now(), 'updated_at' => now()],
            ['turno' => 'noche', 'objetivo' => 72, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_autorizada');
    }
};