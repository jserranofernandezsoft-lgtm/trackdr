<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id', 'tipo', 'icono', 'titulo', 'mensaje', 'url', 'leida', 'leida_at',
    ];

    protected $casts = [
        'leida'    => 'boolean',
        'leida_at' => 'datetime',
    ];

    public static array $colores = [
        'mantenimiento' => '#d97706',
        'asignacion'    => '#7c3aed',
        'devolucion'    => '#2563eb',
        'alerta'        => '#dc2626',
        'colaborador'   => '#0891b2',
        'sistema'       => '#475569',
    ];

    public function getColorAttribute(): string
    {
        return self::$colores[$this->tipo] ?? '#475569';
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public static function paraAdmins(
        string $tipo, string $icono, string $titulo,
        ?string $mensaje = null, ?string $url = null
    ): void {
        $admins = Usuario::where('rol', 'administrador')->where('estado', 'activo')->pluck('id');
        foreach ($admins as $adminId) {
            $existe = self::where('usuario_id', $adminId)
                ->where('titulo', $titulo)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->exists();
            if (!$existe) {
                self::create([
                    'usuario_id' => $adminId, 'tipo' => $tipo, 'icono' => $icono,
                    'titulo' => $titulo, 'mensaje' => $mensaje, 'url' => $url, 'leida' => false,
                ]);
            }
        }
    }

    public static function noLeidasDelUsuario(): int
    {
        if (!auth()->check()) return 0;
        return self::where('usuario_id', auth()->id())->where('leida', false)->count();
    }
}
