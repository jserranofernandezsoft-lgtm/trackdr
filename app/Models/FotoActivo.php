<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FotoActivo extends Model
{
    protected $table = 'fotos_activos';

    protected $fillable = [
        'modelo_tipo',
        'modelo_id',
        'ruta',
        'nombre_original',
        'orden',
        'descripcion',
    ];

    // URL pública de la foto
    public function getUrlAttribute(): string
    {
        return Storage::url($this->ruta);
    }

    // Eliminar archivo al eliminar el registro
    protected static function booted(): void
    {
        static::deleting(function (FotoActivo $foto) {
            Storage::disk('public')->delete(
                str_replace('activos/', '', basename($foto->ruta))
            );
            Storage::disk('public')->delete($foto->ruta);
        });
    }
}
