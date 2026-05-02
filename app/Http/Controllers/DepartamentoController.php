<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::withCount(['colaboradores','activosFijos','activosMenores'])->orderBy('nombre')->paginate(20);
        return view('departamentos.index', compact('departamentos'));
    }

    public function create() { return view('departamentos.create'); }

    public function store(Request $request)
    {
        $request->validate(['nombre'=>'required|string|max:100','codigo'=>'required|string|max:20|unique:departamentos,codigo','descripcion'=>'nullable|string','estado'=>'required|in:activo,inactivo']);
        Departamento::create($request->only(['nombre','codigo','descripcion','estado']));
        return redirect()->route('departamentos.index')->with('success','Departamento creado.');
    }

    public function show(Departamento $departamento)
    {
        $departamento->load(['colaboradoresActivos','activosFijos'=>fn($q)=>$q->with('asignacionActiva.colaborador')->orderBy('numero_activo'),'activosMenores'=>fn($q)=>$q->with('asignacionActiva.colaborador')->orderBy('numero_activo')]);
        return view('departamentos.show', compact('departamento'));
    }

    public function edit(Departamento $departamento) { return view('departamentos.edit', compact('departamento')); }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate(['nombre'=>'required|string|max:100','codigo'=>'required|string|max:20|unique:departamentos,codigo,'.$departamento->id,'descripcion'=>'nullable|string','estado'=>'required|in:activo,inactivo']);
        $departamento->update($request->only(['nombre','codigo','descripcion','estado']));
        return redirect()->route('departamentos.index')->with('success','Actualizado.');
    }

    public function destroy(Departamento $departamento)
    {
        if ($departamento->colaboradores()->exists()) return back()->with('error','Tiene colaboradores asociados.');
        $departamento->delete();
        return redirect()->route('departamentos.index')->with('success','Eliminado.');
    }
}
