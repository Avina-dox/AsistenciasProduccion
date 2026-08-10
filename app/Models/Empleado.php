<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HoraExtraDetalle;
use App\Models\User;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'departamento_id',
        'codigo_empleado',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'puesto',
        'fecha_ingreso',
        'hora_entrada',
        'hora_salida',
        'salario_diario',
        'estatus',
        'turno_id',
        'onomastico',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function departamento()
    {
        return $this->belongsTo(
            Departamento::class
        );
    }

    public function turno()
    {
        return $this->belongsTo(
            Turno::class
        );
    }

    public function asistencias()
    {
        return $this->hasMany(
            Asistencia::class
        );
    }

    public function permisos()
    {
        return $this->hasMany(
            Permiso::class
        );
    }

    public function horasExtras()
    {
        return $this->hasMany(
            HoraExtraDetalle::class,
            'empleado_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}