@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('topbar-actions')
<a href="{{ route('export.pdf.mantenimientos') }}" target="_blank" class="btn btn-outline btn-sm">📄 Mantenimientos</a>
<a href="{{ route('activos-fijos.create') }}" class="btn btn-outline btn-sm">+ Activo Fijo</a>
<a href="{{ route('activos-menores.create') }}" class="btn btn-outline btn-sm">+ Activo Menor</a>
<a href="{{ route('colaboradores.create') }}" class="btn btn-primary btn-sm">+ Colaborador</a>
@endsection

@section('content')

{{-- PANEL DE ALERTAS (solo admin) --}}
@if(auth()->user()->esAdministrador() && count($alertas) > 0)
<div style="margin-bottom:28px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Panel de Alertas</div>
        <span style="background:var(--red);color:#fff;border-radius:20px;padding:2px 8px;font-size:11px;font-weight:700;">{{ count($alertas) }}</span>
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($alertas as $alerta)
        @php
            $estilos = match($alerta['tipo']) {
                'danger'  => ['bg'=>'#fee2e2','border'=>'#dc2626','text'=>'#7f1d1d','badgebg'=>'#dc2626'],
                'warning' => ['bg'=>'#fef3c7','border'=>'#d97706','text'=>'#92400e','badgebg'=>'#d97706'],
                default   => ['bg'=>'#dbeafe','border'=>'#2563eb','text'=>'#1e40af','badgebg'=>'#2563eb'],
            };
        @endphp
        <div style="background:{{ $estilos['bg'] }};border:1px solid {{ $estilos['border'] }};border-left:4px solid {{ $estilos['border'] }};border-radius:8px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div style="display:flex;align-items:flex-start;gap:12px;flex:1;">
                <span style="font-size:20px;line-height:1;margin-top:1px;">{{ $alerta['icono'] }}</span>
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:{{ $estilos['text'] }};">{{ $alerta['titulo'] }}</div>
                    <div style="font-size:12px;color:{{ $estilos['text'] }};opacity:.8;margin-top:3px;">{{ $alerta['detalle'] }}</div>
                </div>
            </div>
            <a href="{{ $alerta['accion'] }}"
               style="background:{{ $estilos['badgebg'] }};color:#fff;text-decoration:none;padding:6px 14px;border-radius:6px;font-size:12.5px;font-weight:500;white-space:nowrap;flex-shrink:0;">
                {{ $alerta['accion_label'] }} →
            </a>
        </div>
        @endforeach
    </div>
</div>
@elseif(auth()->user()->esAdministrador())
<div style="margin-bottom:28px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Panel de Alertas</div>
    </div>
    <div style="background:#dcfce7;border:1px solid #16a34a;border-left:4px solid #16a34a;border-radius:8px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
        <span style="font-size:20px;">✅</span>
        <div style="font-size:13.5px;font-weight:600;color:#14532d;">Todo en orden — No hay alertas activas</div>
    </div>
</div>
@endif

{{-- ACTIVOS FIJOS --}}
<div class="section-title">Activos Fijos</div>
<div class="stats-grid mb-6">
    <a href="{{ route('activos-fijos.index') }}" class="stat-card">
        <div class="stat-label">Total</div>
        <div class="stat-value">{{ $fijos['total'] }}</div>
        <div class="stat-sub">En inventario</div>
    </a>
    <a href="{{ route('activos-fijos.index', ['estado'=>'disponible']) }}" class="stat-card green">
        <div class="stat-label">Disponibles</div>
        <div class="stat-value">{{ $fijos['disponibles'] }}</div>
        <div class="stat-sub">Listos para asignar</div>
    </a>
    <a href="{{ route('activos-fijos.index', ['estado'=>'asignado']) }}" class="stat-card purple">
        <div class="stat-label">Asignados</div>
        <div class="stat-value">{{ $fijos['asignados'] }}</div>
        <div class="stat-sub">En uso</div>
    </a>
    <a href="{{ route('activos-fijos.index', ['estado'=>'mantenimiento']) }}" class="stat-card amber">
        <div class="stat-label">Mantenimiento</div>
        <div class="stat-value">{{ $fijos['mantenimiento'] }}</div>
        <div class="stat-sub">En reparación</div>
    </a>
    <a href="{{ route('activos-fijos.index', ['estado'=>'bodega']) }}" class="stat-card">
        <div class="stat-label">En Bodega</div>
        <div class="stat-value">{{ $fijos['bodega'] }}</div>
    </a>
    <a href="{{ route('activos-fijos.index', ['estado'=>'baja']) }}" class="stat-card red">
        <div class="stat-label">Dados de Baja</div>
        <div class="stat-value">{{ $fijos['baja'] }}</div>
    </a>
