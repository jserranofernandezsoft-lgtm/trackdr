@extends('layouts.app')
@section('title','Editar Activo')
@section('page-title','Editar — ' . $activosFijo->numero_activo)
@section('topbar-actions')
<a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:780px;"><div class="card">
<form action="{{ route('activos-fijos.update',$activosFijo) }}" method="POST">
@csrf @method('PUT')
<div class="section-title">Identificación</div>
<div class="form-grid-3 mb-6">
    <div class="form-group"><label class="form-label">Número de Activo <span class="required">*</span></label><input type="text" name="numero_activo" class="form-control" value="{{ old('numero_activo',$activosFijo->numero_activo) }}" required></div>
    <div class="form-group"><label class="form-label">Tipo <span class="required">*</span></label><select name="tipo" class="form-control" required>@foreach(\App\Models\ActivoFijo::$tipos as $v=>$l)<option value="{{ $v }}" {{ $activosFijo->tipo===$v?'selected':'' }}>{{ \App\Models\ActivoFijo::$tipoIconos[$v]??'' }} {{ $l }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Condición <span class="required">*</span></label><select name="condicion" class="form-control" required>@foreach(\App\Models\ActivoFijo::$condiciones as $v=>$l)<option value="{{ $v }}" {{ $activosFijo->condicion===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Marca <span class="required">*</span></label><input type="text" name="marca" class="form-control" value="{{ old('marca',$activosFijo->marca) }}" required></div>
    <div class="form-group"><label class="form-label">Modelo <span class="required">*</span></label><input type="text" name="modelo" class="form-control" value="{{ old('modelo',$activosFijo->modelo) }}" required></div>
    <div class="form-group"><label class="form-label">Serial</label><input type="text" name="serial" class="form-control" value="{{ old('serial',$activosFijo->serial) }}"></div>
</div>
<div class="section-title">Ubicación</div>
<div class="form-grid mb-6">
    <div class="form-group"><label class="form-label">Departamento</label><select name="departamento_id" class="form-control"><option value="">— General empresa —</option>@foreach($departamentos as $dep)<option value="{{ $dep->id }}" {{ $activosFijo->departamento_id==$dep->id?'selected':'' }}>{{ $dep->nombre }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Ubicación</label><input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion',$activosFijo->ubicacion) }}" placeholder="Oficina, piso..."></div>
    <div class="form-group"><label class="form-label">Estado</label><select name="estado" class="form-control">@foreach(\App\Models\ActivoFijo::$estados as $v=>$l)<option value="{{ $v }}" {{ $activosFijo->estado===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
</div>
<div class="section-title">Adquisición</div>
<div class="form-grid mb-6">
    <div class="form-group"><label class="form-label">Fecha Adquisición</label><input type="date" name="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion',$activosFijo->fecha_adquisicion?->format('Y-m-d')) }}"></div>
    <div class="form-group"><label class="form-label">Valor</label><input type="number" name="valor_adquisicion" class="form-control" value="{{ old('valor_adquisicion',$activosFijo->valor_adquisicion) }}" min="0" step="0.01"></div>
    <div class="form-group"><label class="form-label">Proveedor</label><input type="text" name="proveedor" class="form-control" value="{{ old('proveedor',$activosFijo->proveedor) }}"></div>
    <div class="form-group"><label class="form-label">Descripción</label><input type="text" name="descripcion" class="form-control" value="{{ old('descripcion',$activosFijo->descripcion) }}"></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones',$activosFijo->observaciones) }}</textarea></div>
</div>
<div style="padding-top:18px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div></div>
@endsection
