@include('exports._header')

<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">
    <button onclick="window.print()"
        style="background:#d97706;color:#fff;border:none;padding:9px 20px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
        🖨️ Imprimir / Guardar PDF
    </button>
    <button onclick="window.close()"
        style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;padding:9px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
        ✕ Cerrar
    </button>
</div>

<div class="header">
    <div class="header-logo">
        <div class="ms-grid">
            <span class="ms-blue"></span><span class="ms-green"></span>
            <span class="ms-yellow"></span><span class="ms-red"></span>
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

@php
    $enProceso  = $mantenimientos->where('estado','en_proceso')->count();
    $completado = $mantenimientos->where('estado','completado')->count();
    $totalCosto = $mantenimientos->whereNotNull('costo')->sum('costo');
@endphp
<div class="stats-row">
    <div class="stat-box">       <div class="num">{{ $mantenimientos->count() }}</div><div class="lbl">Total</div></div>
    <div class="stat-box amber"> <div class="num">{{ $enProceso }}</div><div class="lbl">En Proceso</div></div>
    <div class="stat-box green"> <div class="num">{{ $completado }}</div><div class="lbl">Completados</div></div>
    <div class="stat-box">       <div class="num">${{ number_format($totalCosto, 0, '.', ',') }}</div><div class="lbl">Costo Total</div></div>
</div>

<table>
    <thead>
        <tr>
            <th>Activo</th>
            <th>Tipo</th>
            <th>Técnico</th>
            <th>Problema</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Días</th>
            <th>Costo</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
    @forelse($mantenimientos as $m)
    <tr>
        <td>
            <span class="fw mono">{{ $m->activoFijo->numero_activo }}</span>
            <div style="font-size:10px;color:#64748b;">{{ $m->activoFijo->nombre_completo }}</div>
        </td>
        <td>{{ $m->tipo_label }}</td>
        <td>{{ $m->tecnico_proveedor ?? '—' }}</td>
        <td style="max-width:160px;font-size:10.5px;">{{ Str::limit($m->descripcion_problema ?? '—', 60) }}</td>
        <td class="mono">{{ $m->fecha_entrada->format('d/m/Y') }}</td>
        <td class="mono">{{ $m->fecha_salida?->format('d/m/Y') ?? '—' }}</td>
        <td class="mono">{{ $m->dias }}d</td>
        <td class="mono">${{ $m->costo ? number_format($m->costo, 2) : '—' }}</td>
        <td>
            @if($m->estado==='en_proceso')<span class="badge badge-amber">En Proceso</span>
            @elseif($m->estado==='completado')<span class="badge badge-green">Completado</span>
            @else<span class="badge badge-gray">Cancelado</span>@endif
        </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:24px;color:#94a3b8;">Sin mantenimientos registrados</td></tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <span>Control de Activos · Inventario Empresarial</span>
    <span>{{ $mantenimientos->count() }} mantenimiento(s) · {{ $fecha }}</span>
</div>
</body>
</html>
