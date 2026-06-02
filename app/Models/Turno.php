<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = [
        'nombre',
        'hora_entrada',
        'hora_salida'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
    public function turno()
{
    return $this->belongsTo(Turno::class);
}
}