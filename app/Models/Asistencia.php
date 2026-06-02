<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'empleado_id',
        'estatus_asistencia_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'minutos_retardo',
        'comentarios'
    ];

    // RELACIONES

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function estatus()
    {
        return $this->belongsTo(EstatusAsistencia::class, 'estatus_asistencia_id');
    }
}