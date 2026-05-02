@extends('layouts.app')
@section('title','Activos Fijos')
@section('page-title','Activos Fijos')

@section('topbar-actions')
<a href="{{ route('export.pdf.activos-fijos', request()->query()) }}" target="_blank" class="btn btn-outline btn-sm">📄 PDF</a>
<a href="{{ route('export.excel.activos-fijos', request()->query()) }}" class="btn btn-outline btn-sm">📊 Excel</a>
@if(auth()->user()->esAdministrador())
<a href="{{ route('activos-fijos.create') }}" class="btn btn-primary btn-sm">
    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
    Nuevo Activo
</a>
@endif
@endsection

@section('content')
<div class="stats-grid mb-6">
    <div class="stat-card"><div class="stat-label">Total</div><div class="stat-value">{{ $totales['total'] }}</div></div>
    <div class="stat-card green"><div class="stat-label">Disponibles</div><div class="stat-value">{{ $totales['disponibles'] }}</div></div>
    <div class="stat-card purple"><div class="stat-label">Asignados</div><div class="stat-value">{{ $totales['asignados'] }}</div></div>
    <div class="stat-card amber"><div class="stat-label">Mantenimiento</div><div class="stat-value">{{ $totales['mantenimiento'] }}</div></div>
</div>

<div class="card mb-4">
    <form method="GET" class="flex gap-3" style="align-items:flex-end;flex-wrap:wrap;">
        <div class="form-group" style="flex:2;min-width:180px;">
            <label class="form-label">Buscar</label>
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="# activo, marca, modelo, serial...">
        </div>
        <div class="form-group" style="flex:1;min-width:140px;">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-control">
                <option value="">Todos</option>
                @foreach(\App\Models\ActivoFijo::$tipos as $v=>$l)
                <option value="{{ $v }}" {{ request('tipo')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex:1;min-width:140px;">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                @foreach(\App\Models\ActivoFijo::$estados as $v=>$l)
                <option value="{{ $v }}" {{ request('estado')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-outline">Filtrar</button>
        <a href="{{ route('activos-fijos.index') }}" class="btn btn-outline">Limpiar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th># Activo</th>
                    <th>Tipo</th>
                    <th>Marca / Modelo</th>
                    <th>Serial</th>
                    <th>Condición</th>
                    <th>Estado</th>
                    <th>Asignado a</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($activos as $a)
            <tr>
                <td class="mono fw-600">{{ $a->numero_activo }}</td>
                <td>{{ $a->icono }} {{ $a->tipo_label }}</td>
                <td>
                    <div class="fw-600">{{ $a->marca }}</div>
                    <div class="text-muted">{{ $a->modelo }}</div>
                </td>
                <td class="mono" style="font-size:12px;">{{ $a->serial ?? '—' }}</td>
                <td>
                    @if($a->condicion==='bueno')<span class="badge badge-green">Bueno</span>
                    @elseif($a->condicion==='regular')<span class="badge badge-amber">Regular</span>
                    @else<span class="badge badge-red">Dañado</span>@endif
                </td>
                <td>
                    <span class="badge {{ \App\Models\ActivoFijo::$estadoBadge[$a->estado]??'badge-gray' }}">
                        {{ $a->estado_label }}
                    </span>
                </td>
                <td>
                    @if($a->asignacionActiva)
                        <a href="{{ route('colaboradores.show', $a->asignacionActiva->colaborador) }}" class="link">
                            {{ $a->asignacionActiva->colaborador->nombre }}
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('activos-fijos.show', $a) }}" class="btn btn-outline btn-sm">Ver</a>
                        @if(auth()->user()->esAdministrador())
                        @if($a->isDisponible())
                        <a href="{{ route('activos-fijos.asignar', $a) }}" class="btn btn-success btn-sm">Asignar</a>
                        @elseif($a->estado==='asignado')
                        <button onclick="abrirDevolucion({{ $a->id }},'{{ addslashes($a->nombre_completo) }}','{{ $a->condicion }}')"
                                class="btn btn-outline btn-sm" style="color:var(--amber);border-color:var(--amber);">Devolver</button>
                        @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8">
                <div class="empty-state">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" clip-rule="evenodd"/></svg>
                    <p>No hay activos registrados</p>
                    <a href="{{ route('activos-fijos.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;">Registrar primero</a>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $activos->links() }}
</div>
@endsection

{{-- Modal devolución --}}
<div id="modal-dev" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-title">Registrar Devolución</div>
        <div id="modal-nombre" class="modal-sub"></div>
        <form id="form-dev" method="POST">
            @csrf @method('PATCH')
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Condición al Devolver <span class="required">*</span></label>
                <select name="condicion_devolucion" id="modal-cond" class="form-control" required>
                    <option value="bueno">Bueno</option>
                    <option value="regular">Regular</option>
                    <option value="danado">Dañado</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Observaciones</label>
                <input type="text" name="observaciones" class="form-control" placeholder="Opcional">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Confirmar Devolución</button>
                <button type="button" onclick="cerrarModal()" class="btn btn-outline">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function abrirDevolucion(id, nombre, cond) {
    document.getElementById('modal-nombre').textContent = nombre;
    document.getElementById('modal-cond').value = cond || 'bueno';
    document.getElementById('form-dev').action = '/activos-fijos/' + id + '/devolver';
    document.getElementById('modal-dev').style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modal-dev').style.display = 'none';
}
document.getElementById('modal-dev').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endpush
