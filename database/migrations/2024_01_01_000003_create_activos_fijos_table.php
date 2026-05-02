<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activos_fijos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_activo')->unique();
            $table->enum('tipo', [
                'laptop',
                'desktop',
                'monitor',
                'celular',
                'tablet',
                'impresora',
                'servidor',
                'equipo_red',
                'ups',
                'otro',
            ]);
            $table->string('marca');
            $table->string('modelo');
            $table->string('serial')->nullable()->unique();
            $table->text('descripcion')->nullable();
            $table->enum('condicion', ['bueno', 'regular', 'danado'])->default('bueno');
            $table->enum('estado', [
                'disponible',
                'asignado',
                'mantenimiento',
                'baja',
                'robado_perdido',
                'bodega',
            ])->default('disponible');
            $table->string('ubicacion')->nullable();
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->nullOnDelete();
            $table->decimal('valor_adquisicion', 15, 2)->nullable();
            $table->date('fecha_adquisicion')->nullable();
            $table->string('proveedor')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activos_fijos');
    }
};
