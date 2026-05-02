@extends('layouts.app')
@section('title','Usuarios')
@section('page-title','Usuarios del Sistema')
@section('topbar-actions')
<a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">+ Nuevo Usuario</a>
@endsection

@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Creado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($usuarios as $u)
            <tr>
                <td class="fw-600">
                    {{ $u->nombre }}
                    @if($u->id === auth()->id())
                        <span class="badge badge-blue" style="margin-left:6px;font-size:10px;">Tú</span>
                    @endif
                </td>
                <td class="mono" style="font-size:12.5px;">{{ $u->correo }}</td>
                <td>
                    @if($u->rol === 'administrador')
                        <span class="badge badge-blue">Administrador</span>
                    @else
                        <span class="badge badge-gray">Consultor</span>
                    @endif
                </td>
                <td>
                    @if($u->estado === 'activo')
                        <span class="badge badge-green">Activo</span>
                    @else
                        <span class="badge badge-red">Inactivo</span>
                    @endif
                </td>
                <td class="mono">{{ $u->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-outline btn-sm">Editar</a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('usuarios.destroy', $u) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar usuario {{ $u->nombre }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm" style="color:var(--red);border-color:var(--red);">Eliminar</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">
                <div class="empty-state">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                    <p>No hay usuarios registrados</p>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $usuarios->links() }}
</div>

<div class="card" style="margin-top:20px;border-left:4px solid var(--blue);">
    <div class="card-title" style="margin-bottom:8px;">Sobre los roles</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13.5px;">
        <div>
            <div style="font-weight:600;color:var(--blue);margin-bottom:4px;">🔵 Administrador</div>
            <div class="text-muted">Acceso total: crear, editar, asignar, devolver, subir fotos, gestionar usuarios.</div>
        </div>
        <div>
            <div style="font-weight:600;color:var(--text-muted);margin-bottom:4px;">⚪ Consultor</div>
            <div class="text-muted">Solo lectura: ver activos, colaboradores, departamentos y exportar reportes.</div>
        </div>
    </div>
</div>
@endsection
