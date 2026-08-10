<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'turno_id',
        'departamento_id',
        'hora_entrada',
        'hora_salida',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}