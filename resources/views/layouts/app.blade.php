<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Inventario') — Control de Activos</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2"  y="2"  width="9" height="9" rx="1.5" fill="#00a4ef"/>
                <rect x="13" y="2"  width="9" height="9" rx="1.5" fill="#7fba00"/>
                <rect x="2"  y="13" width="9" height="9" rx="1.5" fill="#ffb900"/>
                <rect x="13" y="13" width="9" height="9" rx="1.5" fill="#f25022"/>
            </svg>
        </div>
        <div>
            <span class="brand-title">Control de Activos</span>
            <span class="brand-sub">Inventario Empresarial</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
            Dashboard
            @if(auth()->check() && auth()->user()->esAdministrador())
                @php
                    $totalAlertas = 0;
                    $totalAlertas += \App\Models\Colaborador::where('estado','inactivo')->where(fn($q)=>$q->whereHas('asignacionesFijosActivas')->orWhereHas('asignacionesMenoresActivas'))->count();
                    $totalAlertas += \App\Models\Mantenimiento::where('estado','en_proceso')->where('fecha_entrada','<=',now()->subDays(30)->toDateString())->count();
                @endphp
                @if($totalAlertas > 0)
                <span style="background:#dc2626;color:#fff;border-radius:20px;padding:1px 6px;font-size:10px;font-weight:700;margin-left:auto;">{{ $totalAlertas }}</span>
                @endif
            @endif
        </a>

        <div class="nav-section">Inventario</div>

        <a href="{{ route('activos-fijos.index') }}"
           class="nav-item {{ request()->routeIs('activos-fijos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/></svg>
            Activos Fijos
        </a>

        <a href="{{ route('activos-menores.index') }}"
           class="nav-item {{ request()->routeIs('activos-menores.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/></svg>
            Activos Menores
        </a>

        <div class="nav-section">Organización</div>

        <a href="{{ route('colaboradores.index') }}"
           class="nav-item {{ request()->routeIs('colaboradores.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
            Colaboradores
        </a>

        <a href="{{ route('departamentos.index') }}"
           class="nav-item {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"/></svg>
            Departamentos
        </a>
    </div>

        @if(auth()->user()->esAdministrador())
        <div class="nav-section">Sistema</div>
        <a href="{{ route('usuarios.index') }}"
           class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
            Usuarios
        </a>
        <a href="{{ route('actividad.index') }}"
           class="nav-item {{ request()->routeIs('actividad.*') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/></svg>
            Log de Actividad
        </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="year-badge">Inventario · {{ now()->year }}</div>
    </div>
</nav>

<div class="main-wrapper">
    <header class="topbar">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="flex gap-2" style="align-items:center;flex:1;justify-content:flex-end;">

                {{-- Campana de Notificaciones --}}
        <div style="position:relative;" id="notif-wrap">
            <button id="notif-btn" onclick="toggleNotif()"
                style="position:relative;background:none;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 10px;cursor:pointer;display:flex;align-items:center;color:var(--text-muted);transition:background .15s;"
                onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='none'">
                <svg viewBox="0 0 20 20" fill="currentColor" style="width:18px;height:18px;">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
                <span id="notif-badge" style="display:none;position:absolute;top:-5px;right:-5px;background:var(--red);color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;line-height:18px;text-align:center;"></span>
            </button>
            <div id="notif-dropdown"
                 style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:340px;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:600;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--surface-2);">
                    <span style="font-size:13.5px;font-weight:600;">Notificaciones</span>
                    <button onclick="marcarTodas()" style="font-size:11.5px;color:var(--blue);background:none;border:none;cursor:pointer;font-family:inherit;">Marcar todas leídas</button>
                </div>
                <div id="notif-lista" style="max-height:380px;overflow-y:auto;">
                    <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px;"><div style="font-size:28px;margin-bottom:8px;">🔔</div>Cargando...</div>
                </div>
                <div style="padding:10px 16px;border-top:1px solid var(--border);text-align:center;">
                    <form action="{{ route('notificaciones.limpiar') }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="font-size:11.5px;color:var(--text-muted);background:none;border:none;cursor:pointer;font-family:inherit;">Limpiar notificaciones leídas</button>
                    </form>
                </div>
            </div>
        </div>

