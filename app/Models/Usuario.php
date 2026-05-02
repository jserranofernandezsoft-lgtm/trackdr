<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ── Helpers de rol ────────────────────────────────────────────────────────
    public function esAdministrador(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esConsultor(): bool
    {
        return $this->rol === 'consultor';
    }

    public function getRolLabelAttribute(): string
    {
        return match($this->rol) {
            'administrador' => 'Administrador',
            'consultor'     => 'Consultor',
            default         => $this->rol,
        };
    }
}