</div>

{{-- ACTIVOS MENORES --}}
<div class="section-title">Activos Menores</div>
<div class="stats-grid mb-6">
    <a href="{{ route('activos-menores.index') }}" class="stat-card">
        <div class="stat-label">Total</div>
        <div class="stat-value">{{ $menores['total'] }}</div>
        <div class="stat-sub">Mouse, teclados, etc.</div>
    </a>
    <a href="{{ route('activos-menores.index', ['estado'=>'disponible']) }}" class="stat-card green">
        <div class="stat-label">Disponibles</div>
        <div class="stat-value">{{ $menores['disponibles'] }}</div>
    </a>
    <a href="{{ route('activos-menores.index', ['estado'=>'asignado']) }}" class="stat-card purple">
        <div class="stat-label">Asignados</div>
        <div class="stat-value">{{ $menores['asignados'] }}</div>
    </a>
    <a href="{{ route('activos-menores.index', ['estado'=>'baja']) }}" class="stat-card red">
        <div class="stat-label">Dados de Baja</div>
        <div class="stat-value">{{ $menores['baja'] }}</div>
    </a>
</div>

{{-- ORGANIZACIÓN --}}
<div class="section-title">Organización</div>
<div class="stats-grid mb-6">
    <a href="{{ route('colaboradores.index') }}" class="stat-card blue">
        <div class="stat-label">Colaboradores Activos</div>
        <div class="stat-value">{{ $colaboradores['activos'] }}</div>
        <div class="stat-sub">{{ $colaboradores['total'] }} en total</div>
    </a>
    <a href="{{ route('departamentos.index') }}" class="stat-card cyan">
        <div class="stat-label">Departamentos</div>
        <div class="stat-value">{{ $totalDepartamentos }}</div>
    </a>
</div>

