@extends('layouts.app')
@section('title', $activosMenore->numero_activo)
@section('page-title', $activosMenore->icono . ' ' . $activosMenore->nombre_completo)
@section('topbar-actions')
<a href="{{ route('activos-menores.index') }}" class="btn btn-outline btn-sm">← Volver</a>
<a href="{{ route('activos-menores.qr', $activosMenore) }}" target="_blank" class="btn btn-outline btn-sm">📱 QR</a>
<a href="{{ route('activos-menores.edit', $activosMenore) }}" class="btn btn-outline btn-sm">Editar</a>
<button onclick="document.getElementById('modal-fotos').style.display='flex'" class="btn btn-outline btn-sm">
    📷 Ver Fotos @if($activosMenore->fotos->count() > 0)<span class="badge badge-blue" style="margin-left:4px;">{{ $activosMenore->fotos->count() }}</span>@endif
</button>
@if($activosMenore->isDisponible())
<a href="{{ route('activos-menores.asignar', $activosMenore) }}" class="btn btn-success btn-sm">Asignar</a>
@endif
@endsection
@section('content')

<div class="card mb-6">
    <div class="flex" style="gap:20px;align-items:flex-start;">
        <div style="font-size:48px;line-height:1;">{{ $activosMenore->icono }}</div>
        <div style="flex:1;">
            <div style="font-size:22px;font-weight:700;">{{ $activosMenore->nombre_completo }}</div>
            <div class="mono text-muted" style="margin-top:4px;">{{ $activosMenore->numero_activo }}</div>
            <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
                <span class="badge {{ \App\Models\ActivoMenor::$estadoBadge[$activosMenore->estado]??'badge-gray' }}" style="font-size:13px;padding:4px 12px;">{{ $activosMenore->estado_label }}</span>
                @if($activosMenore->condicion==='bueno')<span class="badge badge-green">Bueno</span>@elseif($activosMenore->condicion==='regular')<span class="badge badge-amber">Regular</span>@else<span class="badge badge-red">Dañado</span>@endif
                <span class="badge badge-gray">{{ $activosMenore->tipo_label }}</span>
            </div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border);">
        <div class="detail-item"><label>Serial</label><p class="mono">{{ $activosMenore->serial ?? '—' }}</p></div>
        <div class="detail-item"><label>Marca</label><p>{{ $activosMenore->marca ?? '—' }}</p></div>
        <div class="detail-item"><label>Modelo</label><p>{{ $activosMenore->modelo ?? '—' }}</p></div>
        <div class="detail-item"><label>Departamento</label><p>@if($activosMenore->departamento)<a href="{{ route('departamentos.show',$activosMenore->departamento) }}" class="link">{{ $activosMenore->departamento->nombre }}</a>@else General @endif</p></div>
        <div class="detail-item"><label>Proveedor</label><p>{{ $activosMenore->proveedor ?? '—' }}</p></div>
        <div class="detail-item"><label>Valor</label><p class="mono">${{ $activosMenore->valor_adquisicion ? number_format($activosMenore->valor_adquisicion,2) : '—' }}</p></div>
        <div class="detail-item"><label>Adquisición</label><p class="mono">{{ $activosMenore->fecha_adquisicion?->format('d/m/Y') ?? '—' }}</p></div>
    </div>
</div>

