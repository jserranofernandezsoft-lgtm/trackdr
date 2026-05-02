@extends('layouts.app')
@section('title','Editar Usuario')
@section('page-title','Editar — ' . $usuario->nombre)
@section('topbar-actions')
<a href="{{ route('usuarios.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection

@section('content')
<div style="max-width:520px;">
<div class="card">
<form action="{{ route('usuarios.update', $usuario) }}" method="POST">
@csrf @method('PUT')
<div class="form-grid">
    <div class="form-group span-2">
        <label class="form-label">Nombre Completo <span class="required">*</span></label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
    </div>
    <div class="form-group span-2">
        <label class="form-label">Correo Electrónico <span class="required">*</span></label>
        <input type="email" name="correo" class="form-control" value="{{ old('correo', $usuario->correo) }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Nueva Contraseña</label>
        <input type="password" name="password" class="form-control" minlength="8" placeholder="Dejar vacío para no cambiar">
        <span class="form-hint">Mínimo 8 caracteres</span>
    </div>
    <div class="form-group">
        <label class="form-label">Confirmar Contraseña</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="form-group">
        <label class="form-label">Rol <span class="required">*</span></label>
        <select name="rol" class="form-control" required
            {{ $usuario->id === auth()->id() ? 'disabled' : '' }}>
            <option value="consultor"     {{ $usuario->rol==='consultor'?'selected':'' }}>Consultor — Solo lectura</option>
            <option value="administrador" {{ $usuario->rol==='administrador'?'selected':'' }}>Administrador — Acceso total</option>
        </select>
        @if($usuario->id === auth()->id())
            <input type="hidden" name="rol" value="{{ $usuario->rol }}">
            <span class="form-hint">No puedes cambiar tu propio rol</span>
        @endif
    </div>
    <div class="form-group">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-control"
            {{ $usuario->id === auth()->id() ? 'disabled' : '' }}>
            <option value="activo"   {{ $usuario->estado==='activo'?'selected':'' }}>Activo</option>
            <option value="inactivo" {{ $usuario->estado==='inactivo'?'selected':'' }}>Inactivo</option>
        </select>
        @if($usuario->id === auth()->id())
            <input type="hidden" name="estado" value="{{ $usuario->estado }}">
            <span class="form-hint">No puedes desactivar tu propia cuenta</span>
        @endif
    </div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