<div class="grid-2">
    {{-- Últimas asignaciones --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Últimas Asignaciones Activas</div>
            <a href="{{ route('activos-fijos.index', ['estado'=>'asignado']) }}" class="btn btn-outline btn-sm">Ver todas</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Activo</th><th>Colaborador</th><th>Desde</th></tr></thead>
                <tbody>
                @forelse($ultimasAsignaciones as $a)
                <tr>
                    <td>
                        <a href="{{ route('activos-fijos.show', $a->activoFijo) }}" class="link">
                            {{ $a->activoFijo->icono }} {{ $a->activoFijo->nombre_completo }}
                        </a>
                        <div class="text-muted">{{ $a->activoFijo->numero_activo }}</div>
                    </td>
                    <td>
                        <a href="{{ route('colaboradores.show', $a->colaborador) }}" class="link">
                            {{ $a->colaborador->nombre }}
                        </a>
                        <div class="text-muted">{{ $a->colaborador->departamento->nombre ?? '' }}</div>
                    </td>
                    <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted" style="padding:24px">Sin asignaciones activas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mantenimientos en proceso --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Equipos en Mantenimiento</div>
        </div>
        @forelse($mantenimientosActivos as $m)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
            <div>
                <a href="{{ route('activos-fijos.show', $m->activoFijo) }}" class="link">
                    {{ $m->activoFijo->icono }} {{ $m->activoFijo->nombre_completo }}
                </a>
                <div class="text-muted">{{ $m->tipo_label }} · {{ $m->dias }} días</div>
            </div>
            <span class="badge badge-amber">En Proceso</span>
        </div>
        @empty
        <div class="empty-state" style="padding:32px 20px;">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p>Ningún equipo en mantenimiento</p>
        </div>
        @endforelse
    </div>
</div>

@if($activosPorTipo->count())
<div class="card" style="margin-top:20px;">
    <div class="card-title" style="margin-bottom:16px;">Activos Fijos por Tipo</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        @foreach($activosPorTipo as $t)
        <a href="{{ route('activos-fijos.index', ['tipo'=>$t->tipo]) }}"
           style="display:flex;align-items:center;gap:8px;padding:8px 14px;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);text-decoration:none;color:var(--text);transition:background .15s;"
           onmouseover="this.style.background='var(--blue-light)'" onmouseout="this.style.background='var(--surface-2)'">
            <span style="font-size:18px;">{{ \App\Models\ActivoFijo::$tipoIconos[$t->tipo] ?? '📦' }}</span>
            <div>
                <div style="font-size:13px;font-weight:600;">{{ \App\Models\ActivoFijo::$tipos[$t->tipo] ?? $t->tipo }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $t->total }} equipo(s)</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif


{{-- GRÁFICAS --}}
<div class="grid-2" style="margin-top:20px;">
    {{-- Gráfica: Activos Fijos por Estado --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:16px;">Activos Fijos por Estado</div>
        <canvas id="chart-estados" height="200"></canvas>
    </div>

    {{-- Gráfica: Activos Fijos por Tipo --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:16px;">Activos Fijos por Tipo</div>
        <canvas id="chart-tipos" height="200"></canvas>
    </div>
</div>

<div class="grid-2" style="margin-top:20px;">
    {{-- Gráfica: Activos Menores por Estado --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:16px;">Activos Menores por Estado</div>
        <canvas id="chart-menores" height="200"></canvas>
    </div>

    {{-- Resumen general --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:16px;">Resumen General</div>
        <canvas id="chart-resumen" height="200"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'DM Sans', system-ui, sans-serif";
Chart.defaults.color = '#64748b';

// Gráfica 1: Activos Fijos por Estado
new Chart(document.getElementById('chart-estados'), {
    type: 'doughnut',
    data: {
        labels: ['Disponible', 'Asignado', 'Mantenimiento', 'Baja', 'Bodega'],
        datasets: [{
            data: [{{ $fijos['disponibles'] }}, {{ $fijos['asignados'] }}, {{ $fijos['mantenimiento'] }}, {{ $fijos['baja'] }}, {{ $fijos['bodega'] }}],
            backgroundColor: ['#16a34a','#7c3aed','#d97706','#dc2626','#94a3b8'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
            tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.raw } }
        },
        cutout: '65%',
    }
});

// Gráfica 2: Activos Fijos por Tipo
new Chart(document.getElementById('chart-tipos'), {
    type: 'bar',
    data: {
        labels: [
            @foreach($activosPorTipo as $t)
            '{{ \App\Models\ActivoFijo::$tipos[$t->tipo] ?? $t->tipo }}',
            @endforeach
        ],
        datasets: [{
            label: 'Cantidad',
            data: [
                @foreach($activosPorTipo as $t)
                {{ $t->total }},
                @endforeach
            ],
            backgroundColor: '#2563eb',
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Gráfica 3: Activos Menores por Estado
new Chart(document.getElementById('chart-menores'), {
    type: 'doughnut',
    data: {
        labels: ['Disponible', 'Asignado', 'Baja', 'Bodega'],
        datasets: [{
            data: [{{ $menores['disponibles'] }}, {{ $menores['asignados'] }}, {{ $menores['baja'] }}, {{ $menores['total'] - $menores['disponibles'] - $menores['asignados'] - $menores['baja'] }}],
            backgroundColor: ['#16a34a','#7c3aed','#dc2626','#94a3b8'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
        },
        cutout: '65%',
    }
});

// Gráfica 4: Resumen general
new Chart(document.getElementById('chart-resumen'), {
    type: 'bar',
    data: {
        labels: ['Activos Fijos', 'Activos Menores', 'Colaboradores', 'Departamentos'],
        datasets: [{
            label: 'Total',
            data: [{{ $fijos['total'] }}, {{ $menores['total'] }}, {{ $colaboradores['total'] }}, {{ $totalDepartamentos }}],
            backgroundColor: ['#2563eb','#0891b2','#7c3aed','#16a34a'],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

@endsection