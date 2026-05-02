@extends('layouts.app')
@section('title','Colaboradores')
@section('page-title','Colaboradores')
@section('topbar-actions')
<a href="{{ route('export.excel.colaboradores') }}" class="btn btn-outline btn-sm">📊 Excel</a>
@if(auth()->user()->esAdministrador())
<a href="{{ route('colaboradores.create') }}" class="btn btn-primary btn-sm">+ Nuevo Colaborador</a>
@endif
@endsection
@section('content')
<div class="card mb-4">
    <form method="GET" class="flex gap-3" style="align-items:flex-end;flex-wrap:wrap;">
        <div class="form-group" style="flex:2;min-width:180px;"><label class="form-label">Buscar</label><input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Nombre, correo, cargo..."></div>
        <div class="form-group" style="flex:1;min-width:160px;"><label class="form-label">Departamento</label><select name="departamento_id" class="form-control"><option value="">Todos</option>@foreach($departamentos as $d)<option value="{{ $d->id }}" {{ request('departamento_id')==$d->id?'selected':'' }}>{{ $d->nombre }}</option>@endforeach</select></div>
        <div class="form-group" style="flex:1;min-width:120px;"><label class="form-label">Estado</label><select name="estado" class="form-control"><option value="">Todos</option><option value="activo" {{ request('estado')==='activo'?'selected':'' }}>Activo</option><option value="inactivo" {{ request('estado')==='inactivo'?'selected':'' }}>Inactivo</option></select></div>
        <button type="submit" class="btn btn-outline">Filtrar</button>
        <a href="{{ route('colaboradores.index') }}" class="btn btn-outline">Limpiar</a>
    </form>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nombre</th><th>Correo</th><th>Cargo</th><th>Departamento</th><th>Estado</th><th>Activos</th><th></th></tr></thead>
            <tbody>
            @forelse($colaboradores as $c)
            <tr>
                <td class="fw-600"><a href="{{ route('colaboradores.show',$c) }}" class="link">{{ $c->nombre }}</a></td>
                <td class="mono" style="font-size:12.5px;">{{ $c->correo }}</td>
                <td class="text-muted">{{ $c->cargo ?? '—' }}</td>
                <td><a href="{{ route('departamentos.show',$c->departamento) }}" class="link">{{ $c->departamento->nombre }}</a></td>
                <td>@if($c->estado==='activo')<span class="badge badge-green">Activo</span>@else<span class="badge badge-gray">Inactivo</span>@endif</td>
                <td class="text-center"><span class="badge badge-purple">{{ $c->total_activos_asignados }}</span></td>
                <td><div class="flex gap-2"><a href="{{ route('colaboradores.show',$c) }}" class="btn btn-outline btn-sm">Ver</a><a href="{{ route('colaboradores.edit',$c) }}" class="btn btn-outline btn-sm">Editar</a></div></td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg><p>No hay colaboradores</p><a href="{{ route('colaboradores.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;">Agregar primero</a></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $colaboradores->links() }}
</div>
@endsection
