<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionFijo extends Model
{
    use SoftDeletes;

    protected $table = 'asignaciones_fijos';

    protected $fillable = [
        'activo_fijo_id', 'colaborador_id',
        'fecha_asignacion', 'fecha_devolucion_estimada', 'fecha_devolucion_real',
        'condicion_entrega', 'condicion_devolucion',
        'ubicacion', 'observaciones', 'estado',
    ];

    protected $casts = [
        'fecha_asignacion'          => 'date',
        'fecha_devolucion_estimada' => 'date',
        'fecha_devolucion_real'     => 'date',
    ];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }
}