@if($activosMenore->asignacionActiva)
@php $aa = $activosMenore->asignacionActiva @endphp
<div class="card mb-6" style="border-left:4px solid var(--purple);">
    <div class="card-header"><div class="card-title">Asignado Actualmente</div><span class="badge badge-purple">Activo</span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:18px;">
        <div class="detail-item"><label>Colaborador</label><p><a href="{{ route('colaboradores.show',$aa->colaborador) }}" class="link" style="font-size:15px;">{{ $aa->colaborador->nombre }}</a></p></div>
        <div class="detail-item"><label>Departamento</label><p>{{ $aa->colaborador->departamento->nombre ?? '—' }}</p></div>
        <div class="detail-item"><label>Desde</label><p class="mono">{{ $aa->fecha_asignacion->format('d/m/Y') }}</p></div>
        <div class="detail-item"><label>Condición Entrega</label><p>{{ \App\Models\ActivoMenor::$condiciones[$aa->condicion_entrega] ?? '—' }}</p></div>
    </div>
    <div style="padding:16px;background:var(--surface-2);border-radius:var(--radius-sm);">
        <div class="section-title">Registrar Devolución</div>
        <form action="{{ route('activos-menores.devolver',$activosMenore) }}" method="POST" class="flex gap-3" style="align-items:flex-end;flex-wrap:wrap;">
            @csrf @method('PATCH')
            <div class="form-group" style="flex:1;min-width:150px;"><label class="form-label">Condición <span class="required">*</span></label><select name="condicion_devolucion" class="form-control" required><option value="bueno">Bueno</option><option value="regular">Regular</option><option value="danado">Dañado</option></select></div>
            <div class="form-group" style="flex:2;min-width:200px;"><label class="form-label">Observaciones</label><input type="text" name="observaciones" class="form-control" placeholder="Opcional"></div>
            <button type="submit" class="btn btn-outline" style="color:var(--amber);border-color:var(--amber);" onclick="return confirm('¿Confirmar devolución?')">Confirmar Devolución</button>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header"><div class="card-title">Historial de Asignaciones</div><span class="text-muted">{{ $activosMenore->asignaciones->count() }} registro(s)</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Colaborador</th><th>Departamento</th><th>Desde</th><th>Devuelto</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($activosMenore->asignaciones as $a)
            <tr>
                <td><a href="{{ route('colaboradores.show',$a->colaborador) }}" class="link">{{ $a->colaborador->nombre }}</a></td>
                <td class="text-muted">{{ $a->colaborador->departamento->nombre ?? '—' }}</td>
                <td class="mono">{{ $a->fecha_asignacion->format('d/m/Y') }}</td>
                <td class="mono">{{ $a->fecha_devolucion_real?->format('d/m/Y') ?? '—' }}</td>
                <td>@if($a->estado==='activo')<span class="badge badge-purple">Activo</span>@else<span class="badge badge-gray">Devuelto</span>@endif</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted" style="padding:20px">Sin historial</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Fotos --}}
<div id="modal-fotos" class="modal-overlay" style="align-items:flex-start;padding:40px 20px;overflow-y:auto;">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:860px;margin:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div>
                <div style="font-size:17px;font-weight:700;">Fotos del Activo</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $activosMenore->numero_activo }} — {{ $activosMenore->nombre_completo }}</div>
            </div>
            <button onclick="document.getElementById('modal-fotos').style.display='none'" style="background:none;border:none;font-size:22px;cursor:pointer;color:var(--text-muted);">✕</button>
        </div>

        <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:20px;margin-bottom:24px;">
            <form action="{{ route('fotos.menor.store', $activosMenore) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:200px;">
                        <label style="font-size:12.5px;font-weight:500;display:block;margin-bottom:6px;">Agregar fotos (JPG, PNG, WEBP — máx. 5MB c/u)</label>
                        <input type="file" name="fotos[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp" required>
                    </div>
                    <button type="submit" class="btn btn-primary">📷 Subir</button>
                </div>
            </form>
        </div>

        @if($activosMenore->fotos->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
            @foreach($activosMenore->fotos as $foto)
            <div style="border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;background:var(--surface-2);">
                <img src="{{ Storage::url($foto->ruta) }}" alt="{{ $foto->nombre_original }}"
                     style="width:100%;height:160px;object-fit:cover;cursor:pointer;"
                     onclick="abrirFotoGrande('{{ Storage::url($foto->ruta) }}','{{ $foto->nombre_original }}')">
                <div style="padding:8px 10px;display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:11.5px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;" title="{{ $foto->nombre_original }}">
                        {{ $foto->nombre_original }}
                    </span>
                    <form action="{{ route('fotos.destroy', $foto) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta foto?')" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--red);font-size:16px;padding:2px 4px;" title="Eliminar">🗑</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:40px;margin-bottom:12px;">📷</div>
            <p>No hay fotos registradas para este activo.</p>
        </div>
        @endif
    </div>
</div>

<div id="visor-foto" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:2000;align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="visor-img" src="" alt="" style="max-width:92vw;max-height:90vh;border-radius:8px;object-fit:contain;">
</div>

@push('scripts')
<script>
document.getElementById('modal-fotos').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
function abrirFotoGrande(url, alt) {
    document.getElementById('visor-img').src = url;
    document.getElementById('visor-img').alt = alt;
    document.getElementById('visor-foto').style.display = 'flex';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('modal-fotos').style.display = 'none';
        document.getElementById('visor-foto').style.display = 'none';
    }
});
</script>
@endpush

@endsection