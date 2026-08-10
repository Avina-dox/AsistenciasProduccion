<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }
}