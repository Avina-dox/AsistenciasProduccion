<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    protected $table = 'tipos_permisos';

    protected $fillable = [
        'nombre',
        'codigo',
        'goce_sueldo',
    ];

    protected $casts = [
        'goce_sueldo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}