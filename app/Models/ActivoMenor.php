<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActivoMenor extends Model
{
    use SoftDeletes;

    protected $table = 'activos_menores';

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
        'mouse'        => 'Mouse',
        'teclado'      => 'Teclado',
        'memoria_ram'  => 'Memoria RAM',
        'disco_duro'   => 'Disco Duro / SSD',
        'memoria_usb'  => 'Memoria USB',
        'webcam'       => 'Webcam',
        'auriculares'  => 'Auriculares / Headset',
        'cargador'     => 'Cargador / Fuente',
        'adaptador'    => 'Adaptador / Hub',
        'parlante'     => 'Parlante / Bocina',
        'otro'         => 'Otro Accesorio',
    ];

    public static array $estados = [
        'disponible' => 'Disponible',
        'asignado'   => 'Asignado',
        'baja'       => 'Dado de Baja',
        'bodega'     => 'En Bodega',
    ];

    public static array $estadoBadge = [
        'disponible' => 'badge-green',
        'asignado'   => 'badge-purple',
        'baja'       => 'badge-red',
        'bodega'     => 'badge-gray',
    ];

    public static array $condiciones = [
        'bueno'   => 'Bueno',
        'regular' => 'Regular',
        'danado'  => 'Dañado',
    ];

    public static array $tipoIconos = [
        'mouse'       => '🖱️',
        'teclado'     => '⌨️',
        'memoria_ram' => '💾',
        'disco_duro'  => '💿',
        'memoria_usb' => '🔌',
        'webcam'      => '📷',
        'auriculares' => '🎧',
        'cargador'    => '🔋',
        'adaptador'   => '🔌',
        'parlante'    => '🔊',
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
        return trim(($this->marca ?? '') . ' ' . ($this->modelo ?? '')) ?: $this->tipo_label;
    }

    public function isDisponible(): bool
    {
        return in_array($this->estado, ['disponible', 'bodega']);
    }

    public static function siguienteNumero(): string
    {
        $total = self::withTrashed()->count();
        return 'AM-' . str_pad($total + 1, 4, '0', STR_PAD_LEFT);
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionMenor::class)->orderByDesc('fecha_asignacion');
    }

    public function fotos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FotoActivo::class, 'modelo_id')
                    ->where('modelo_tipo', 'menor')
                    ->orderBy('orden');
    }

    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(AsignacionMenor::class)->where('estado', 'activo');
    }
}
