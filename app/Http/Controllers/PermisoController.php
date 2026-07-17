<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [

        'folio',

        'empleado_id',

        'tipo_permiso_id',

        'fecha',

        'hora_salida',

        'hora_regreso',

        'motivo',

        'goce_sueldo',

        'estatus_permiso_id',

        'registrado_por',

        'autorizado_por',

        'fecha_autorizacion',

        'observaciones_supervisor',

        'observaciones_rh',

    ];

    protected $casts = [

        'fecha' => 'date',

        'fecha_autorizacion' => 'datetime',

        'goce_sueldo' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function empleado()
    {
        return $this->belongsTo(
            Empleado::class
        );
    }

    public function tipo()
    {
        return $this->belongsTo(
            TipoPermiso::class,
            'tipo_permiso_id'
        );
    }

    public function estatus()
    {
        return $this->belongsTo(
            EstatusPermiso::class,
            'estatus_permiso_id'
        );
    }

    public function supervisor()
    {
        return $this->belongsTo(
            User::class,
            'registrado_por'
        );
    }

    public function autorizador()
    {
        return $this->belongsTo(
            User::class,
            'autorizado_por'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendientes($query)
    {
        return $query->whereHas(
            'estatus',
            fn($q) => $q->where('nombre', 'PENDIENTE')
        );
    }

    public function scopeAprobados($query)
    {
        return $query->whereHas(
            'estatus',
            fn($q) => $q->where('nombre', 'APROBADO')
        );
    }

    public function scopeRechazados($query)
    {
        return $query->whereHas(
            'estatus',
            fn($q) => $q->where('nombre', 'RECHAZADO')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de negocio
    |--------------------------------------------------------------------------
    */

    public function aprobar($usuarioId)
    {
        $estatus = EstatusPermiso::where(
            'nombre',
            'APROBADO'
        )->first();

        $this->update([

            'estatus_permiso_id' => $estatus->id,

            'autorizado_por' => $usuarioId,

            'fecha_autorizacion' => now(),

        ]);
    }

    public function rechazar($usuarioId)
    {
        $estatus = EstatusPermiso::where(
            'nombre',
            'RECHAZADO'
        )->first();

        $this->update([

            'estatus_permiso_id' => $estatus->id,

            'autorizado_por' => $usuarioId,

            'fecha_autorizacion' => now(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFechaFormateadaAttribute()
    {
        return Carbon::parse(
            $this->fecha
        )->format('d/m/Y');
    }
}