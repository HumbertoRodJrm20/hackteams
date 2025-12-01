<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migración completa el sistema de gestión de eventos con:
     * - Relación N:M entre eventos y participantes
     * - Relaciones en modelo Proyecto (avances y calificaciones)
     * - Conversión de fecha en avances a datetime para Carbon compatibility
     */
    public function up(): void
    {
        // 1. Crear tabla evento_participante para la relación N:M
        if (!Schema::hasTable('evento_participante')) {
            Schema::create('evento_participante', function (Blueprint $table) {
                $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
                $table->foreignId('participante_id')->constrained('participantes', 'user_id')->onDelete('cascade');
                $table->primary(['evento_id', 'participante_id']);
                $table->timestamps();
            });
        }

        // 2. Asegurar que la tabla avances tiene la estructura correcta
        if (!Schema::hasTable('avances')) {
            Schema::create('avances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
                $table->text('descripcion');
                $table->dateTime('fecha'); // datetime para Carbon compatibility
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 3. Asegurar que la tabla calificaciones tiene la estructura correcta
        if (!Schema::hasTable('calificaciones')) {
            Schema::create('calificaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
                $table->foreignId('juez_user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('criterio_id')->constrained('criterio_evaluacion')->onDelete('cascade');
                $table->integer('puntuacion');
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['proyecto_id', 'juez_user_id', 'criterio_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_participante');
        Schema::dropIfExists('avances');
        Schema::dropIfExists('calificaciones');
    }
};
