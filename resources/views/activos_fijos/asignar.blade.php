@extends('layouts.app')
@section('title','Asignar Activo')
@section('page-title','Asignar — ' . $activosFijo->numero_activo)
@section('topbar-actions')
<a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:640px;">
<div class="card mb-4" style="border-left:4px solid var(--blue);">
    <div class="flex items-center gap-3">
        <span style="font-size:32px;">{{ $activosFijo->icono }}</span>
        <div>
            <div class="fw-600" style="font-size:16px;">{{ $activosFijo->nombre_completo }}</div>
            <div class="mono text-muted">{{ $activosFijo->numero_activo }}@if($activosFijo->serial) · {{ $activosFijo->serial }}@endif</div>
        </div>
    </div>
</div>
<div class="card">
<div class="section-title">Datos de la Asignación</div>
<p class="text-muted mb-4">Al asignar, el activo pasará a estado <strong>Asignado</strong> automáticamente.</p>
<form action="{{ route('activos-fijos.storeAsignacion',$activosFijo) }}" method="POST">
@csrf
<div class="form-grid">
    <div class="form-group span-2"><label class="form-label">Colaborador <span class="required">*</span></label><select name="colaborador_id" class="form-control" required><option value="">— Seleccione colaborador —</option>@foreach($colaboradores as $c)<option value="{{ $c->id }}" {{ old('colaborador_id')==$c->id?'selected':'' }}>{{ $c->nombre }} — {{ $c->departamento->nombre }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Fecha de Asignación <span class="required">*</span></label><input type="date" name="fecha_asignacion" class="form-control" value="{{ old('fecha_asignacion',now()->toDateString()) }}" required></div>
    <div class="form-group"><label class="form-label">Fecha de Devolución Estimada</label><input type="date" name="fecha_devolucion_estimada" class="form-control" value="{{ old('fecha_devolucion_estimada') }}"></div>
    <div class="form-group"><label class="form-label">Condición al Entregar <span class="required">*</span></label><select name="condicion_entrega" class="form-control" required><option value="bueno" {{ old('condicion_entrega','bueno')==='bueno'?'selected':'' }}>Bueno</option><option value="regular" {{ old('condicion_entrega')==='regular'?'selected':'' }}>Regular</option><option value="danado" {{ old('condicion_entrega')==='danado'?'selected':'' }}>Dañado</option></select></div>
    <div class="form-group"><label class="form-label">Ubicación</label><input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion',$activosFijo->ubicacion) }}" placeholder="Oficina, piso..."></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea></div>
</div>
<div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-success">Confirmar Asignación</button>
    <a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
