<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstatusHoraExtra extends Model
{
    protected $fillable = [

        'nombre',

        'color',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function solicitudes()
    {
        return $this->hasMany(
            HoraExtra::class,
            'estatus_hora_extra_id'
        );
    }
}