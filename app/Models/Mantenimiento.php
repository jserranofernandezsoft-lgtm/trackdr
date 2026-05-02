<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    use SoftDeletes;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'activo_fijo_id', 'fecha_entrada', 'fecha_salida',
        'tecnico_proveedor', 'tipo',
        'descripcion_problema', 'descripcion_solucion',
        'costo', 'estado', 'observaciones',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida'  => 'date',
        'costo'         => 'decimal:2',
    ];

    public static array $tipos = [
        'preventivo' => 'Preventivo',
        'correctivo' => 'Correctivo',
        'garantia'   => 'Garantía',
    ];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }

    public function getDiasAttribute(): int
    {
        $fin = $this->fecha_salida ?? now();
        return (int) $this->fecha_entrada->diffInDays($fin);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? $this->tipo;
    }
}
