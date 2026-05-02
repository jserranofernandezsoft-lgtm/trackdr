@extends('layouts.app')
@section('title',$departamento->nombre)
@section('page-title',$departamento->nombre)
@section('topbar-actions')
<a href="{{ route('departamentos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
<a href="{{ route('departamentos.edit',$departamento) }}" class="btn btn-outline btn-sm">Editar</a>
@endsection
@section('content')
<div class="card mb-6">
    <div class="flex" style="justify-content:space-between;align-items:flex-start;">
        <div>
            <div style="font-size:20px;font-weight:700;">{{ $departamento->nombre }}</div>
            <span class="badge badge-gray mono" style="margin-top:6px;">{{ $departamento->codigo }}</span>
            @if($departamento->descripcion)<p class="text-muted" style="margin-top:8px;">{{ $departamento->descripcion }}</p>@endif
        </div>
        @if($departamento->estado==='activo')<span class="badge badge-green">Activo</span>@else<span class="badge badge-gray">Inactivo</span>@endif
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:14px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
        <div style="text-align:center;"><div style="font-size:26px;font-weight:700;color:var(--blue);">{{ $departamento->colaboradoresActivos->count() }}</div><div class="text-muted">Colaboradores</div></div>
        <div style="text-align:center;"><div style="font-size:26px;font-weight:700;color:var(--purple);">{{ $departamento->activosFijos->count() }}</div><div class="text-muted">Activos Fijos</div></div>
        <div style="text-align:center;"><div style="font-size:26px;font-weight:700;color:var(--cyan);">{{ $departamento->activosMenores->count() }}</div><div class="text-muted">Activos Menores</div></div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header"><div class="card-title">Colaboradores</div><a href="{{ route('colaboradores.create') }}" class="btn btn-outline btn-sm">+ Nuevo</a></div>
        <div class="table-wrap"><table><thead><tr><th>Nombre</th><th>Cargo</th><th>Estado</th></tr></thead><tbody>
        @forelse($departamento->colaboradoresActivos as $c)
        <tr><td><a href="{{ route('colaboradores.show',$c) }}" class="link">{{ $c->nombre }}</a></td><td class="text-muted">{{ $c->cargo ?? '—' }}</td><td><span class="badge badge-green">Activo</span></td></tr>
        @empty<tr><td colspan="3" class="text-center text-muted" style="padding:20px">Sin colaboradores</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title">Activos Fijos</div><a href="{{ route('activos-fijos.index') }}" class="btn btn-outline btn-sm">Ver todos</a></div>
        <div class="table-wrap"><table><thead><tr><th>Activo</th><th>Estado</th><th>Asignado a</th></tr></thead><tbody>
        @forelse($departamento->activosFijos->take(8) as $a)
        <tr><td><a href="{{ route('activos-fijos.show',$a) }}" class="link">{{ $a->icono }} {{ $a->nombre_completo }}</a><div class="mono text-muted">{{ $a->numero_activo }}</div></td><td><span class="badge {{ \App\Models\ActivoFijo::$estadoBadge[$a->estado]??'badge-gray' }}">{{ $a->estado_label }}</span></td><td>{{ $a->asignacionActiva?->colaborador->nombre ?? '—' }}</td></tr>
        @empty<tr><td colspan="3" class="text-center text-muted" style="padding:20px">Sin activos fijos</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
