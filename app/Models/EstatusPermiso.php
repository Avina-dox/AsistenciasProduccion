<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstatusPermiso extends Model
{
    protected $table = 'estatus_permisos';

    protected $fillable = [
        'nombre',
        'color',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function permisos()
    {
        return $this->hasMany(
            Permiso::class,
            'estatus_permiso_id'
        );
    }
}