<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}