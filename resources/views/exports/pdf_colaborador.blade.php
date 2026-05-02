@include('exports._header')

<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">
    <button onclick="window.print()"
        style="background:#7c3aed;color:#fff;border:none;padding:9px 20px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
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
        <div class="report-title">Activos Asignados</div>
        <div class="report-date">Generado el {{ $fecha }}</div>
    </div>
</div>

{{-- Ficha del colaborador --}}
<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px 20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <div style="font-size:18px;font-weight:700;color:#0f172a;">{{ $colaboradore->nombre }}</div>
        <div style="font-size:12px;color:#64748b;margin-top:3px;">{{ $colaboradore->cargo ?? '' }} · {{ $colaboradore->departamento?->nombre ?? '' }}</div>
        <div style="font-size:11px;color:#94a3b8;margin-top:2px;font-family:monospace;">{{ $colaboradore->correo }}</div>
        @if($colaboradore->telefono)<div style="font-size:11px;color:#94a3b8;font-family:monospace;">📞 {{ $colaboradore->telefono }}</div>@endif
    </div>
    <div style="text-align:right;">
        <div style="font-size:24px;font-weight:700;color:#7c3aed;">{{ $colaboradore->asignacionesFijos->count() + $colaboradore->asignacionesMenores->count() }}</div>
        <div style="font-size:10px;color:#64748b;text-transform:uppercase;letter-spacing:.4px;">Activos asignados</div>
    </div>
</div>

{{-- Activos Fijos --}}
@if($colaboradore->asignacionesFijos->count())
<div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
    Activos Fijos ({{ $colaboradore->asignacionesFijos->count() }})
</div>
<table style="margin-bottom:20px;">
    <thead>
        <tr>
            <th># Activo</th>
            <th>Tipo</th>
            <th>Equipo</th>
            <th>Serial</th>
            <th>Condición</th>
            <th>Desde</th>
        </tr>
    </thead>
    <tbody>
    @foreach($colaboradore->asignacionesFijos as $a)
    <tr>
        <td class="mono fw">{{ $a->activoFijo->numero_activo }}</td>
        <td>{{ $a->activoFijo->icono }} {{ $a->activoFijo->tipo_label }}</td>
        <td class="fw">{{ $a->activoFijo->nombre_completo }}</td>
        <td class="mono">{{ $a->activoFijo->serial ?? '—' }}</td>
        <td>
            @if($a->activoFijo->condicion==='bueno')<span class="badge badge-green">Bueno</span>
            @elseif($a->activoFijo->condicion==='regular')<span class="badge badge-amber">Regular</span>
            @else<span class="badge badge-red">Dañado</span>@endif
        </td>
        <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif

{{-- Activos Menores --}}
@if($colaboradore->asignacionesMenores->count())
<div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
    Activos Menores ({{ $colaboradore->asignacionesMenores->count() }})
</div>
<table>
    <thead>
        <tr>
            <th># Activo</th>
            <th>Tipo</th>
            <th>Accesorio</th>
            <th>Serial</th>
            <th>Condición</th>
            <th>Desde</th>
        </tr>
    </thead>
    <tbody>
    @foreach($colaboradore->asignacionesMenores as $a)
    <tr>
        <td class="mono fw">{{ $a->activoMenor->numero_activo }}</td>
        <td>{{ $a->activoMenor->icono }} {{ $a->activoMenor->tipo_label }}</td>
        <td class="fw">{{ $a->activoMenor->nombre_completo }}</td>
        <td class="mono">{{ $a->activoMenor->serial ?? '—' }}</td>
        <td>
            @if($a->activoMenor->condicion==='bueno')<span class="badge badge-green">Bueno</span>
            @elseif($a->activoMenor->condicion==='regular')<span class="badge badge-amber">Regular</span>
            @else<span class="badge badge-red">Dañado</span>@endif
        </td>
        <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif

@if(!$colaboradore->asignacionesFijos->count() && !$colaboradore->asignacionesMenores->count())
<div style="text-align:center;padding:40px;color:#94a3b8;">
    Este colaborador no tiene activos asignados actualmente.
</div>
@endif

<div class="footer">
    <span>Control de Activos · Inventario Empresarial</span>
    <span>{{ $fecha }}</span>
</div>
</body>
</html>
