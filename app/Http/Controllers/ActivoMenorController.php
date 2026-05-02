<?php

namespace App\Http\Controllers;

use App\Models\ActivoMenor;
use App\Models\AsignacionMenor;
use App\Models\Departamento;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActividadLog;

class ActivoMenorController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivoMenor::with(['departamento', 'asignacionActiva.colaborador']);

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
            'total'       => ActivoMenor::count(),
            'disponibles' => ActivoMenor::where('estado', 'disponible')->count(),
            'asignados'   => ActivoMenor::where('estado', 'asignado')->count(),
        ];

        return view('activos_menores.index', compact('activos', 'totales'));
    }

    public function create()
    {
        $departamentos = Departamento::where('estado', 'activo')->orderBy('nombre')->get();
        $numeroActivo  = ActivoMenor::siguienteNumero();
        return view('activos_menores.create', compact('departamentos', 'numeroActivo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_activo'     => 'required|string|max:50|unique:activos_menores,numero_activo',
            'tipo'              => 'required|in:' . implode(',', array_keys(ActivoMenor::$tipos)),
            'marca'             => 'nullable|string|max:100',
            'modelo'            => 'nullable|string|max:100',
            'serial'            => 'nullable|string|max:150',
            'descripcion'       => 'nullable|string|max:500',
            'condicion'         => 'required|in:bueno,regular,danado',
            'estado'            => 'required|in:' . implode(',', array_keys(ActivoMenor::$estados)),
            'ubicacion'         => 'nullable|string|max:150',
            'departamento_id'   => 'nullable|exists:departamentos,id',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'proveedor'         => 'nullable|string|max:150',
            'observaciones'     => 'nullable|string',
        ]);

        $activo = ActivoMenor::create($data);
        ActividadLog::registrar('crear', 'activo_menor', $activo->numero_activo . ' ' . $activo->nombre_completo, $activo->id);

        return redirect()->route('activos-menores.index')
            ->with('success', 'Activo menor registrado correctamente.');
    }

    public function show(ActivoMenor $activosMenore)
    {
        $activosMenore->load([
            'departamento',
            'asignacionActiva.colaborador.departamento',
            'asignaciones.colaborador.departamento',
            'fotos',
        ]);
        return view('activos_menores.show', compact('activosMenore'));
    }

    public function edit(ActivoMenor $activosMenore)
    {
        $departamentos = Departamento::where('estado', 'activo')->orderBy('nombre')->get();
        return view('activos_menores.edit', compact('activosMenore', 'departamentos'));
    }

    public function update(Request $request, ActivoMenor $activosMenore)
    {
        $data = $request->validate([
            'numero_activo'     => 'required|string|max:50|unique:activos_menores,numero_activo,' . $activosMenore->id,
            'tipo'              => 'required|in:' . implode(',', array_keys(ActivoMenor::$tipos)),
            'marca'             => 'nullable|string|max:100',
            'modelo'            => 'nullable|string|max:100',
            'serial'            => 'nullable|string|max:150',
            'descripcion'       => 'nullable|string|max:500',
            'condicion'         => 'required|in:bueno,regular,danado',
            'estado'            => 'required|in:' . implode(',', array_keys(ActivoMenor::$estados)),
            'ubicacion'         => 'nullable|string|max:150',
            'departamento_id'   => 'nullable|exists:departamentos,id',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'proveedor'         => 'nullable|string|max:150',
            'observaciones'     => 'nullable|string',
        ]);

        $activosMenore->update($data);
        ActividadLog::registrar('editar', 'activo_menor', $activosMenore->numero_activo . ' ' . $activosMenore->nombre_completo, $activosMenore->id);

        return redirect()->route('activos-menores.show', $activosMenore)
            ->with('success', 'Activo menor actualizado.');
    }

    // ── QR ────────────────────────────────────────────────────────────────────
    public function qr(ActivoMenor $activosMenore)
    {
        $activo = $activosMenore->load('departamento');
        $url = route('activos-menores.show', $activosMenore);
        return view('activos_menores.qr', compact('activo', 'url'));
    }

    // ── Asignar ───────────────────────────────────────────────────────────────
    public function asignar(ActivoMenor $activosMenore)
    {
        if (!$activosMenore->isDisponible()) {
            return back()->with('error', 'El activo no está disponible para asignación.');
        }
        $colaboradores = Colaborador::where('estado', 'activo')
            ->with('departamento')->orderBy('nombre')->get();
        return view('activos_menores.asignar', compact('activosMenore', 'colaboradores'));
    }

    public function storeAsignacion(Request $request, ActivoMenor $activosMenore)
    {
        $data = $request->validate([
            'colaborador_id'    => 'required|exists:colaboradores,id',
            'fecha_asignacion'  => 'required|date',
            'condicion_entrega' => 'required|in:bueno,regular,danado',
            'observaciones'     => 'nullable|string',
        ]);

        if (!$activosMenore->isDisponible()) {
            return back()->with('error', 'El activo no está disponible.');
        }

        DB::transaction(function () use ($data, $activosMenore) {
            AsignacionMenor::create(array_merge($data, [
                'activo_menor_id' => $activosMenore->id,
                'estado'          => 'activo',
            ]));
            $activosMenore->update([
                'estado'    => 'asignado',
                'condicion' => $data['condicion_entrega'],
            ]);
        });

        ActividadLog::registrar('asignar', 'activo_menor',
            $activosMenore->numero_activo . ' ' . $activosMenore->nombre_completo,
            $activosMenore->id,
            'Asignado a colaborador #' . $data['colaborador_id']
        );

        return redirect()->route('activos-menores.show', $activosMenore)
            ->with('success', 'Activo asignado correctamente.');
    }

    // ── Devolver ──────────────────────────────────────────────────────────────
    public function devolver(Request $request, ActivoMenor $activosMenore)
    {
        $data = $request->validate([
            'condicion_devolucion' => 'required|in:bueno,regular,danado',
            'observaciones'        => 'nullable|string',
        ]);

        $asignacion = $activosMenore->asignacionActiva;
        if (!$asignacion) {
            return back()->with('error', 'Sin asignación activa.');
        }

        DB::transaction(function () use ($data, $activosMenore, $asignacion) {
            $asignacion->update([
                'estado'               => 'devuelto',
                'fecha_devolucion_real'=> now()->toDateString(),
                'condicion_devolucion' => $data['condicion_devolucion'],
                'observaciones'        => $data['observaciones'],
            ]);
            $activosMenore->update([
                'estado'    => 'disponible',
                'condicion' => $data['condicion_devolucion'],
            ]);
        });

        ActividadLog::registrar('devolver', 'activo_menor',
            $activosMenore->numero_activo . ' ' . $activosMenore->nombre_completo,
            $activosMenore->id,
            'Condición: ' . $data['condicion_devolucion']
        );

        return redirect()->route('activos-menores.show', $activosMenore)
            ->with('success', 'Activo devuelto correctamente.');
    }
}
