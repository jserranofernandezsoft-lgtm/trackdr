<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActivoFijo extends Model
{
    use SoftDeletes;

    protected $table = 'activos_fijos';

    protected $fillable = [
        'numero_activo', 'tipo', 'marca', 'modelo', 'serial',
        'descripcion', 'condicion', 'estado', 'ubicacion',
        'departamento_id', 'valor_adquisicion', 'fecha_adquisicion',
        'proveedor', 'observaciones',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor_adquisicion' => 'decimal:2',
    ];

    // ── Catálogos ─────────────────────────────────────────────────────────────
    public static array $tipos = [
        'laptop'      => 'Laptop',
        'desktop'     => 'Desktop / Torre',
        'monitor'     => 'Monitor',
        'celular'     => 'Celular / Teléfono',
        'tablet'      => 'Tablet',
        'impresora'   => 'Impresora',
        'servidor'    => 'Servidor',
        'equipo_red'  => 'Equipo de Red',
        'ups'         => 'UPS / Regulador',
        'otro'        => 'Otro',
    ];

    public static array $estados = [
        'disponible'     => 'Disponible',
        'asignado'       => 'Asignado',
        'mantenimiento'  => 'En Mantenimiento',
        'baja'           => 'Dado de Baja',
        'robado_perdido' => 'Robado / Perdido',
        'bodega'         => 'En Bodega',
    ];

    public static array $estadoBadge = [
        'disponible'     => 'badge-green',
        'asignado'       => 'badge-purple',
        'mantenimiento'  => 'badge-amber',
        'baja'           => 'badge-red',
        'robado_perdido' => 'badge-red',
        'bodega'         => 'badge-gray',
    ];

    public static array $condiciones = [
        'bueno'   => 'Bueno',
        'regular' => 'Regular',
        'danado'  => 'Dañado',
    ];

    public static array $tipoIconos = [
        'laptop'      => '💻',
        'desktop'     => '🖥️',
        'monitor'     => '🖥️',
        'celular'     => '📱',
        'tablet'      => '📱',
        'impresora'   => '🖨️',
        'servidor'    => '🗄️',
        'equipo_red'  => '🔌',
        'ups'         => '🔋',
        'otro'        => '📦',
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────
    public function getTipoLabelAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? $this->tipo;
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::$estados[$this->estado] ?? $this->estado;
    }

    public function getCondicionLabelAttribute(): string
    {
        return self::$condiciones[$this->condicion] ?? $this->condicion;
    }

    public function getIconoAttribute(): string
    {
        return self::$tipoIconos[$this->tipo] ?? '📦';
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->marca . ' ' . $this->modelo);
    }

    public function isDisponible(): bool
    {
        return in_array($this->estado, ['disponible', 'bodega']);
    }

    // ── Número automático ─────────────────────────────────────────────────────
    public static function siguienteNumero(): string
    {
        $total = self::withTrashed()->count();
        return 'AF-' . str_pad($total + 1, 4, '0', STR_PAD_LEFT);
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionFijo::class)->orderByDesc('fecha_asignacion');
    }

    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(AsignacionFijo::class)->where('estado', 'activo');
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class)->orderByDesc('fecha_entrada');
    }

    public function fotos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FotoActivo::class, 'modelo_id')
                    ->where('modelo_tipo', 'fijo')
                    ->orderBy('orden');
    }

    public function mantenimientoActivo(): HasOne
    {
        return $this->hasOne(Mantenimiento::class)->where('estado', 'en_proceso');
    }
}
