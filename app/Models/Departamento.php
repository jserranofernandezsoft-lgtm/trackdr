<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use SoftDeletes;

    protected $table = 'departamentos';

    protected $fillable = ['nombre', 'codigo', 'descripcion', 'estado'];

    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class);
    }

    public function colaboradoresActivos(): HasMany
    {
        return $this->hasMany(Colaborador::class)->where('estado', 'activo');
    }

    public function activosFijos(): HasMany
    {
        return $this->hasMany(ActivoFijo::class);
    }

    public function activosMenores(): HasMany
    {
        return $this->hasMany(ActivoMenor::class);
    }

    public function getTotalActivosAttribute(): int
    {
        return $this->activosFijos()->count() + $this->activosMenores()->count();
    }
}
