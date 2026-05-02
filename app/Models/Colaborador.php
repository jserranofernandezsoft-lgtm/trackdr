<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colaborador extends Model
{
    use SoftDeletes;

    protected $table = 'colaboradores';

    protected $fillable = [
        'nombre', 'correo', 'cargo', 'telefono',
        'departamento_id', 'estado', 'observaciones',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function asignacionesFijos(): HasMany
    {
        return $this->hasMany(AsignacionFijo::class)->orderByDesc('fecha_asignacion');
    }

    public function asignacionesMenores(): HasMany
    {
        return $this->hasMany(AsignacionMenor::class)->orderByDesc('fecha_asignacion');
    }

    public function asignacionesFijosActivas(): HasMany
    {
        return $this->hasMany(AsignacionFijo::class)->where('estado', 'activo');
    }

    public function asignacionesMenoresActivas(): HasMany
    {
        return $this->hasMany(AsignacionMenor::class)->where('estado', 'activo');
    }

    public function getTotalActivosAsignadosAttribute(): int
    {
        return $this->asignacionesFijosActivas()->count()
             + $this->asignacionesMenoresActivas()->count();
    }
}
