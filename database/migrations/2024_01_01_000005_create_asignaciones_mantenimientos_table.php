<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Asignaciones de activos fijos
        Schema::create('asignaciones_fijos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activos_fijos')->onDelete('restrict');
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('restrict');
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion_estimada')->nullable();
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('condicion_entrega', ['bueno', 'regular', 'danado'])->default('bueno');
            $table->enum('condicion_devolucion', ['bueno', 'regular', 'danado'])->nullable();
            $table->string('ubicacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['activo', 'devuelto'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        // Asignaciones de activos menores
        Schema::create('asignaciones_menores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_menor_id')->constrained('activos_menores')->onDelete('restrict');
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('restrict');
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('condicion_entrega', ['bueno', 'regular', 'danado'])->default('bueno');
            $table->enum('condicion_devolucion', ['bueno', 'regular', 'danado'])->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['activo', 'devuelto'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        // Mantenimientos (solo activos fijos)
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activos_fijos')->onDelete('restrict');
            $table->date('fecha_entrada');
            $table->date('fecha_salida')->nullable();
            $table->string('tecnico_proveedor')->nullable();
            $table->enum('tipo', ['preventivo', 'correctivo', 'garantia'])->default('correctivo');
            $table->text('descripcion_problema')->nullable();
            $table->text('descripcion_solucion')->nullable();
            $table->decimal('costo', 15, 2)->nullable();
            $table->enum('estado', ['en_proceso', 'completado', 'cancelado'])->default('en_proceso');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
        Schema::dropIfExists('asignaciones_menores');
        Schema::dropIfExists('asignaciones_fijos');
    }
};
