<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\ActivoMenor;
use App\Models\FotoActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FotoController extends Controller
{
    // ── Subir fotos a un Activo Fijo ──────────────────────────────────────────
    public function storeParaFijo(Request $request, ActivoFijo $activosFijo)
    {
        $request->validate([
            'fotos'      => 'required|array|min:1',
            'fotos.*'    => 'image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
        ]);

        $orden = $activosFijo->fotos()->max('orden') ?? 0;

        foreach ($request->file('fotos') as $archivo) {
            $nombre = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
            $ruta   = $archivo->storeAs('activos/fijos', $nombre, 'public');

            FotoActivo::create([
                'modelo_tipo'     => 'fijo',
                'modelo_id'       => $activosFijo->id,
                'ruta'            => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'orden'           => ++$orden,
            ]);
        }

        return back()->with('success', count($request->file('fotos')) . ' foto(s) subida(s) correctamente.');
    }

    // ── Subir fotos a un Activo Menor ─────────────────────────────────────────
    public function storeParaMenor(Request $request, ActivoMenor $activosMenore)
    {
        $request->validate([
            'fotos'   => 'required|array|min:1',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $orden = $activosMenore->fotos()->max('orden') ?? 0;

        foreach ($request->file('fotos') as $archivo) {
            $nombre = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
            $ruta   = $archivo->storeAs('activos/menores', $nombre, 'public');

            FotoActivo::create([
                'modelo_tipo'     => 'menor',
                'modelo_id'       => $activosMenore->id,
                'ruta'            => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'orden'           => ++$orden,
            ]);
        }

        return back()->with('success', count($request->file('fotos')) . ' foto(s) subida(s) correctamente.');
    }

    // ── Eliminar una foto ─────────────────────────────────────────────────────
    public function destroy(FotoActivo $foto)
    {
        // Determinar a dónde volver
        $tipo = $foto->modelo_tipo;
        $id   = $foto->modelo_id;

        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        $ruta = $tipo === 'fijo'
            ? route('activos-fijos.show', $id)
            : route('activos-menores.show', $id);

        return redirect($ruta)->with('success', 'Foto eliminada.');
    }
}
