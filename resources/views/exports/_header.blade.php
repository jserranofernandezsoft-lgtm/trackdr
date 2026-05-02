<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{{ $titulo }}</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', Arial, sans-serif;
        font-size: 12px;
        color: #1a202c;
        background: #fff;
        padding: 28px 32px;
    }

    /* Encabezado */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 3px solid #0f172a;
        margin-bottom: 20px;
    }
    .header-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ms-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3px;
        width: 28px;
        height: 28px;
    }
    .ms-grid span { border-radius: 2px; }
    .ms-blue   { background: #00a4ef; }
    .ms-green  { background: #7fba00; }
    .ms-yellow { background: #ffb900; }
    .ms-red    { background: #f25022; }
    .brand-name { font-size: 15px; font-weight: 700; color: #0f172a; }
    .brand-sub  { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
    .header-info { text-align: right; }
    .header-info .report-title { font-size: 16px; font-weight: 700; color: #0f172a; }
    .header-info .report-date  { font-size: 10.5px; color: #64748b; margin-top: 3px; }

    /* Filtros activos */
    .filtros-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 14px;
        margin-bottom: 16px;
        font-size: 11px;
        color: #64748b;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filtros-bar strong { color: #1a202c; }

    /* Resumen stats */
    .stats-row {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .stat-box {
        flex: 1;
        min-width: 90px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        text-align: center;
        border-top: 3px solid #2563eb;
    }
    .stat-box.green  { border-top-color: #16a34a; }
    .stat-box.purple { border-top-color: #7c3aed; }
    .stat-box.amber  { border-top-color: #d97706; }
    .stat-box.red    { border-top-color: #dc2626; }
    .stat-box .num   { font-size: 22px; font-weight: 700; color: #0f172a; line-height: 1; }
    .stat-box .lbl   { font-size: 10px; color: #64748b; margin-top: 4px; text-transform: uppercase; letter-spacing: .4px; }

    /* Tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
    }
    thead tr th {
        background: #0f172a;
        color: #fff;
        padding: 8px 10px;
        text-align: left;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }
    tbody tr td {
        padding: 7px 10px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    tbody tr:nth-child(even) td { background: #f8fafc; }
    tbody tr:last-child td { border-bottom: none; }

    .mono { font-family: 'DM Mono', monospace; font-size: 11px; }
    .fw { font-weight: 600; }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 12px;
        font-size: 10.5px;
        font-weight: 500;
    }
    .badge-green  { background: #dcfce7; color: #166534; }
    .badge-red    { background: #fee2e2; color: #991b1b; }
    .badge-amber  { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .badge-gray   { background: #f1f5f9; color: #475569; }
    .badge-blue   { background: #dbeafe; color: #1e40af; }

    /* Footer */
    .footer {
        margin-top: 24px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: #94a3b8;
    }

    /* Print */
    @media print {
        body { padding: 12px 16px; }
        .no-print { display: none !important; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
        thead { display: table-header-group; }
    }
</style>
</head>
<body>
