<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('usuario_nombre');         // guardamos el nombre por si se elimina el usuario
            $table->string('accion');                 // 'crear', 'editar', 'eliminar', 'asignar', etc.
            $table->string('modulo');                 // 'activo_fijo', 'activo_menor', 'colaborador', etc.
            $table->string('entidad_id')->nullable(); // ID del registro afectado
            $table->string('entidad_label');          // Descripción legible: "AF-0001 Dell Latitude"
            $table->text('detalle')->nullable();      // Info adicional
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_log');
    }
};
