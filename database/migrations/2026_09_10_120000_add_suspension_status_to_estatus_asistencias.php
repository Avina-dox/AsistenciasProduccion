<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\EstatusAsistencia;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        EstatusAsistencia::updateOrCreate(
            ['codigo' => 'S'],
            [
                'nombre' => 'Suspensión',
                'color' => 'rose',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        EstatusAsistencia::where('codigo', 'S')->delete();
    }
};
