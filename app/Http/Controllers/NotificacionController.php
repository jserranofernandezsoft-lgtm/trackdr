<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(15)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'icono'   => $n->icono,
                'titulo'  => $n->titulo,
                'mensaje' => $n->mensaje,
                'url'     => $n->url,
                'leida'   => $n->leida,
                'color'   => $n->color,
                'tiempo'  => $n->created_at->diffForHumans(),
            ]);

        $noLeidas = Notificacion::where('usuario_id', auth()->id())
            ->where('leida', false)->count();

        return response()->json([
            'notificaciones' => $notificaciones,
            'no_leidas'      => $noLeidas,
        ]);
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== auth()->id()) abort(403);
        $notificacion->update(['leida' => true, 'leida_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('usuario_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true, 'leida_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function limpiar()
    {
        Notificacion::where('usuario_id', auth()->id())->where('leida', true)->delete();
        return back()->with('success', 'Notificaciones leídas eliminadas.');
    }
}
