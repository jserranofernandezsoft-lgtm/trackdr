@extends('layouts.app')
@section('title','Editar Colaborador')
@section('page-title','Editar — ' . $colaboradore->nombre)
@section('topbar-actions')
<a href="{{ route('colaboradores.show',$colaboradore) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:580px;"><div class="card">
<form action="{{ route('colaboradores.update',$colaboradore) }}" method="POST">
@csrf @method('PUT')
<div class="form-grid">
    <div class="form-group span-2"><label class="form-label">Nombre <span class="required">*</span></label><input type="text" name="nombre" class="form-control" value="{{ old('nombre',$colaboradore->nombre) }}" required></div>
    <div class="form-group span-2"><label class="form-label">Correo <span class="required">*</span></label><input type="email" name="correo" class="form-control" value="{{ old('correo',$colaboradore->correo) }}" required></div>
    <div class="form-group"><label class="form-label">Cargo</label><input type="text" name="cargo" class="form-control" value="{{ old('cargo',$colaboradore->cargo) }}"></div>
    <div class="form-group"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="{{ old('telefono',$colaboradore->telefono) }}"></div>
    <div class="form-group"><label class="form-label">Departamento <span class="required">*</span></label><select name="departamento_id" class="form-control" required><option value="">— Seleccione —</option>@foreach($departamentos as $dep)<option value="{{ $dep->id }}" {{ $colaboradore->departamento_id==$dep->id?'selected':'' }}>{{ $dep->nombre }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Estado</label><select name="estado" class="form-control"><option value="activo" {{ $colaboradore->estado==='activo'?'selected':'' }}>Activo</option><option value="inactivo" {{ $colaboradore->estado==='inactivo'?'selected':'' }}>Inactivo</option></select></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones',$colaboradore->observaciones) }}</textarea></div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="{{ route('colaboradores.show',$colaboradore) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div></div>
@endsection
