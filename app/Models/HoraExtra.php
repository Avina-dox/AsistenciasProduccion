<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $fillable = [

        'folio',

        'tipo',

        'departamento_id',

        'fecha',

        'hora_inicio',

        'hora_fin',

        'motivo',

        'estatus_hora_extra_id',

        'registrado_por',

        'autorizado_por',

        'fecha_autorizacion',

        'observaciones_supervisor',

        'observaciones_coordinacion',

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

    public function estatus()
    {
        return $this->belongsTo(
            EstatusHoraExtra::class,
            'estatus_hora_extra_id'
        );
    }

    public function supervisor()
    {
        return $this->belongsTo(
            User::class,
            'registrado_por'
        );
    }

    public function coordinador()
    {
        return $this->belongsTo(
            User::class,
            'autorizado_por'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            HoraExtraDetalle::class
        );
    }
}