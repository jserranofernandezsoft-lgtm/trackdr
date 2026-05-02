<?php

namespace App\Http\Controllers;

use App\Models\ActividadLog;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $query = ActividadLog::with('usuario')->orderByDesc('created_at');

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(fn($sq) => $sq
                ->where('entidad_label', 'like', "%$q%")
                ->orWhere('usuario_nombre', 'like', "%$q%")
                ->orWhere('detalle', 'like', "%$q%")
            );
        }

        $logs     = $query->paginate(30)->withQueryString();
        $usuarios = Usuario::orderBy('nombre')->get();

        return view('actividad.index', compact('logs', 'usuarios'));
    }
}
