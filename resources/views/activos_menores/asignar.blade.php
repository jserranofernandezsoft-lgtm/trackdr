@extends('layouts.app')
@section('title','Asignar Activo Menor')
@section('page-title','Asignar — ' . $activosMenore->numero_activo)
@section('topbar-actions')
<a href="{{ route('activos-menores.show',$activosMenore) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:600px;">
<div class="card mb-4" style="border-left:4px solid var(--cyan);">
    <div class="flex items-center gap-3">
        <span style="font-size:32px;">{{ $activosMenore->icono }}</span>
        <div>
            <div class="fw-600" style="font-size:16px;">{{ $activosMenore->nombre_completo }}</div>
            <div class="mono text-muted">{{ $activosMenore->numero_activo }}@if($activosMenore->serial) · {{ $activosMenore->serial }}@endif</div>
        </div>
    </div>
</div>
<div class="card">
<form action="{{ route('activos-menores.storeAsignacion',$activosMenore) }}" method="POST">
@csrf
<div class="form-grid">
    <div class="form-group span-2"><label class="form-label">Colaborador <span class="required">*</span></label><select name="colaborador_id" class="form-control" required><option value="">— Seleccione colaborador —</option>@foreach($colaboradores as $c)<option value="{{ $c->id }}" {{ old('colaborador_id')==$c->id?'selected':'' }}>{{ $c->nombre }} — {{ $c->departamento->nombre }}</option>@endforeach</select></div>
    <div class="form-group"><label class="form-label">Fecha de Asignación <span class="required">*</span></label><input type="date" name="fecha_asignacion" class="form-control" value="{{ old('fecha_asignacion',now()->toDateString()) }}" required></div>
    <div class="form-group"><label class="form-label">Condición al Entregar <span class="required">*</span></label><select name="condicion_entrega" class="form-control" required><option value="bueno">Bueno</option><option value="regular">Regular</option><option value="danado">Dañado</option></select></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea></div>
</div>
<div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-success">Confirmar Asignación</button>
    <a href="{{ route('activos-menores.show',$activosMenore) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
