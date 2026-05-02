<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\AsignacionFijo;
use App\Models\Mantenimiento;
use App\Models\Departamento;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActividadLog;
use App\Models\Notificacion;

class ActivoFijoController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivoFijo::with(['departamento', 'asignacionActiva.colaborador']);

        if ($request->filled('tipo'))   $query->where('tipo', $request->tipo);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(fn($sq) => $sq
                ->where('numero_activo', 'like', "%$q%")
                ->orWhere('marca',       'like', "%$q%")
                ->orWhere('modelo',      'like', "%$q%")
                ->orWhere('serial',      'like', "%$q%")
            );
        }

        $activos = $query->orderBy('numero_activo')->paginate(20)->withQueryString();

        $totales = [
            'total'         => ActivoFijo::count(),
            'disponibles'   => ActivoFijo::where('estado', 'disponible')->count(),
            'asignados'     => ActivoFijo::where('estado', 'asignado')->count(),
            'mantenimiento' => ActivoFijo::where('estado', 'mantenimiento')->count(),
        ];

        return view('activos_fijos.index', compact('activos', 'totales'));
    }

    public function create()
    {
        $departamentos = Departamento::where('estado', 'activo')->orderBy('nombre')->get();
        $numeroActivo  = ActivoFijo::siguienteNumero();
        return view('activos_fijos.create', compact('departamentos', 'numeroActivo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_activo'     => 'required|string|max:50|unique:activos_fijos,numero_activo',
            'tipo'              => 'required|in:' . implode(',', array_keys(ActivoFijo::$tipos)),
            'marca'             => 'required|string|max:100',
            'modelo'            => 'required|string|max:100',
            'serial'            => 'nullable|string|max:150|unique:activos_fijos,serial',
            'descripcion'       => 'nullable|string|max:500',
            'condicion'         => 'required|in:bueno,regular,danado',
            'estado'            => 'required|in:' . implode(',', array_keys(ActivoFijo::$estados)),
            'ubicacion'         => 'nullable|string|max:150',
            'departamento_id'   => 'nullable|exists:departamentos,id',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'proveedor'         => 'nullable|string|max:150',
            'observaciones'     => 'nullable|string',
        ]);

        $activo = ActivoFijo::create($data);
        ActividadLog::registrar('crear', 'activo_fijo', $activo->numero_activo . ' ' . $activo->nombre_completo, $activo->id);

        return redirect()->route('activos-fijos.index')
            ->with('success', 'Activo registrado correctamente.');
    }

    public function show(ActivoFijo $activosFijo)
    {
        $activosFijo->load([
            'departamento',
            'asignacionActiva.colaborador.departamento',
            'asignaciones.colaborador.departamento',
            'mantenimientos',
            'fotos',
        ]);
        return view('activos_fijos.show', compact('activosFijo'));
    }

    public function edit(ActivoFijo $activosFijo)
    {
        $departamentos = Departamento::where('estado', 'activo')->orderBy('nombre')->get();
        return view('activos_fijos.edit', compact('activosFijo', 'departamentos'));
    }

    public function update(Request $request, ActivoFijo $activosFijo)
    {
        $data = $request->validate([
            'numero_activo'     => 'required|string|max:50|unique:activos_fijos,numero_activo,' . $activosFijo->id,
            'tipo'              => 'required|in:' . implode(',', array_keys(ActivoFijo::$tipos)),
            'marca'             => 'required|string|max:100',
            'modelo'            => 'required|string|max:100',
            'serial'            => 'nullable|string|max:150|unique:activos_fijos,serial,' . $activosFijo->id,
            'descripcion'       => 'nullable|string|max:500',
            'condicion'         => 'required|in:bueno,regular,danado',
            'estado'            => 'required|in:' . implode(',', array_keys(ActivoFijo::$estados)),
            'ubicacion'         => 'nullable|string|max:150',
            'departamento_id'   => 'nullable|exists:departamentos,id',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'proveedor'         => 'nullable|string|max:150',
            'observaciones'     => 'nullable|string',
        ]);

        $activosFijo->update($data);
        ActividadLog::registrar('editar', 'activo_fijo', $activosFijo->numero_activo . ' ' . $activosFijo->nombre_completo, $activosFijo->id);

        return redirect()->route('activos-fijos.show', $activosFijo)
            ->with('success', 'Activo actualizado correctamente.');
    }

    // ── QR ────────────────────────────────────────────────────────────────────
    public function qr(ActivoFijo $activosFijo)
    {
        $activo = $activosFijo->load('departamento');
        $url = route('activos-fijos.show', $activosFijo);
        return view('activos_fijos.qr', compact('activo', 'url'));
    }

    // ── Asignar ───────────────────────────────────────────────────────────────
    public function asignar(ActivoFijo $activosFijo)
    {
        if (!$activosFijo->isDisponible()) {
            return back()->with('error', 'El activo no está disponible para asignación.');
        }
        $colaboradores = Colaborador::where('estado', 'activo')
            ->with('departamento')->orderBy('nombre')->get();
        return view('activos_fijos.asignar', compact('activosFijo', 'colaboradores'));
    }

    public function storeAsignacion(Request $request, ActivoFijo $activosFijo)
    {
        $data = $request->validate([
            'colaborador_id'            => 'required|exists:colaboradores,id',
            'fecha_asignacion'          => 'required|date',
            'fecha_devolucion_estimada' => 'nullable|date|after_or_equal:fecha_asignacion',
            'condicion_entrega'         => 'required|in:bueno,regular,danado',
            'ubicacion'                 => 'nullable|string|max:150',
            'observaciones'             => 'nullable|string',
        ]);

        if (!$activosFijo->isDisponible()) {
            return back()->with('error', 'El activo no está disponible.');
        }

        DB::transaction(function () use ($data, $activosFijo) {
            AsignacionFijo::create(array_merge($data, [
                'activo_fijo_id' => $activosFijo->id,
                'estado'         => 'activo',
            ]));
            $activosFijo->update([
                'estado'    => 'asignado',
                'ubicacion' => $data['ubicacion'] ?? $activosFijo->ubicacion,
                'condicion' => $data['condicion_entrega'],
            ]);
        });

        ActividadLog::registrar('asignar', 'activo_fijo',
            $activosFijo->numero_activo . ' ' . $activosFijo->nombre_completo,
            $activosFijo->id,
            'Asignado a ' . \App\Models\Colaborador::find($data['colaborador_id'])?->nombre
        );

        Notificacion::paraAdmins(
            'asignacion', '🔄',
            $activosFijo->numero_activo . ' asignado',
            $activosFijo->nombre_completo . ' fue asignado a un colaborador.',
            route('activos-fijos.show', $activosFijo)
        );
        return redirect()->route('activos-fijos.show', $activosFijo)
            ->with('success', 'Activo asignado correctamente.');
    }

    // ── Devolver ──────────────────────────────────────────────────────────────
    public function devolver(Request $request, ActivoFijo $activosFijo)
    {
        $data = $request->validate([
            'condicion_devolucion' => 'required|in:bueno,regular,danado',
            'observaciones'        => 'nullable|string',
        ]);

        $asignacion = $activosFijo->asignacionActiva;
        if (!$asignacion) {
            return back()->with('error', 'Este activo no tiene asignación activa.');
        }

        DB::transaction(function () use ($data, $activosFijo, $asignacion) {
            $asignacion->update([
                'estado'               => 'devuelto',
                'fecha_devolucion_real'=> now()->toDateString(),
                'condicion_devolucion' => $data['condicion_devolucion'],
                'observaciones'        => $data['observaciones'],
            ]);
            $activosFijo->update([
                'estado'    => 'disponible',
                'condicion' => $data['condicion_devolucion'],
            ]);
        });

        ActividadLog::registrar('devolver', 'activo_fijo',
            $activosFijo->numero_activo . ' ' . $activosFijo->nombre_completo,
            $activosFijo->id,
            'Condición: ' . $data['condicion_devolucion']
        );

        Notificacion::paraAdmins(
            'devolucion', '🔙',
            $activosFijo->numero_activo . ' devuelto — condición: ' . $data['condicion_devolucion'],
            $activosFijo->nombre_completo . ' está disponible nuevamente.',
            route('activos-fijos.show', $activosFijo)
        );
        return redirect()->route('activos-fijos.show', $activosFijo)
            ->with('success', 'Activo devuelto. Ahora está disponible.');
    }

    // ── Mantenimiento ─────────────────────────────────────────────────────────
    public function crearMantenimiento(ActivoFijo $activosFijo)
    {
        return view('activos_fijos.mantenimiento_create', compact('activosFijo'));
    }

    public function storeMantenimiento(Request $request, ActivoFijo $activosFijo)
    {
        $data = $request->validate([
            'fecha_entrada'        => 'required|date',
            'tecnico_proveedor'    => 'nullable|string|max:150',
            'tipo'                 => 'required|in:preventivo,correctivo,garantia',
            'descripcion_problema' => 'nullable|string',
            'observaciones'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $activosFijo) {
            Mantenimiento::create([
                'activo_fijo_id'       => $activosFijo->id,
                'fecha_entrada'        => $data['fecha_entrada'],
                'tecnico_proveedor'    => $data['tecnico_proveedor'] ?? null,
                'tipo'                 => $data['tipo'],
                'descripcion_problema' => $data['descripcion_problema'] ?? null,
                'observaciones'        => $data['observaciones'] ?? null,
                'estado'               => 'en_proceso',
            ]);
            $activosFijo->update(['estado' => 'mantenimiento']);
        });

        ActividadLog::registrar('mantenimiento', 'activo_fijo',
            $activosFijo->numero_activo . ' ' . $activosFijo->nombre_completo,
            $activosFijo->id,
            $data['tipo'] . ' — ' . ($data['tecnico_proveedor'] ?? 'Sin técnico')
        );

        Notificacion::paraAdmins(
            'mantenimiento', '🔧',
            $activosFijo->numero_activo . ' enviado a mantenimiento',
            $activosFijo->nombre_completo . ' — ' . ucfirst($data['tipo']),
            route('activos-fijos.show', $activosFijo)
        );
        return redirect()->route('activos-fijos.show', $activosFijo)
            ->with('success', 'Activo enviado a mantenimiento.');
    }

    public function cerrarMantenimiento(Request $request, ActivoFijo $activosFijo, Mantenimiento $mantenimiento)
    {
        $data = $request->validate([
            'fecha_salida'         => 'required|date',
            'descripcion_solucion' => 'nullable|string',
            'costo'                => 'nullable|numeric|min:0',
            'condicion_resultado'  => 'required|in:bueno,regular,danado',
            'observaciones'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $activosFijo, $mantenimiento) {
            $mantenimiento->update([
                'fecha_salida'         => $data['fecha_salida'],
                'descripcion_solucion' => $data['descripcion_solucion'] ?? null,
                'costo'                => $data['costo'] ?? null,
                'estado'               => 'completado',
                'observaciones'        => $data['observaciones'] ?? null,
            ]);
            $activosFijo->update([
                'estado'    => 'disponible',
                'condicion' => $data['condicion_resultado'],
            ]);
        });

        ActividadLog::registrar('cerrar_mant', 'activo_fijo',
            $activosFijo->numero_activo . ' ' . $activosFijo->nombre_completo,
            $activosFijo->id,
            'Condición resultado: ' . $data['condicion_resultado']
        );

        Notificacion::paraAdmins(
            'mantenimiento', '✅',
            'Mantenimiento cerrado — ' . $activosFijo->numero_activo,
            $activosFijo->nombre_completo . ' disponible. Condición: ' . $data['condicion_resultado'],
            route('activos-fijos.show', $activosFijo)
        );
        return redirect()->route('activos-fijos.show', $activosFijo)
            ->with('success', 'Mantenimiento cerrado. Activo disponible nuevamente.');
    }
}
