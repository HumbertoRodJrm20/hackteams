<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla pivot para asignar proyectos a jueces para evaluar.
     * Un proyecto puede ser asignado a múltiples jueces para que lo evalúen.
     */
    public function up(): void
    {
        Schema::create('proyecto_juez', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('juez_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('asignado_en')->useCurrent();
            $table->timestamps();

            // Asegurar que no haya duplicados: un juez no puede ser asignado dos veces al mismo proyecto
            $table->unique(['proyecto_id', 'juez_user_id']);

            $table->index('juez_user_id');
            $table->index('proyecto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_juez');
    }
};
