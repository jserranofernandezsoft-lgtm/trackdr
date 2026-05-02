@include('exports._header')

{{-- Botón imprimir --}}
<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">
    <button onclick="window.print()"
        style="background:#2563eb;color:#fff;border:none;padding:9px 20px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
        🖨️ Imprimir / Guardar PDF
    </button>
    <button onclick="window.close()"
        style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;padding:9px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
        ✕ Cerrar
    </button>
    <span style="font-size:12px;color:#94a3b8;margin-left:6px;">Para guardar como PDF → selecciona "Guardar como PDF" en la impresora</span>
</div>

{{-- Header del reporte --}}
<div class="header">
    <div class="header-logo">
        <div class="ms-grid">
            <span class="ms-blue"></span>
            <span class="ms-green"></span>
            <span class="ms-yellow"></span>
            <span class="ms-red"></span>
        </div>
        <div>
            <div class="brand-name">Control de Activos</div>
            <div class="brand-sub">Inventario Empresarial</div>
        </div>
    </div>
    <div class="header-info">
        <div class="report-title">{{ $titulo }}</div>
        <div class="report-date">Generado el {{ $fecha }}</div>
    </div>
</div>

{{-- Filtros activos --}}
@if(array_filter($filtros))
<div class="filtros-bar">
    <span>Filtros aplicados:</span>
    @if(!empty($filtros['estado']))<span><strong>Estado:</strong> {{ \App\Models\ActivoFijo::$estados[$filtros['estado']] ?? $filtros['estado'] }}</span>@endif
    @if(!empty($filtros['tipo']))<span><strong>Tipo:</strong> {{ \App\Models\ActivoFijo::$tipos[$filtros['tipo']] ?? $filtros['tipo'] }}</span>@endif
</div>
@endif

{{-- Stats --}}
@php
    $total    = $activos->count();
    $disp     = $activos->where('estado','disponible')->count();
    $asign    = $activos->where('estado','asignado')->count();
    $mant     = $activos->where('estado','mantenimiento')->count();
    $baja     = $activos->whereIn('estado',['baja','robado_perdido'])->count();
@endphp
<div class="stats-row">
    <div class="stat-box">       <div class="num">{{ $total }}</div><div class="lbl">Total</div></div>
    <div class="stat-box green"> <div class="num">{{ $disp }}</div> <div class="lbl">Disponibles</div></div>
    <div class="stat-box purple"><div class="num">{{ $asign }}</div><div class="lbl">Asignados</div></div>
    <div class="stat-box amber"> <div class="num">{{ $mant }}</div> <div class="lbl">Mantenimiento</div></div>
    <div class="stat-box red">   <div class="num">{{ $baja }}</div> <div class="lbl">Baja / Perdido</div></div>
</div>

{{-- Tabla --}}
<table>
    <thead>
        <tr>
            <th># Activo</th>
            <th>Tipo</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Serial</th>
            <th>Condición</th>
            <th>Estado</th>
            <th>Departamento</th>
            <th>Asignado a</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
    @forelse($activos as $a)
    <tr>
        <td class="mono fw">{{ $a->numero_activo }}</td>
        <td>{{ $a->icono }} {{ $a->tipo_label }}</td>
        <td class="fw">{{ $a->marca }}</td>
        <td>{{ $a->modelo }}</td>
        <td class="mono">{{ $a->serial ?? '—' }}</td>
        <td>
            @if($a->condicion==='bueno')<span class="badge badge-green">Bueno</span>
            @elseif($a->condicion==='regular')<span class="badge badge-amber">Regular</span>
            @else<span class="badge badge-red">Dañado</span>@endif
        </td>
        <td>
            @php $badge = ['disponible'=>'badge-green','asignado'=>'badge-purple','mantenimiento'=>'badge-amber','baja'=>'badge-red','robado_perdido'=>'badge-red','bodega'=>'badge-gray'][$a->estado] ?? 'badge-gray' @endphp
            <span class="badge {{ $badge }}">{{ $a->estado_label }}</span>
        </td>
        <td>{{ $a->departamento?->nombre ?? 'General' }}</td>
        <td>{{ $a->asignacionActiva?->colaborador->nombre ?? '—' }}</td>
        <td class="mono">${{ $a->valor_adquisicion ? number_format($a->valor_adquisicion, 2) : '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="10" style="text-align:center;padding:24px;color:#94a3b8;">No hay activos registrados</td></tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <span>Control de Activos · Inventario Empresarial</span>
    <span>Total: {{ $activos->count() }} activo(s) · {{ $fecha }}</span>
</div>

</body>
</html>
