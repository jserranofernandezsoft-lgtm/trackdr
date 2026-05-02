<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\ActividadLog;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('nombre')->paginate(20);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'            => 'required|string|max:150',
            'correo'            => 'required|email|unique:usuarios,correo',
            'password'          => ['required', 'confirmed', Password::min(8)],
            'rol'               => 'required|in:administrador,consultor',
            'estado'            => 'required|in:activo,inactivo',
        ]);

        Usuario::create([
            'nombre'   => $data['nombre'],
            'correo'   => $data['correo'],
            'password' => Hash::make($data['password']),
            'rol'      => $data['rol'],
            'estado'   => $data['estado'],
        ]);

        ActividadLog::registrar('crear', 'usuario', $data['nombre']);
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'rol'    => 'required|in:administrador,consultor',
            'estado' => 'required|in:activo,inactivo',
        ];

        // Solo validar password si se ingresó algo
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $data = $request->validate($rules);

        $usuario->update([
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'rol'    => $data['rol'],
            'estado' => $data['estado'],
        ]);

        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($data['password'])]);
        }

        ActividadLog::registrar('editar', 'usuario', $usuario->nombre, $usuario->id);
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado.');
    }

    public function destroy(Usuario $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        ActividadLog::registrar('eliminar', 'usuario', $usuario->nombre, $usuario->id);
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado.');
    }
}
