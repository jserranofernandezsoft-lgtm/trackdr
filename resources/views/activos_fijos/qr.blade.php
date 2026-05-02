<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>QR — {{ $activo->numero_activo }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;background:#fff;padding:30px;display:flex;flex-direction:column;align-items:center;}
.no-print{margin-bottom:24px;display:flex;gap:10px;}
.btn{padding:9px 20px;border-radius:7px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid transparent;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
.btn-blue{background:#2563eb;color:#fff;border:none;}
.btn-gray{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;}
.qr-card{border:2px solid #e2e8f0;border-radius:12px;padding:24px;width:280px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.08);}
.ms-grid{display:inline-grid;grid-template-columns:1fr 1fr;gap:3px;width:24px;height:24px;margin-bottom:8px;}
.ms-grid span{border-radius:2px;}
.brand-name{font-size:13px;font-weight:700;color:#0f172a;margin-bottom:4px;}
.brand-sub{font-size:10px;color:#64748b;text-transform:uppercase;letter-spacing:.4px;margin-bottom:16px;}
.qr-img{width:180px;height:180px;margin:0 auto 16px;}
.qr-numero{font-family:monospace;font-size:18px;font-weight:700;color:#0f172a;letter-spacing:1px;margin-bottom:6px;}
.qr-nombre{font-size:13px;font-weight:600;color:#1a202c;margin-bottom:4px;}
.qr-tipo{font-size:11px;color:#64748b;margin-bottom:8px;}
.qr-serial{font-family:monospace;font-size:10.5px;color:#94a3b8;}
.qr-url{font-size:9px;color:#94a3b8;margin-top:10px;word-break:break-all;}
.divider{height:1px;background:#e2e8f0;margin:12px 0;}
@media print{
    .no-print{display:none!important;}
    body{padding:10px;}
    .qr-card{box-shadow:none;border:1.5px solid #cbd5e1;}
}
</style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-blue">🖨️ Imprimir QR</button>
    <button onclick="window.close()" class="btn btn-gray">✕ Cerrar</button>
    <span style="font-size:12px;color:#94a3b8;align-self:center;">Imprime y pega en el equipo físico</span>
</div>

<div class="qr-card">
    {{-- Logo --}}
    <div class="ms-grid">
        <span style="background:#00a4ef;border-radius:2px;"></span>
        <span style="background:#7fba00;border-radius:2px;"></span>
        <span style="background:#ffb900;border-radius:2px;"></span>
        <span style="background:#f25022;border-radius:2px;"></span>
    </div>
    <div class="brand-name">Control de Activos</div>
    <div class="brand-sub">Inventario Empresarial</div>

    <div class="divider"></div>

    {{-- QR generado con API gratuita --}}
    <img class="qr-img"
         src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($url) }}&color=0f172a&bgcolor=ffffff&margin=6"
         alt="QR {{ $activo->numero_activo }}">

    <div class="qr-numero">{{ $activo->numero_activo }}</div>
    <div class="qr-nombre">{{ $activo->nombre_completo }}</div>
    <div class="qr-tipo">{{ $activo->tipo_label }}</div>

    @if($activo->serial)
    <div class="qr-serial">S/N: {{ $activo->serial }}</div>
    @endif

    @if($activo->departamento)
    <div class="qr-serial" style="margin-top:3px;">{{ $activo->departamento->nombre }}</div>
    @endif

    <div class="divider"></div>
    <div class="qr-url">{{ $url }}</div>
</div>

</body>
</html>
