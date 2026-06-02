<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstatusAsistencia extends Model
{
    protected $table = 'estatus_asistencias';

    protected $fillable = [
        'codigo',
        'nombre',
        'color',
        'descuenta_salario'
    ];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}