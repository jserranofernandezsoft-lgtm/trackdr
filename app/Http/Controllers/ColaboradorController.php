<?php
namespace App\Http\Controllers;
use App\Models\Colaborador;
use App\Models\ActividadLog;
use App\Models\Notificacion;
use App\Models\Departamento;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $query = Colaborador::with('departamento');
        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(fn($sq) => $sq->where('nombre','like',"%$q%")->orWhere('correo','like',"%$q%")->orWhere('cargo','like',"%$q%"));
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('departamento_id')) $query->where('departamento_id', $request->departamento_id);
        $colaboradores = $query->orderBy('nombre')->paginate(20)->withQueryString();
        $departamentos = Departamento::where('estado','activo')->orderBy('nombre')->get();
        return view('colaboradores.index', compact('colaboradores','departamentos'));
    }

    public function create()
    {
        $departamentos = Departamento::where('estado','activo')->orderBy('nombre')->get();
        return view('colaboradores.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:150',
            'correo'         => 'required|email|unique:colaboradores,correo',
            'cargo'          => 'nullable|string|max:100',
            'telefono'       => 'nullable|string|max:20',
            'departamento_id'=> 'required|exists:departamentos,id',
            'estado'         => 'required|in:activo,inactivo',
            'observaciones'  => 'nullable|string',
        ]);
        $col = Colaborador::create($data);
        ActividadLog::registrar('crear', 'colaborador', $col->nombre, $col->id);
        Notificacion::paraAdmins(
            'colaborador', '👤',
            'Nuevo colaborador: ' . $col->nombre,
            ($col->cargo ?? '') . ' · ' . ($col->departamento?->nombre ?? ''),
            route('colaboradores.show', $col)
        );
        return redirect()->route('colaboradores.index')->with('success','Colaborador registrado.');
    }

    public function show(Colaborador $colaboradore)
    {
        $colaboradore->load([
            'departamento',
            'asignacionesFijos' => fn($q) => $q->with('activoFijo')->orderByDesc('fecha_asignacion'),
            'asignacionesMenores' => fn($q) => $q->with('activoMenor')->orderByDesc('fecha_asignacion'),
        ]);
        return view('colaboradores.show', compact('colaboradore'));
    }

    public function edit(Colaborador $colaboradore)
    {
        $departamentos = Departamento::where('estado','activo')->orderBy('nombre')->get();
        return view('colaboradores.edit', compact('colaboradore','departamentos'));
    }

    public function update(Request $request, Colaborador $colaboradore)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:150',
            'correo'         => 'required|email|unique:colaboradores,correo,'.$colaboradore->id,
            'cargo'          => 'nullable|string|max:100',
            'telefono'       => 'nullable|string|max:20',
            'departamento_id'=> 'required|exists:departamentos,id',
            'estado'         => 'required|in:activo,inactivo',
            'observaciones'  => 'nullable|string',
        ]);
        $colaboradore->update($data);
        ActividadLog::registrar('editar', 'colaborador', $colaboradore->nombre, $colaboradore->id);
        return redirect()->route('colaboradores.show',$colaboradore)->with('success','Actualizado.');
    }

    public function destroy(Colaborador $colaboradore)
    {
        if ($colaboradore->asignacionesFijosActivas()->exists() || $colaboradore->asignacionesMenoresActivas()->exists()) {
            return back()->with('error','Tiene activos asignados. Devuélvalos primero.');
        }
        ActividadLog::registrar('eliminar', 'colaborador', $colaboradore->nombre, $colaboradore->id);
        $colaboradore->delete();
        return redirect()->route('colaboradores.index')->with('success','Colaborador eliminado.');
    }
}
