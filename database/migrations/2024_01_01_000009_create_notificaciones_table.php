<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('tipo');
            $table->string('icono');
            $table->string('titulo');
            $table->text('mensaje')->nullable();
            $table->string('url')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();
            $table->index(['usuario_id', 'leida']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
