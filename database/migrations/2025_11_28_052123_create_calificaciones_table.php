<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('juez_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('criterio_id')->nullable()->constrained('criterio_evaluacion')->onDelete('cascade');
            $table->integer('puntuacion');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['proyecto_id', 'juez_user_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
