@extends('layouts.app')
@section('title', $colaboradore->nombre)
@section('page-title', $colaboradore->nombre)
@section('topbar-actions')
<a href="{{ route('colaboradores.index') }}" class="btn btn-outline btn-sm">← Volver</a>
<a href="{{ route('colaboradores.edit', $colaboradore) }}" class="btn btn-outline btn-sm">Editar</a>
<a href="{{ route('export.pdf.colaborador', $colaboradore) }}" target="_blank" class="btn btn-outline btn-sm">📄 PDF Activos</a>
@endsection
@section('content')

{{-- Tarjeta del colaborador --}}
<div class="card mb-6">
    <div class="flex" style="justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="font-size:22px;font-weight:700;">{{ $colaboradore->nombre }}</div>
            <div class="mono text-muted" style="margin-top:4px;">{{ $colaboradore->correo }}</div>
            <div style="color:var(--text-muted);margin-top:4px;">
                {{ $colaboradore->cargo ?? '' }}
                @if($colaboradore->cargo && $colaboradore->departamento) · @endif
                @if($colaboradore->departamento)
                <a href="{{ route('departamentos.show', $colaboradore->departamento) }}" class="link">{{ $colaboradore->departamento->nombre }}</a>
                @endif
            </div>
            @if($colaboradore->telefono)
            <div class="mono text-muted" style="margin-top:4px;">📞 {{ $colaboradore->telefono }}</div>
            @endif
        </div>
        @if($colaboradore->estado==='activo')<span class="badge badge-green" style="font-size:13px;padding:4px 12px;">Activo</span>
        @else<span class="badge badge-gray" style="font-size:13px;padding:4px 12px;">Inactivo</span>@endif
    </div>

    {{-- Resumen de activos --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:14px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border);">
        @php
            $fijosActivos   = $colaboradore->asignacionesFijos->where('estado','activo')->count();
            $menoresActivos = $colaboradore->asignacionesMenores->where('estado','activo')->count();
            $totalHistorial = $colaboradore->asignacionesFijos->count() + $colaboradore->asignacionesMenores->count();
        @endphp
        <div style="text-align:center;"><div style="font-size:28px;font-weight:700;color:var(--blue);">{{ $fijosActivos + $menoresActivos }}</div><div class="text-muted">Activos hoy</div></div>
        <div style="text-align:center;"><div style="font-size:28px;font-weight:700;color:var(--purple);">{{ $fijosActivos }}</div><div class="text-muted">Activos Fijos</div></div>
        <div style="text-align:center;"><div style="font-size:28px;font-weight:700;color:var(--cyan);">{{ $menoresActivos }}</div><div class="text-muted">Activos Menores</div></div>
        <div style="text-align:center;"><div style="font-size:28px;font-weight:700;color:var(--text-muted);">{{ $totalHistorial }}</div><div class="text-muted">Total historial</div></div>
    </div>
</div>

{{-- Activos Fijos asignados --}}
<div class="card mb-6">
    <div class="card-header">
        <div>
            <div class="card-title">Activos Fijos Asignados</div>
            <div class="card-subtitle">Laptops, monitores, celulares, servidores, etc.</div>
        </div>
        <a href="{{ route('activos-fijos.index', ['estado'=>'disponible']) }}" class="btn btn-outline btn-sm">Ver disponibles</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th># Activo</th><th>Equipo</th><th>Serial</th><th>Condición</th><th>Desde</th><th>Devuelto</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($colaboradore->asignacionesFijos as $a)
            <tr>
                <td class="mono fw-600">{{ $a->activoFijo->numero_activo }}</td>
                <td>
                    <a href="{{ route('activos-fijos.show', $a->activoFijo) }}" class="link">
                        {{ $a->activoFijo->icono }} {{ $a->activoFijo->nombre_completo }}
                    </a>
                    <div class="text-muted">{{ $a->activoFijo->tipo_label }}</div>
                </td>
                <td class="mono" style="font-size:12px;">{{ $a->activoFijo->serial ?? '—' }}</td>
                <td>@if($a->condicion_entrega==='bueno')<span class="badge badge-green">Bueno</span>@elseif($a->condicion_entrega==='regular')<span class="badge badge-amber">Regular</span>@else<span class="badge badge-red">Dañado</span>@endif</td>
                <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
                <td class="mono">{{ $a->fecha_devolucion_real?->format('d/m/Y') ?? '—' }}</td>
                <td>@if($a->estado==='activo')<span class="badge badge-purple">Activo</span>@else<span class="badge badge-gray">Devuelto</span>@endif</td>
                <td><a href="{{ route('activos-fijos.show', $a->activoFijo) }}" class="btn btn-outline btn-sm">Ver equipo</a></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted" style="padding:24px">Sin activos fijos asignados</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Activos Menores asignados --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Activos Menores Asignados</div>
            <div class="card-subtitle">Mouse, teclados, memorias, accesorios</div>
        </div>
        <a href="{{ route('activos-menores.index', ['estado'=>'disponible']) }}" class="btn btn-outline btn-sm">Ver disponibles</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th># Activo</th><th>Accesorio</th><th>Serial</th><th>Desde</th><th>Devuelto</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($colaboradore->asignacionesMenores as $a)
            <tr>
                <td class="mono fw-600">{{ $a->activoMenor->numero_activo }}</td>
                <td>
                    <a href="{{ route('activos-menores.show', $a->activoMenor) }}" class="link">
                        {{ $a->activoMenor->icono }} {{ $a->activoMenor->nombre_completo }}
                    </a>
                    <div class="text-muted">{{ $a->activoMenor->tipo_label }}</div>
                </td>
                <td class="mono" style="font-size:12px;">{{ $a->activoMenor->serial ?? '—' }}</td>
                <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
                <td class="mono">{{ $a->fecha_devolucion_real?->format('d/m/Y') ?? '—' }}</td>
                <td>@if($a->estado==='activo')<span class="badge badge-purple">Activo</span>@else<span class="badge badge-gray">Devuelto</span>@endif</td>
                <td><a href="{{ route('activos-menores.show', $a->activoMenor) }}" class="btn btn-outline btn-sm">Ver accesorio</a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted" style="padding:24px">Sin activos menores asignados</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
