<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadLog extends Model
{
    protected $table = 'actividad_log';

    protected $fillable = [
        'usuario_id',
        'usuario_nombre',
        'accion',
        'modulo',
        'entidad_id',
        'entidad_label',
        'detalle',
        'ip',
    ];

    // Íconos por acción
    public static array $iconos = [
        'crear'         => '✅',
        'editar'        => '✏️',
        'eliminar'      => '🗑️',
        'asignar'       => '🔄',
        'devolver'      => '🔙',
        'mantenimiento' => '🔧',
        'cerrar_mant'   => '✔️',
        'subir_foto'    => '📷',
        'eliminar_foto' => '🗑️',
        'login'         => '🔐',
        'logout'        => '🚪',
    ];

    // Colores por acción
    public static array $colores = [
        'crear'         => 'badge-green',
        'editar'        => 'badge-blue',
        'eliminar'      => 'badge-red',
        'asignar'       => 'badge-purple',
        'devolver'      => 'badge-amber',
        'mantenimiento' => 'badge-amber',
        'cerrar_mant'   => 'badge-green',
        'subir_foto'    => 'badge-cyan',
        'eliminar_foto' => 'badge-red',
        'login'         => 'badge-gray',
        'logout'        => 'badge-gray',
    ];

    // Labels legibles por módulo
    public static array $modulos = [
        'activo_fijo'    => 'Activo Fijo',
        'activo_menor'   => 'Activo Menor',
        'colaborador'    => 'Colaborador',
        'departamento'   => 'Departamento',
        'usuario'        => 'Usuario',
        'foto'           => 'Foto',
        'sesion'         => 'Sesión',
    ];

    public function getIconoAttribute(): string
    {
        return self::$iconos[$this->accion] ?? '📋';
    }

    public function getColorAttribute(): string
    {
        return self::$colores[$this->accion] ?? 'badge-gray';
    }

    public function getModuloLabelAttribute(): string
    {
        return self::$modulos[$this->modulo] ?? $this->modulo;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    // Helper estático para registrar desde cualquier lado
    public static function registrar(
        string $accion,
        string $modulo,
        string $entidadLabel,
        ?string $entidadId = null,
        ?string $detalle = null
    ): void {
        self::create([
            'usuario_id'    => auth()->id(),
            'usuario_nombre'=> auth()->user()?->nombre ?? 'Sistema',
            'accion'        => $accion,
            'modulo'        => $modulo,
            'entidad_id'    => $entidadId,
            'entidad_label' => $entidadLabel,
            'detalle'       => $detalle,
            'ip'            => request()->ip(),
        ]);
    }
}
