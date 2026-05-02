@extends('layouts.app')
@section('title','Nuevo Usuario')
@section('page-title','Nuevo Usuario')
@section('topbar-actions')
<a href="{{ route('usuarios.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection

@section('content')
<div style="max-width:520px;">
<div class="card">
<form action="{{ route('usuarios.store') }}" method="POST">
@csrf
<div class="form-grid">
    <div class="form-group span-2">
        <label class="form-label">Nombre Completo <span class="required">*</span></label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
    </div>
    <div class="form-group span-2">
        <label class="form-label">Correo Electrónico <span class="required">*</span></label>
        <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Contraseña <span class="required">*</span></label>
        <input type="password" name="password" class="form-control" required minlength="8">
        <span class="form-hint">Mínimo 8 caracteres</span>
    </div>
    <div class="form-group">
        <label class="form-label">Confirmar Contraseña <span class="required">*</span></label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="form-label">Rol <span class="required">*</span></label>
        <select name="rol" class="form-control" required>
            <option value="consultor"      {{ old('rol')==='consultor'?'selected':'' }}>Consultor — Solo lectura</option>
            <option value="administrador"  {{ old('rol')==='administrador'?'selected':'' }}>Administrador — Acceso total</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-control">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
    </div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Crear Usuario</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
