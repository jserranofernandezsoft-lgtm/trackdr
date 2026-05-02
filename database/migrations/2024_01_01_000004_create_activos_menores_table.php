<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activos_menores', function (Blueprint $table) {
            $table->id();
            $table->string('numero_activo')->unique();
            $table->enum('tipo', [
                'mouse',
                'teclado',
                'memoria_ram',
                'disco_duro',
                'memoria_usb',
                'webcam',
                'auriculares',
                'cargador',
                'adaptador',
                'parlante',
                'otro',
            ]);
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('serial')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('condicion', ['bueno', 'regular', 'danado'])->default('bueno');
            $table->enum('estado', [
                'disponible',
                'asignado',
                'baja',
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
        Schema::dropIfExists('activos_menores');
    }
};
