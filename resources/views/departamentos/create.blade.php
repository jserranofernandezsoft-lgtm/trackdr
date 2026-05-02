@extends('layouts.app')
@section('title','Nuevo Departamento')
@section('page-title','Nuevo Departamento')
@section('topbar-actions')
<a href="{{ route('departamentos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:500px;"><div class="card">
<form action="{{ route('departamentos.store') }}" method="POST">
@csrf
<div class="form-grid">
    <div class="form-group span-2"><label class="form-label">Nombre <span class="required">*</span></label><input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required></div>
    <div class="form-group"><label class="form-label">Código <span class="required">*</span></label><input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" maxlength="20" required><span class="form-hint">Ej: TI, RRHH, FIN</span></div>
    <div class="form-group"><label class="form-label">Estado</label><select name="estado" class="form-control"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
    <div class="form-group span-2"><label class="form-label">Descripción</label><textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion') }}</textarea></div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('departamentos.index') }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div></div>
@endsection
