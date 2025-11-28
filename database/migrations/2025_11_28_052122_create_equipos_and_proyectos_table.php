<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('logo_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla Pivote: equipo_participante (N:M con asignación de Perfil)
        Schema::create('equipo_participante', function (Blueprint $table) {
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('participantes', 'user_id')->onDelete('cascade');
            $table->foreignId('perfil_id')->nullable()->constrained('perfiles')->onDelete('set null');
            $table->boolean('es_lider')->default(false);
            $table->primary(['equipo_id', 'participante_id']);
            $table->timestamps();
        });

        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('resumen');
            $table->string('link_repositorio')->nullable();
            $table->enum('estado', ['pendiente', 'en_desarrollo', 'terminado', 'calificado'])->default('pendiente');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('equipo_participante');
        Schema::dropIfExists('equipos');
    }
};
