<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActividadLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo'   => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password'], 'estado' => 'activo'], $remember)) {
            $request->session()->regenerate();
            ActividadLog::registrar('login', 'sesion', Auth::user()->nombre, Auth::id(), 'Inicio de sesión desde ' . $request->ip());
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('correo'))
            ->withErrors(['correo' => 'Credenciales incorrectas o cuenta inactiva.']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActividadLog::registrar('logout', 'sesion', Auth::user()->nombre, Auth::id());
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
