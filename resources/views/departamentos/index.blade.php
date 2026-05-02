@extends('layouts.app')
@section('title','Departamentos')
@section('page-title','Departamentos')
@section('topbar-actions')
<a href="{{ route('departamentos.create') }}" class="btn btn-primary btn-sm">+ Nuevo Departamento</a>
@endsection
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nombre</th><th>Código</th><th>Descripción</th><th>Colaboradores</th><th>Activos Fijos</th><th>Activos Menores</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($departamentos as $d)
            <tr>
                <td class="fw-600"><a href="{{ route('departamentos.show',$d) }}" class="link">{{ $d->nombre }}</a></td>
                <td><span class="badge badge-gray mono">{{ $d->codigo }}</span></td>
                <td class="text-muted">{{ $d->descripcion ?? '—' }}</td>
                <td class="text-center"><span class="badge badge-blue">{{ $d->colaboradores_count }}</span></td>
                <td class="text-center"><span class="badge badge-purple">{{ $d->activos_fijos_count }}</span></td>
                <td class="text-center"><span class="badge badge-cyan">{{ $d->activos_menores_count }}</span></td>
                <td>@if($d->estado==='activo')<span class="badge badge-green">Activo</span>@else<span class="badge badge-gray">Inactivo</span>@endif</td>
                <td><div class="flex gap-2">
                    <a href="{{ route('departamentos.show',$d) }}" class="btn btn-outline btn-sm">Ver</a>
                    <a href="{{ route('departamentos.edit',$d) }}" class="btn btn-outline btn-sm">Editar</a>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"/></svg><p>No hay departamentos</p><a href="{{ route('departamentos.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;">Crear primero</a></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $departamentos->links() }}
</div>
@endsection
