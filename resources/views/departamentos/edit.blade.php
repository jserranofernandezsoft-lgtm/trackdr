@extends('layouts.app')
@section('title','Editar Departamento')
@section('page-title','Editar — ' . $departamento->nombre)
@section('topbar-actions')
<a href="{{ route('departamentos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:500px;"><div class="card">
<form action="{{ route('departamentos.update',$departamento) }}" method="POST">
@csrf @method('PUT')
<div class="form-grid">
    <div class="form-group span-2"><label class="form-label">Nombre <span class="required">*</span></label><input type="text" name="nombre" class="form-control" value="{{ old('nombre',$departamento->nombre) }}" required></div>
    <div class="form-group"><label class="form-label">Código <span class="required">*</span></label><input type="text" name="codigo" class="form-control" value="{{ old('codigo',$departamento->codigo) }}" maxlength="20" required></div>
    <div class="form-group"><label class="form-label">Estado</label><select name="estado" class="form-control"><option value="activo" {{ $departamento->estado==='activo'?'selected':'' }}>Activo</option><option value="inactivo" {{ $departamento->estado==='inactivo'?'selected':'' }}>Inactivo</option></select></div>
    <div class="form-group span-2"><label class="form-label">Descripción</label><textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion',$departamento->descripcion) }}</textarea></div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="{{ route('departamentos.index') }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div></div>
@endsection
