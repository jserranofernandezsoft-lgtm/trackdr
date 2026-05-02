<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ActivoMenorController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\NotificacionController;

// Autenticacion
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buscar', [BuscadorController::class, 'buscar'])->name('buscar');

    // Notificaciones
    Route::get('notificaciones',                         [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::patch('notificaciones/{notificacion}/leer',   [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('notificaciones/leer-todas',             [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leerTodas');
    Route::delete('notificaciones/limpiar',              [NotificacionController::class, 'limpiar'])->name('notificaciones.limpiar');

    // Departamentos
    Route::resource('departamentos', DepartamentoController::class);

    // Colaboradores
    Route::resource('colaboradores', ColaboradorController::class);

    // ── Activos Fijos ─────────────────────────────────────────────────────────
    // IMPORTANTE: rutas estáticas ANTES que rutas con parámetros
    Route::get('activos-fijos',        [ActivoFijoController::class, 'index'])->name('activos-fijos.index');

    // Rutas solo admin — van ANTES del show/{id} para evitar conflictos
    Route::middleware('admin')->group(function () {
        Route::get('activos-fijos/create',  [ActivoFijoController::class, 'create'])->name('activos-fijos.create');
        Route::post('activos-fijos',        [ActivoFijoController::class, 'store'])->name('activos-fijos.store');
    });

    // Rutas con parámetro {activosFijo} — van DESPUÉS
    Route::get('activos-fijos/{activosFijo}',      [ActivoFijoController::class, 'show'])->name('activos-fijos.show');
    Route::get('activos-fijos/{activosFijo}/qr',   [ActivoFijoController::class, 'qr'])->name('activos-fijos.qr');

    Route::middleware('admin')->group(function () {
        Route::get('activos-fijos/{activosFijo}/edit',    [ActivoFijoController::class, 'edit'])->name('activos-fijos.edit');
        Route::put('activos-fijos/{activosFijo}',         [ActivoFijoController::class, 'update'])->name('activos-fijos.update');
        Route::get('activos-fijos/{activosFijo}/asignar', [ActivoFijoController::class, 'asignar'])->name('activos-fijos.asignar');
        Route::post('activos-fijos/{activosFijo}/asignar',[ActivoFijoController::class, 'storeAsignacion'])->name('activos-fijos.storeAsignacion');
        Route::patch('activos-fijos/{activosFijo}/devolver', [ActivoFijoController::class, 'devolver'])->name('activos-fijos.devolver');
        Route::get('activos-fijos/{activosFijo}/mantenimiento',  [ActivoFijoController::class, 'crearMantenimiento'])->name('activos-fijos.mantenimiento.create');
        Route::post('activos-fijos/{activosFijo}/mantenimiento', [ActivoFijoController::class, 'storeMantenimiento'])->name('activos-fijos.mantenimiento.store');
        Route::patch('activos-fijos/{activosFijo}/mantenimiento/{mantenimiento}/cerrar', [ActivoFijoController::class, 'cerrarMantenimiento'])->name('activos-fijos.mantenimiento.cerrar');
    });

    // ── Activos Menores ───────────────────────────────────────────────────────
    Route::get('activos-menores', [ActivoMenorController::class, 'index'])->name('activos-menores.index');

    Route::middleware('admin')->group(function () {
        Route::get('activos-menores/create', [ActivoMenorController::class, 'create'])->name('activos-menores.create');
        Route::post('activos-menores',       [ActivoMenorController::class, 'store'])->name('activos-menores.store');
    });

    Route::get('activos-menores/{activosMenore}', [ActivoMenorController::class, 'show'])->name('activos-menores.show');
    Route::get('activos-menores/{activosMenore}/qr', [ActivoMenorController::class, 'qr'])->name('activos-menores.qr');

    Route::middleware('admin')->group(function () {
        Route::get('activos-menores/{activosMenore}/edit',       [ActivoMenorController::class, 'edit'])->name('activos-menores.edit');
        Route::put('activos-menores/{activosMenore}',            [ActivoMenorController::class, 'update'])->name('activos-menores.update');
        Route::get('activos-menores/{activosMenore}/asignar',    [ActivoMenorController::class, 'asignar'])->name('activos-menores.asignar');
        Route::post('activos-menores/{activosMenore}/asignar',   [ActivoMenorController::class, 'storeAsignacion'])->name('activos-menores.storeAsignacion');
        Route::patch('activos-menores/{activosMenore}/devolver', [ActivoMenorController::class, 'devolver'])->name('activos-menores.devolver');
    });

    // ── Fotos ─────────────────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::post('activos-fijos/{activosFijo}/fotos',     [FotoController::class, 'storeParaFijo'])->name('fotos.fijo.store');
        Route::post('activos-menores/{activosMenore}/fotos', [FotoController::class, 'storeParaMenor'])->name('fotos.menor.store');
        Route::delete('fotos/{foto}',                        [FotoController::class, 'destroy'])->name('fotos.destroy');
    });

    // ── Exportar ──────────────────────────────────────────────────────────────
    Route::get('export/pdf/activos-fijos',              [ExportController::class, 'pdfActivosFijos'])->name('export.pdf.activos-fijos');
    Route::get('export/pdf/activos-menores',            [ExportController::class, 'pdfActivosMenores'])->name('export.pdf.activos-menores');
    Route::get('export/pdf/colaborador/{colaboradore}', [ExportController::class, 'pdfColaborador'])->name('export.pdf.colaborador');
    Route::get('export/pdf/mantenimientos',             [ExportController::class, 'pdfMantenimientos'])->name('export.pdf.mantenimientos');
    Route::get('export/excel/activos-fijos',            [ExportController::class, 'excelActivosFijos'])->name('export.excel.activos-fijos');
    Route::get('export/excel/activos-menores',          [ExportController::class, 'excelActivosMenores'])->name('export.excel.activos-menores');
    Route::get('export/excel/colaboradores',            [ExportController::class, 'excelColaboradores'])->name('export.excel.colaboradores');

    // ── Usuarios ──────────────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
        Route::get('actividad', [ActividadController::class, 'index'])->name('actividad.index');
    });
});
