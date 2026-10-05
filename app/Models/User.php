<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'dashboard_widgets'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Widgets del dashboard que se muestran por defecto si el usuario
     * no ha guardado ninguna preferencia todavía.
     */
    public const WIDGETS_DASHBOARD_DEFAULT = [
        'kpis_principales',
        'indicadores_operativos',
        'cobertura_area',
        'distribucion_asistencia',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dashboard_widgets' => 'array',
        ];
    }

    /**
     * Widgets del dashboard que este usuario eligió mostrar.
     */
    public function widgetsDashboardVisibles(): array
    {
        return $this->dashboard_widgets ?? self::WIDGETS_DASHBOARD_DEFAULT;
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }
    public function solicitudesHoraExtra()
{
    return $this->hasMany(
        HoraExtra::class,
        'registrado_por'
    );
}

public function autorizacionesHoraExtra()
{
    return $this->hasMany(
        HoraExtra::class,
        'autorizado_por'
    );
}
}