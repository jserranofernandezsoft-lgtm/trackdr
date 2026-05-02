@extends('layouts.app')
@section('title','Nuevo Activo Fijo')
@section('page-title','Nuevo Activo Fijo')
@section('topbar-actions')
<a href="{{ route('activos-fijos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
@endsection

@section('content')
<div style="max-width:780px;">
<div class="card">
<form action="{{ route('activos-fijos.store') }}" method="POST">
@csrf

<div class="section-title">Identificación</div>
<div class="form-grid-3 mb-6">
    <div class="form-group">
        <label class="form-label">Número de Activo <span class="required">*</span></label>
        <input type="text" name="numero_activo" class="form-control" value="{{ old('numero_activo', $numeroActivo) }}" required>
        <span class="form-hint">Auto-generado, puedes cambiarlo</span>
    </div>
    <div class="form-group">
        <label class="form-label">Tipo <span class="required">*</span></label>
        <select name="tipo" class="form-control" required>
            <option value="">— Seleccione —</option>
            @foreach(\App\Models\ActivoFijo::$tipos as $v=>$l)
            <option value="{{ $v }}" {{ old('tipo')===$v?'selected':'' }}>{{ \App\Models\ActivoFijo::$tipoIconos[$v] ?? '' }} {{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Condición <span class="required">*</span></label>
        <select name="condicion" class="form-control" required>
            @foreach(\App\Models\ActivoFijo::$condiciones as $v=>$l)
            <option value="{{ $v }}" {{ old('condicion','bueno')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Marca <span class="required">*</span></label>
        <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Modelo <span class="required">*</span></label>
        <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Número de Serial</label>
        <input type="text" name="serial" class="form-control" value="{{ old('serial') }}">
    </div>
</div>

<div class="section-title">Ubicación y Responsable</div>
<div class="form-grid mb-6">
    <div class="form-group">
        <label class="form-label">Departamento Responsable</label>
        <select name="departamento_id" class="form-control">
            <option value="">— General empresa —</option>
            @foreach($departamentos as $dep)
            <option value="{{ $dep->id }}" {{ old('departamento_id')==$dep->id?'selected':'' }}>{{ $dep->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Ubicación Física</label>
        <input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion') }}" placeholder="Oficina, piso, sede...">
    </div>
    <div class="form-group">
        <label class="form-label">Estado Inicial</label>
        <select name="estado" class="form-control">
            <option value="disponible" {{ old('estado','disponible')==='disponible'?'selected':'' }}>Disponible</option>
            <option value="bodega" {{ old('estado')==='bodega'?'selected':'' }}>En Bodega</option>
            <option value="baja" {{ old('estado')==='baja'?'selected':'' }}>Dado de Baja</option>
        </select>
    </div>
</div>

<div class="section-title">Adquisición</div>
<div class="form-grid mb-6">
    <div class="form-group">
        <label class="form-label">Fecha de Adquisición</label>
        <input type="date" name="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Valor de Adquisición</label>
        <input type="number" name="valor_adquisicion" class="form-control" value="{{ old('valor_adquisicion') }}" min="0" step="0.01" placeholder="0.00">
    </div>
    <div class="form-group">
        <label class="form-label">Proveedor</label>
        <input type="text" name="proveedor" class="form-control" value="{{ old('proveedor') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Descripción</label>
        <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion') }}" placeholder="Descripción breve">
    </div>
    <div class="form-group span-2">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
    </div>
</div>

<div style="padding-top:18px;border-top:1px solid var(--border);display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">Registrar Activo</button>
    <a href="{{ route('activos-fijos.index') }}" class="btn btn-outline">Cancelar</a>
</div>
</form>
</div>
</div>
@endsection
