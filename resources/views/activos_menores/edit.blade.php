@extends('layouts.app')
@section('title','Editar Activo Menor')
@section('page-title','Editar — ' . $activosMenore->numero_activo)
@section('topbar-actions')
<a href="{{ route('activos-menores.show',$activosMenore) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:700px;"><div class="card">
<form action="{{ route('activos-menores.update',$activosMenore) }}" method="POST">
@csrf @method('PUT')
<div class="section-title">Identificación</div>
<div class="form-grid-3 mb-6">
    <div class="form-group"><label class="form-label">Número de Activo <span class="required">*</span></label><input type="text" name="numero_activo" class="form-control" value="{{ old('numero_activo',$activosMenore->numero_activo) }}" required></div>
    <div class="form-group"><label class="form-label">Tipo <span class="required">*</span></label><select name="tipo" class="form-control" required>@foreach(\App\Models\ActivoMenor::$tipos as $v=>$l)<option value="{{ $v }}" {{ $activosMenore->tipo===$v?'selected':'' }}>{{ \App\Models\ActivoMenor::$tipoIconos[$v]??'' }} {{ $l }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Condición <span class="required">*</span></label><select name="condicion" class="form-control" required>@foreach(\App\Models\ActivoMenor::$condiciones as $v=>$l)<option value="{{ $v }}" {{ $activosMenore->condicion===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Marca</label><input type="text" name="marca" class="form-control" value="{{ old('marca',$activosMenore->marca) }}"></div>
    <div class="form-group"><label class="form-label">Modelo</label><input type="text" name="modelo" class="form-control" value="{{ old('modelo',$activosMenore->modelo) }}"></div>
    <div class="form-group"><label class="form-label">Serial</label><input type="text" name="serial" class="form-control" value="{{ old('serial',$activosMenore->serial) }}"></div>
</div>
<div class="form-grid mb-6">
    <div class="form-group"><label class="form-label">Estado</label><select name="estado" class="form-control">@foreach(\App\Models\ActivoMenor::$estados as $v=>$l)<option value="{{ $v }}" {{ $activosMenore->estado===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Departamento</label><select name="departamento_id" class="form-control"><option value="">— General empresa —</option>@foreach($departamentos as $dep)<option value="{{ $dep->id }}" {{ $activosMenore->departamento_id==$dep->id?'selected':'' }}>{{ $dep->nombre }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Ubicación</label><input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion',$activosMenore->ubicacion) }}"></div>
    <div class="form-group"><label class="form-label">Proveedor</label><input type="text" name="proveedor" class="form-control" value="{{ old('proveedor',$activosMenore->proveedor) }}"></div>
    <div class="form-group"><label class="form-label">Valor</label><input type="number" name="valor_adquisicion" class="form-control" value="{{ old('valor_adquisicion',$activosMenore->valor_adquisicion) }}" min="0" step="0.01"></div>
    <div class="form-group"><label class="form-label">Fecha Adquisición</label><input type="date" name="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion',$activosMenore->fecha_adquisicion?->format('Y-m-d')) }}"></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones',$activosMenore->observaciones) }}</textarea></div>
</div>
<div style="padding-top:16px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="{{ route('activos-menores.show',$activosMenore) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div></div>
@endsection
