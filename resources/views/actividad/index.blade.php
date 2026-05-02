@extends('layouts.app')
@section('title','Log de Actividad')
@section('page-title','Log de Actividad')

@section('content')

{{-- Filtros --}}
<div class="card mb-4">
    <form method="GET" class="flex gap-3" style="align-items:flex-end;flex-wrap:wrap;">
        <div class="form-group" style="flex:2;min-width:180px;">
            <label class="form-label">Buscar</label>
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}"
                   placeholder="Activo, colaborador, detalle...">
        </div>
        <div class="form-group" style="flex:1;min-width:140px;">
            <label class="form-label">Usuario</label>
            <select name="usuario_id" class="form-control">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                <option value="{{ $u->id }}" {{ request('usuario_id')==$u->id?'selected':'' }}>{{ $u->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex:1;min-width:130px;">
            <label class="form-label">Acción</label>
            <select name="accion" class="form-control">
                <option value="">Todas</option>
                @foreach(\App\Models\ActividadLog::$iconos as $v => $i)
                <option value="{{ $v }}" {{ request('accion')===$v?'selected':'' }}>{{ $i }} {{ ucfirst(str_replace('_',' ',$v)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex:1;min-width:130px;">
            <label class="form-label">Módulo</label>
            <select name="modulo" class="form-control">
                <option value="">Todos</option>
                @foreach(\App\Models\ActividadLog::$modulos as $v => $l)
                <option value="{{ $v }}" {{ request('modulo')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="min-width:130px;">
            <label class="form-label">Desde</label>
            <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
        </div>
        <div class="form-group" style="min-width:130px;">
            <label class="form-label">Hasta</label>
            <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
        </div>
        <button type="submit" class="btn btn-outline">Filtrar</button>
        <a href="{{ route('actividad.index') }}" class="btn btn-outline">Limpiar</a>
    </form>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Registro de Actividad</div>
        <span class="text-muted">{{ $logs->total() }} registro(s)</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Registro Afectado</th>
                    <th>Detalle</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
            <tr>
                <td class="mono" style="white-space:nowrap;font-size:12px;">
                    {{ $log->created_at->format('d/m/Y') }}
                    <span style="color:var(--text-muted);">{{ $log->created_at->format('H:i:s') }}</span>
                </td>
                <td>
                    <div class="fw-600" style="font-size:13px;">{{ $log->usuario_nombre }}</div>
                </td>
                <td>
                    <span class="badge {{ $log->color }}">
                        {{ $log->icono }} {{ ucfirst(str_replace('_',' ',$log->accion)) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-gray">{{ $log->modulo_label }}</span>
                </td>
                <td class="fw-600" style="font-size:13px;">{{ $log->entidad_label }}</td>
                <td class="text-muted" style="font-size:12.5px;">{{ $log->detalle ?? '—' }}</td>
                <td class="mono" style="font-size:11px;color:var(--text-muted);">{{ $log->ip ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/></svg>
                        <p>No hay registros de actividad aún</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
