<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Colaborador;
use App\Models\ActivoFijo;
use App\Models\ActivoMenor;
use App\Models\AsignacionFijo;
use App\Models\AsignacionMenor;
use App\Models\Mantenimiento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar en orden correcto
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('notificaciones')->truncate();
        DB::table('actividad_log')->truncate();
        DB::table('fotos_activos')->truncate();
        DB::table('usuarios')->truncate();
        DB::table('mantenimientos')->truncate();
        DB::table('asignaciones_menores')->truncate();
        DB::table('asignaciones_fijos')->truncate();
        DB::table('activos_menores')->truncate();
        DB::table('activos_fijos')->truncate();
        DB::table('colaboradores')->truncate();
        DB::table('departamentos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── Usuarios ──────────────────────────────────────────────────────────────
        Usuario::create([
            'nombre'   => 'Administrador',
            'correo'   => 'admin@empresa.com',
            'password' => Hash::make('Admin2024!'),
            'rol'      => 'administrador',
            'estado'   => 'activo',
        ]);

        Usuario::create([
            'nombre'   => 'Consultor Demo',
            'correo'   => 'consultor@empresa.com',
            'password' => Hash::make('Consultor2024!'),
            'rol'      => 'consultor',
            'estado'   => 'activo',
        ]);

        // ── Departamentos ─────────────────────────────────────────────────────
        $ti   = Departamento::create(['nombre' => 'Tecnología de la Información', 'codigo' => 'TI',   'estado' => 'activo', 'descripcion' => 'Gestión de infraestructura y sistemas']);
        $fin  = Departamento::create(['nombre' => 'Finanzas',                     'codigo' => 'FIN',  'estado' => 'activo', 'descripcion' => 'Contabilidad y control financiero']);
        $rrhh = Departamento::create(['nombre' => 'Recursos Humanos',             'codigo' => 'RRHH', 'estado' => 'activo', 'descripcion' => 'Gestión del talento humano']);
        $ops  = Departamento::create(['nombre' => 'Operaciones',                  'codigo' => 'OPS',  'estado' => 'activo', 'descripcion' => 'Procesos operativos']);

        // ── Colaboradores ─────────────────────────────────────────────────────
        $maria  = Colaborador::create(['nombre' => 'María Rodríguez', 'correo' => 'mrodriguez@empresa.com', 'cargo' => 'Analista TI',         'telefono' => '809-555-0101', 'departamento_id' => $ti->id,   'estado' => 'activo']);
        $carlos = Colaborador::create(['nombre' => 'Carlos Méndez',   'correo' => 'cmendez@empresa.com',   'cargo' => 'Desarrollador Senior',  'telefono' => '809-555-0102', 'departamento_id' => $ti->id,   'estado' => 'activo']);
        $ana    = Colaborador::create(['nombre' => 'Ana González',    'correo' => 'agonzalez@empresa.com', 'cargo' => 'Contadora',             'telefono' => '809-555-0103', 'departamento_id' => $fin->id,  'estado' => 'activo']);
        $luis   = Colaborador::create(['nombre' => 'Luis Peralta',    'correo' => 'lperalta@empresa.com',  'cargo' => 'Analista Financiero',   'telefono' => '809-555-0104', 'departamento_id' => $fin->id,  'estado' => 'activo']);
        $sofia  = Colaborador::create(['nombre' => 'Sofía Martínez',  'correo' => 'smartinez@empresa.com', 'cargo' => 'Especialista RRHH',     'telefono' => '809-555-0105', 'departamento_id' => $rrhh->id, 'estado' => 'activo']);

        // ── Activos Fijos ─────────────────────────────────────────────────────
        $laptop1 = ActivoFijo::create([
            'numero_activo' => 'AF-0001', 'tipo' => 'laptop', 'marca' => 'Dell',
            'modelo' => 'Latitude 5540', 'serial' => 'DELL-5540-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 1200.00,
            'fecha_adquisicion' => now()->subMonths(6)->toDateString(),
            'proveedor' => 'KS Technology',
        ]);

        $laptop2 = ActivoFijo::create([
            'numero_activo' => 'AF-0002', 'tipo' => 'laptop', 'marca' => 'HP',
            'modelo' => 'EliteBook 840 G9', 'serial' => 'HP-840-002',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 1350.00,
            'fecha_adquisicion' => now()->subMonths(4)->toDateString(),
            'proveedor' => 'KS Technology',
        ]);

        $laptop3 = ActivoFijo::create([
            'numero_activo' => 'AF-0003', 'tipo' => 'laptop', 'marca' => 'Lenovo',
            'modelo' => 'ThinkPad E15', 'serial' => 'LEN-E15-003',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $fin->id, 'valor_adquisicion' => 980.00,
            'fecha_adquisicion' => now()->subMonths(8)->toDateString(),
            'proveedor' => 'TechStore RD',
        ]);

        $monitor1 = ActivoFijo::create([
            'numero_activo' => 'AF-0004', 'tipo' => 'monitor', 'marca' => 'LG',
            'modelo' => '27BK550Y', 'serial' => 'LG-27BK-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 280.00,
            'fecha_adquisicion' => now()->subMonths(6)->toDateString(),
        ]);

        $servidor = ActivoFijo::create([
            'numero_activo' => 'AF-0005', 'tipo' => 'servidor', 'marca' => 'Dell',
            'modelo' => 'PowerEdge R750', 'serial' => 'SRV-DELL-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'ubicacion' => 'Data Center - Rack A1',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 8500.00,
            'fecha_adquisicion' => now()->subYear()->toDateString(),
        ]);

        $celular = ActivoFijo::create([
            'numero_activo' => 'AF-0006', 'tipo' => 'celular', 'marca' => 'Samsung',
            'modelo' => 'Galaxy A54', 'serial' => 'SAM-A54-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $fin->id, 'valor_adquisicion' => 350.00,
            'fecha_adquisicion' => now()->subMonths(3)->toDateString(),
        ]);

        // ── Activos Menores ───────────────────────────────────────────────────
        $mouse1 = ActivoMenor::create([
            'numero_activo' => 'AM-0001', 'tipo' => 'mouse', 'marca' => 'Logitech',
            'modelo' => 'MX Master 3', 'serial' => 'LOG-MX3-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 55.00,
        ]);

        $mouse2 = ActivoMenor::create([
            'numero_activo' => 'AM-0002', 'tipo' => 'mouse', 'marca' => 'Logitech',
            'modelo' => 'M240', 'serial' => 'LOG-M240-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 25.00,
        ]);

        $teclado1 = ActivoMenor::create([
            'numero_activo' => 'AM-0003', 'tipo' => 'teclado', 'marca' => 'Logitech',
            'modelo' => 'K380', 'serial' => 'LOG-K380-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 35.00,
        ]);

        $usb1 = ActivoMenor::create([
            'numero_activo' => 'AM-0004', 'tipo' => 'memoria_usb', 'marca' => 'Kingston',
            'modelo' => 'DataTraveler 64GB',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $fin->id, 'valor_adquisicion' => 12.00,
        ]);

        $auriculares = ActivoMenor::create([
            'numero_activo' => 'AM-0005', 'tipo' => 'auriculares', 'marca' => 'Jabra',
            'modelo' => 'Evolve 30', 'serial' => 'JAB-EV30-001',
            'condicion' => 'bueno', 'estado' => 'disponible',
            'departamento_id' => $ti->id, 'valor_adquisicion' => 80.00,
        ]);

        // ── Asignaciones ──────────────────────────────────────────────────────
        // Laptop 1 → María
        AsignacionFijo::create([
            'activo_fijo_id' => $laptop1->id, 'colaborador_id' => $maria->id,
            'fecha_asignacion' => now()->subMonths(5)->toDateString(),
            'condicion_entrega' => 'bueno', 'ubicacion' => 'Oficina TI - Piso 3',
            'estado' => 'activo',
        ]);
        $laptop1->update(['estado' => 'asignado', 'ubicacion' => 'Oficina TI - Piso 3']);

        // Laptop 2 → Carlos
        AsignacionFijo::create([
            'activo_fijo_id' => $laptop2->id, 'colaborador_id' => $carlos->id,
            'fecha_asignacion' => now()->subMonths(3)->toDateString(),
            'condicion_entrega' => 'bueno',
            'estado' => 'activo',
        ]);
        $laptop2->update(['estado' => 'asignado']);

        // Laptop 3 → Ana (historial: primero fue de Luis)
        AsignacionFijo::create([
            'activo_fijo_id' => $laptop3->id, 'colaborador_id' => $luis->id,
            'fecha_asignacion' => now()->subMonths(10)->toDateString(),
            'fecha_devolucion_real' => now()->subMonths(6)->toDateString(),
            'condicion_entrega' => 'bueno', 'condicion_devolucion' => 'bueno',
            'estado' => 'devuelto',
        ]);
        AsignacionFijo::create([
            'activo_fijo_id' => $laptop3->id, 'colaborador_id' => $ana->id,
            'fecha_asignacion' => now()->subMonths(5)->toDateString(),
            'condicion_entrega' => 'bueno',
            'estado' => 'activo',
        ]);
        $laptop3->update(['estado' => 'asignado']);

        // Celular → Luis
        AsignacionFijo::create([
            'activo_fijo_id' => $celular->id, 'colaborador_id' => $luis->id,
            'fecha_asignacion' => now()->subMonths(2)->toDateString(),
            'condicion_entrega' => 'bueno',
            'estado' => 'activo',
        ]);
        $celular->update(['estado' => 'asignado']);

        // Laptop 1 va a mantenimiento (se devuelve primero para que quede en historial)
        // Monitor → en mantenimiento
        Mantenimiento::create([
            'activo_fijo_id' => $monitor1->id,
            'fecha_entrada' => now()->subWeeks(2)->toDateString(),
            'tecnico_proveedor' => 'TechService RD',
            'tipo' => 'correctivo',
            'descripcion_problema' => 'Pantalla con parpadeo intermitente en el lado derecho.',
            'estado' => 'en_proceso',
        ]);
        $monitor1->update(['estado' => 'mantenimiento']);

        // Activos menores asignados
        AsignacionMenor::create([
            'activo_menor_id' => $mouse1->id, 'colaborador_id' => $maria->id,
            'fecha_asignacion' => now()->subMonths(5)->toDateString(),
            'condicion_entrega' => 'bueno', 'estado' => 'activo',
        ]);
        $mouse1->update(['estado' => 'asignado']);

        AsignacionMenor::create([
            'activo_menor_id' => $teclado1->id, 'colaborador_id' => $carlos->id,
            'fecha_asignacion' => now()->subMonths(3)->toDateString(),
            'condicion_entrega' => 'bueno', 'estado' => 'activo',
        ]);
        $teclado1->update(['estado' => 'asignado']);

        AsignacionMenor::create([
            'activo_menor_id' => $auriculares->id, 'colaborador_id' => $maria->id,
            'fecha_asignacion' => now()->subMonths(5)->toDateString(),
            'condicion_entrega' => 'bueno', 'estado' => 'activo',
        ]);
        $auriculares->update(['estado' => 'asignado']);
    }
}
