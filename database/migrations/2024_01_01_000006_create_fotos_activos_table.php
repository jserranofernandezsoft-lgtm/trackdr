<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotos_activos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo_tipo', 20);   // 'fijo' o 'menor'
            $table->unsignedBigInteger('modelo_id');
            $table->string('ruta');               // path relativo en storage
            $table->string('nombre_original')->nullable();
            $table->integer('orden')->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->index(['modelo_tipo', 'modelo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos_activos');
    }
};
