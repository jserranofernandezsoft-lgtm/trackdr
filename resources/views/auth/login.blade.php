<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Iniciar Sesión — Control de Activos</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',system-ui,sans-serif;background:#f4f6fa;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
.login-wrap{width:100%;max-width:420px;}
.login-brand{text-align:center;margin-bottom:32px;}
.ms-grid{display:inline-grid;grid-template-columns:1fr 1fr;gap:4px;width:40px;height:40px;margin-bottom:14px;}
.ms-grid span{border-radius:3px;}
.ms-blue{background:#00a4ef}.ms-green{background:#7fba00}.ms-yellow{background:#ffb900}.ms-red{background:#f25022}
.brand-name{font-size:20px;font-weight:700;color:#0f172a;}
.brand-sub{font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-top:3px;}
.card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:32px;box-shadow:0 4px 24px rgba(0,0,0,.06);}
.card-title{font-size:18px;font-weight:700;color:#0f172a;margin-bottom:6px;}
.card-sub{font-size:13px;color:#64748b;margin-bottom:24px;}
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:13px;font-weight:500;color:#1a202c;margin-bottom:6px;}
.form-control{width:100%;padding:10px 13px;border:1px solid #e2e8f0;border-radius:7px;font-size:14px;font-family:inherit;color:#1a202c;background:#fff;transition:border-color .15s,box-shadow .15s;appearance:none;}
.form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.form-control.error{border-color:#dc2626;}
.error-msg{font-size:12px;color:#dc2626;margin-top:5px;}
.remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#475569;margin-bottom:22px;cursor:pointer;}
.remember input{width:15px;height:15px;cursor:pointer;}
.btn-login{width:100%;padding:11px;background:#2563eb;color:#fff;border:none;border-radius:7px;font-size:15px;font-weight:600;font-family:inherit;cursor:pointer;transition:background .15s;}
.btn-login:hover{background:#1d4ed8;}
.footer-note{text-align:center;margin-top:20px;font-size:12px;color:#94a3b8;}
</style>
</head>
<body>
<div class="login-wrap">
    <div class="login-brand">
        <div class="ms-grid">
            <span class="ms-blue"></span>
            <span class="ms-green"></span>
            <span class="ms-yellow"></span>
            <span class="ms-red"></span>
        </div>
        <div class="brand-name">Control de Activos</div>
        <div class="brand-sub">Inventario Empresarial</div>
    </div>

    <div class="card">
        <div class="card-title">Iniciar Sesión</div>
        <div class="card-sub">Ingresa tus credenciales para continuar</div>

        @if($errors->any())
        <div style="background:#fee2e2;color:#7f1d1d;padding:10px 14px;border-radius:7px;font-size:13px;margin-bottom:18px;display:flex;gap:8px;align-items:flex-start;">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control {{ $errors->has('correo') ? 'error' : '' }}"
                       value="{{ old('correo') }}" required autofocus placeholder="usuario@empresa.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                       required placeholder="••••••••">
            </div>
            <label class="remember">
                <input type="checkbox" name="remember" value="1"> Mantener sesión iniciada
            </label>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
    <div class="footer-note">Control de Activos · {{ now()->year }}</div>
</div>
</body>
</html>
