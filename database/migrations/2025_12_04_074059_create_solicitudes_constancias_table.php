<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_constancias', function (Blueprint $table) {
            $table->id();

            // Relación al participante (usuario)
            $table->unsignedBigInteger('participante_id');

            // Relación al evento
            $table->unsignedBigInteger('evento_id');

            $table->string('rol');                           // Participante, Ponente, Tallerista...
            $table->date('fecha_evento');                    // Fecha real del evento
            $table->string('tipo');                          // Tipo de constancia
            $table->string('motivo')->nullable();            // ¿Para qué se solicita?
            $table->text('comentario')->nullable();          // Comentarios adicionales
            $table->string('evidencia_path')->nullable();    // Archivo subido
            $table->string('estatus')->default('Pendiente'); // Pendiente, Aprobado, Rechazado

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('participante_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_constancias');
    }
};
