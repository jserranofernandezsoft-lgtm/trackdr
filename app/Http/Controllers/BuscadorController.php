<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\ActivoMenor;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class BuscadorController extends Controller
{
    public function buscar(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $resultados = [];

        // Activos Fijos
        ActivoFijo::where('numero_activo', 'like', "%$q%")
            ->orWhere('marca',  'like', "%$q%")
            ->orWhere('modelo', 'like', "%$q%")
            ->orWhere('serial', 'like', "%$q%")
            ->limit(5)
            ->get()
            ->each(function ($a) use (&$resultados) {
                $resultados[] = [
                    'tipo'   => 'Activo Fijo',
                    'icono'  => $a->icono,
                    'titulo' => $a->numero_activo . ' — ' . $a->nombre_completo,
                    'sub'    => $a->tipo_label . ' · ' . $a->estado_label,
                    'url'    => route('activos-fijos.show', $a),
                    'badge'  => 'badge-blue',
                ];
            });

        // Activos Menores
        ActivoMenor::where('numero_activo', 'like', "%$q%")
            ->orWhere('marca',  'like', "%$q%")
            ->orWhere('modelo', 'like', "%$q%")
            ->orWhere('serial', 'like', "%$q%")
            ->limit(5)
            ->get()
            ->each(function ($a) use (&$resultados) {
                $resultados[] = [
                    'tipo'   => 'Activo Menor',
                    'icono'  => $a->icono,
                    'titulo' => $a->numero_activo . ' — ' . $a->nombre_completo,
                    'sub'    => $a->tipo_label . ' · ' . $a->estado_label,
                    'url'    => route('activos-menores.show', $a),
                    'badge'  => 'badge-cyan',
                ];
            });

        // Colaboradores
        Colaborador::where('nombre', 'like', "%$q%")
            ->orWhere('correo', 'like', "%$q%")
            ->orWhere('cargo',  'like', "%$q%")
            ->limit(4)
            ->get()
            ->each(function ($c) use (&$resultados) {
                $resultados[] = [
                    'tipo'   => 'Colaborador',
                    'icono'  => '👤',
                    'titulo' => $c->nombre,
                    'sub'    => ($c->cargo ?? '') . ' · ' . ($c->departamento?->nombre ?? ''),
                    'url'    => route('colaboradores.show', $c),
                    'badge'  => 'badge-purple',
                ];
            });

        return response()->json($resultados);
    }
}
