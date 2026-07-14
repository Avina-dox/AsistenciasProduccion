<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraExtraDetalle extends Model
{
    protected $fillable = [

        'hora_extra_id',

        'empleado_id',

        'horas',

        'observaciones',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function horaExtra()
    {
        return $this->belongsTo(HoraExtra::class);
    }
}
