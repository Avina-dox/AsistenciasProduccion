<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZktecoMarcacion extends Model
{
    protected $table = 'zkteco_marcaciones';

    protected $fillable = [
        'log_id',
        'user_id',
        'numero_empleado',
        'nombre',
        'apellido',
        'check_time',
        'check_type',
        'verify_code',
        'sensor_id',
    ];

    protected $casts = [
        'check_time' => 'datetime',
    ];
}