{{-- Buscador Global --}}
        <div style="position:relative;width:260px;">
            <input type="text" id="buscador-global" placeholder="🔍 Buscar activo, colaborador..."
                   autocomplete="off"
                   style="width:100%;padding:7px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:13px;font-family:var(--font);color:var(--text);background:var(--surface-2);outline:none;transition:border-color .15s;"
                   onfocus="this.style.borderColor='var(--blue)'" onblur="setTimeout(()=>{this.style.borderColor='var(--border)'},200)">
            <div id="buscador-dropdown"
                 style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:500;max-height:400px;overflow-y:auto;">
            </div>
        </div>
            @yield('topbar-actions')
            <div style="display:flex;align-items:center;gap:10px;margin-left:8px;padding-left:12px;border-left:1px solid var(--border);">
                <div style="text-align:right;">
                    <div style="font-size:13px;font-weight:600;color:var(--text);">{{ auth()->user()->nombre }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">
                        @if(auth()->user()->esAdministrador())
                            <span style="color:var(--blue);">● Administrador</span>
                        @else
                            <span style="color:var(--text-muted);">● Consultor</span>
                        @endif
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" title="Cerrar sesión"
                            style="color:var(--text-muted);" onclick="return confirm('¿Cerrar sesión?')">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="width:15px;height:15px;"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-error">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>

<script>
// Buscador Global
(function() {
    const input = document.getElementById('buscador-global');
    const dropdown = document.getElementById('buscador-dropdown');
    if (!input) return;

    let timer;

    input.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        timer = setTimeout(() => {
            fetch('/buscar?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.length) {
                    dropdown.innerHTML = '<div style="padding:16px;text-align:center;color:#94a3b8;font-size:13px;">Sin resultados para "' + q + '"</div>';
                    dropdown.style.display = 'block';
                    return;
                }

                dropdown.innerHTML = data.map(r => `
                    <a href="${r.url}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;text-decoration:none;color:#1a202c;border-bottom:1px solid #f1f5f9;transition:background .1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <span style="font-size:20px;line-height:1;flex-shrink:0;">${r.icono}</span>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.titulo}</div>
                            <div style="font-size:11.5px;color:#64748b;margin-top:1px;">${r.sub}</div>
                        </div>
                        <span style="font-size:10.5px;font-weight:500;padding:2px 7px;border-radius:12px;flex-shrink:0;background:${badgeColor(r.badge)};color:${badgeText(r.badge)};">${r.tipo}</span>
                    </a>
                `).join('');

                dropdown.style.display = 'block';
            })
            .catch(() => { dropdown.style.display = 'none'; });
        }, 250);
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Cerrar con Escape
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdown.style.display = 'none';
            this.blur();
        }
    });

    function badgeColor(badge) {
        const map = { 'badge-blue':'#dbeafe', 'badge-cyan':'#cffafe', 'badge-purple':'#ede9fe', 'badge-green':'#dcfce7', 'badge-gray':'#f1f5f9' };
        return map[badge] || '#f1f5f9';
    }
    function badgeText(badge) {
        const map = { 'badge-blue':'#1e40af', 'badge-cyan':'#164e63', 'badge-purple':'#5b21b6', 'badge-green':'#166534', 'badge-gray':'#475569' };
        return map[badge] || '#475569';
    }
})();
</script>

<script>
// ── Notificaciones ────────────────────────────────────────────────────────────
(function() {
    let abierto = false;

    window.toggleNotif = function() {
        abierto = !abierto;
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = abierto ? 'block' : 'none';
        if (abierto) cargarNotif();
    };

    function cargarNotif() {
        fetch('/notificaciones', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            actualizarBadge(data.no_leidas);
            const lista = document.getElementById('notif-lista');
            if (!data.notificaciones.length) {
                lista.innerHTML = '<div style="padding:28px;text-align:center;color:#94a3b8;font-size:13px;"><div style="font-size:32px;margin-bottom:8px;">🔔</div>Sin notificaciones</div>';
                return;
            }
            lista.innerHTML = data.notificaciones.map(function(n) {
                return '<div onclick="irA(\'' + n.url + '\',\'' + n.id + '\',' + n.leida + ')" style="display:flex;gap:12px;padding:12px 16px;border-bottom:1px solid #f1f5f9;cursor:pointer;background:' + (n.leida ? '#fff' : '#f8fafc') + ';transition:background .1s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'' + (n.leida ? '#fff' : '#f8fafc') + '\'">'
                    + '<div style="font-size:22px;line-height:1;flex-shrink:0;margin-top:2px;">' + n.icono + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    + '<div style="font-size:13px;font-weight:' + (n.leida ? '400' : '600') + ';color:#1a202c;line-height:1.3;">' + n.titulo + '</div>'
                    + (n.mensaje ? '<div style="font-size:11.5px;color:#64748b;margin-top:2px;">' + n.mensaje + '</div>' : '')
                    + '<div style="font-size:10.5px;color:#94a3b8;margin-top:4px;">' + n.tiempo + '</div>'
                    + '</div>'
                    + (!n.leida ? '<div style="width:8px;height:8px;background:#2563eb;border-radius:50%;flex-shrink:0;margin-top:5px;"></div>' : '')
                    + '</div>';
            }).join('');
        }).catch(function() {});
    }

    window.irA = function(url, id, leida) {
        if (!leida) {
            fetch('/notificaciones/' + id + '/leer', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function() {
                if (url && url !== 'null') window.location.href = url;
                else cargarNotif();
            });
        } else {
            if (url && url !== 'null') window.location.href = url;
        }
    };

    window.marcarTodas = function() {
        fetch('/notificaciones/leer-todas', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function() { cargarNotif(); actualizarBadge(0); });
    };

    function actualizarBadge(count) {
        var badge = document.getElementById('notif-badge');
        if (!badge) return;
        if (count > 0) { badge.textContent = count > 9 ? '9+' : count; badge.style.display = 'block'; }
        else { badge.style.display = 'none'; }
    }

    document.addEventListener('click', function(e) {
        var wrap = document.getElementById('notif-wrap');
        if (wrap && !wrap.contains(e.target)) {
            var dd = document.getElementById('notif-dropdown');
            if (dd) dd.style.display = 'none';
            abierto = false;
        }
    });

    fetch('/notificaciones', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(data) { actualizarBadge(data.no_leidas); })
        .catch(function() {});

    setInterval(function() {
        fetch('/notificaciones', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) { actualizarBadge(data.no_leidas); })
            .catch(function() {});
    }, 60000);
})();
</script>

@stack('scripts')
</body>
</html>
