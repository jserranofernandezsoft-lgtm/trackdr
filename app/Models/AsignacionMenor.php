<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionMenor extends Model
{
    use SoftDeletes;

    protected $table = 'asignaciones_menores';

    protected $fillable = [
        'activo_menor_id', 'colaborador_id',
        'fecha_asignacion', 'fecha_devolucion_real',
        'condicion_entrega', 'condicion_devolucion',
        'observaciones', 'estado',
    ];

    protected $casts = [
        'fecha_asignacion'    => 'date',
        'fecha_devolucion_real' => 'date',
    ];

    public function activoMenor(): BelongsTo
    {
        return $this->belongsTo(ActivoMenor::class);
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }
}
