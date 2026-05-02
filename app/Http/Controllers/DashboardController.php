<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\ActivoMenor;
use App\Models\Colaborador;
use App\Models\Departamento;
use App\Models\AsignacionFijo;
use App\Models\Mantenimiento;

class DashboardController extends Controller
{
    public function index()
    {
        // Activos Fijos
        $fijos = [
            'total'         => ActivoFijo::count(),
            'disponibles'   => ActivoFijo::where('estado', 'disponible')->count(),
            'asignados'     => ActivoFijo::where('estado', 'asignado')->count(),
            'mantenimiento' => ActivoFijo::where('estado', 'mantenimiento')->count(),
            'baja'          => ActivoFijo::where('estado', 'baja')->count(),
            'bodega'        => ActivoFijo::where('estado', 'bodega')->count(),
        ];

        // Activos Menores
        $menores = [
            'total'       => ActivoMenor::count(),
            'disponibles' => ActivoMenor::where('estado', 'disponible')->count(),
            'asignados'   => ActivoMenor::where('estado', 'asignado')->count(),
            'baja'        => ActivoMenor::where('estado', 'baja')->count(),
        ];

        // Colaboradores
        $colaboradores = [
            'total'   => Colaborador::count(),
            'activos' => Colaborador::where('estado', 'activo')->count(),
        ];

        // Departamentos
        $totalDepartamentos = Departamento::where('estado', 'activo')->count();

        // Mantenimientos en proceso
        $mantenimientosActivos = Mantenimiento::where('estado', 'en_proceso')
            ->with('activoFijo')
            ->orderByDesc('fecha_entrada')
            ->limit(5)
            ->get();

        // Últimas asignaciones
        $ultimasAsignaciones = AsignacionFijo::with(['activoFijo', 'colaborador.departamento'])
            ->where('estado', 'activo')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Activos por tipo
        $activosPorTipo = ActivoFijo::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        // ── Panel de Alertas ──────────────────────────────────────────────────
        $alertas = [];

        // 1. Colaboradores inactivos con activos asignados
        $colabInactivos = Colaborador::where('estado', 'inactivo')
            ->where(fn($q) => $q
                ->whereHas('asignacionesFijosActivas')
                ->orWhereHas('asignacionesMenoresActivas')
            )
            ->with(['departamento'])
            ->get();

        if ($colabInactivos->count()) {
            $alertas[] = [
                'tipo'         => 'danger',
                'icono'        => '👤',
                'titulo'       => $colabInactivos->count() . ' colaborador(es) inactivo(s) con activos asignados',
                'detalle'      => $colabInactivos->map(fn($c) => $c->nombre . ' (' . ($c->departamento->nombre ?? 'Sin depto.') . ')')->join(' · '),
                'accion'       => route('colaboradores.index', ['estado' => 'inactivo']),
                'accion_label' => 'Ver colaboradores',
            ];
        }

        // 2. Equipos más de 30 días en mantenimiento
        $mantLargo = Mantenimiento::where('estado', 'en_proceso')
            ->where('fecha_entrada', '<=', now()->subDays(30)->toDateString())
            ->with('activoFijo')
            ->get();

        if ($mantLargo->count()) {
            $alertas[] = [
                'tipo'         => 'warning',
                'icono'        => '🔧',
                'titulo'       => $mantLargo->count() . ' equipo(s) con más de 30 días en mantenimiento',
                'detalle'      => $mantLargo->map(fn($m) => $m->activoFijo->numero_activo . ' — ' . $m->dias . 'd')->join(' · '),
                'accion'       => route('activos-fijos.index', ['estado' => 'mantenimiento']),
                'accion_label' => 'Ver en mantenimiento',
            ];
        }

        // 3. Activos fijos sin departamento
        $sinDepto = ActivoFijo::whereNull('departamento_id')
            ->whereNotIn('estado', ['baja', 'robado_perdido'])
            ->count();

        if ($sinDepto) {
            $alertas[] = [
                'tipo'         => 'info',
                'icono'        => '🏢',
                'titulo'       => $sinDepto . ' activo(s) fijo(s) sin departamento asignado',
                'detalle'      => 'Estos activos no tienen un departamento responsable definido.',
                'accion'       => route('activos-fijos.index'),
                'accion_label' => 'Ver activos fijos',
            ];
        }

        // 4. Activos en bodega hace más de 60 días
        $bodegaFijos   = ActivoFijo::where('estado', 'bodega')->where('updated_at', '<=', now()->subDays(60))->count();
        $bodegaMenores = ActivoMenor::where('estado', 'bodega')->where('updated_at', '<=', now()->subDays(60))->count();
        $totalBodega   = $bodegaFijos + $bodegaMenores;

        if ($totalBodega) {
            $alertas[] = [
                'tipo'         => 'info',
                'icono'        => '📦',
                'titulo'       => $totalBodega . ' activo(s) en bodega por más de 60 días',
                'detalle'      => 'Podrían asignarse o darse de baja. ' . ($bodegaFijos ? "{$bodegaFijos} fijo(s)" : '') . ($bodegaMenores ? " · {$bodegaMenores} menor(es)" : ''),
                'accion'       => route('activos-fijos.index', ['estado' => 'bodega']),
                'accion_label' => 'Ver en bodega',
            ];
        }

        // 5. Activos menores sin departamento
        $sinDeptoMenor = ActivoMenor::whereNull('departamento_id')
            ->whereNotIn('estado', ['baja'])
            ->count();

        if ($sinDeptoMenor) {
            $alertas[] = [
                'tipo'         => 'info',
                'icono'        => '🖱️',
                'titulo'       => $sinDeptoMenor . ' activo(s) menor(es) sin departamento asignado',
                'detalle'      => 'Estos accesorios no tienen departamento responsable.',
                'accion'       => route('activos-menores.index'),
                'accion_label' => 'Ver activos menores',
            ];
        }

        return view('dashboard.index', compact(
            'fijos', 'menores', 'colaboradores', 'totalDepartamentos',
            'mantenimientosActivos', 'ultimasAsignaciones', 'activosPorTipo',
            'alertas'
        ));
    }
}
