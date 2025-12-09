<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('participantes', 'user_id')->onDelete('cascade');

            // Tipo: 'solicitud' (participante quiere unirse) o 'invitacion' (líder invita)
            $table->enum('tipo', ['solicitud', 'invitacion']);

            // Estado: 'pendiente', 'aceptada', 'rechazada'
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');

            // Mensaje opcional
            $table->text('mensaje')->nullable();

            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['equipo_id', 'estado']);
            $table->index(['participante_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_equipo');
    }
};
