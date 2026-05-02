@extends('layouts.app')
@section('title','Enviar a Mantenimiento')
@section('page-title','Enviar a Mantenimiento')
@section('topbar-actions')
<a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection
@section('content')
<div style="max-width:600px;">
<div class="card mb-4" style="border-left:4px solid var(--amber);">
    <div class="flex items-center gap-3">
        <span style="font-size:32px;">{{ $activosFijo->icono }}</span>
        <div><div class="fw-600" style="font-size:16px;">{{ $activosFijo->nombre_completo }}</div><div class="mono text-muted">{{ $activosFijo->numero_activo }}</div></div>
    </div>
</div>
<div class="card">
<form action="{{ route('activos-fijos.mantenimiento.store',$activosFijo) }}" method="POST">
@csrf
<div class="form-grid">
    <div class="form-group"><label class="form-label">Fecha de Entrada <span class="required">*</span></label><input type="date" name="fecha_entrada" class="form-control" value="{{ now()->toDateString() }}" required></div>
    <div class="form-group"><label class="form-label">Tipo <span class="required">*</span></label><select name="tipo" class="form-control" required><option value="correctivo">Correctivo</option><option value="preventivo">Preventivo</option><option value="garantia">Garantía</option></select></div>
    <div class="form-group span-2"><label class="form-label">Técnico / Proveedor</label><input type="text" name="tecnico_proveedor" class="form-control" placeholder="Nombre del técnico o empresa"></div>
    <div class="form-group span-2"><label class="form-label">Descripción del Problema</label><textarea name="descripcion_problema" class="form-control" rows="3" placeholder="¿Cuál es el problema?">{{ old('descripcion_problema') }}</textarea></div>
    <div class="form-group span-2"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea></div>
</div>
<div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary" style="background:var(--amber);border-color:var(--amber);">Enviar a Mantenimiento</button>
    <a href="{{ route('activos-fijos.show',$activosFijo) }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
