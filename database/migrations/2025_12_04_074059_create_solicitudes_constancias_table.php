<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('solicitudes_constancias', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('participante_id');
        $table->unsignedBigInteger('evento_id');

        $table->string('rol')->nullable();
        $table->date('fecha_evento')->nullable();
        $table->string('tipo'); // asistencia/participación/ponente etc.
        $table->string('motivo')->nullable();
        
        $table->string('evidencia_path')->nullable();

        $table->text('datos_personalizados')->nullable(); // JSON o texto libre
        $table->text('comentario')->nullable();

        $table->string('estatus')->default('pendiente'); // pendiente/aprobado/rechazado
        $table->text('respuesta_admin')->nullable();

        $table->unsignedBigInteger('admin_id')->nullable();

        $table->timestamps();

        // Relaciones
        $table->foreign('participante_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_constancias');
    }
};
