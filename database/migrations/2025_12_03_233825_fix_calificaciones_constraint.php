<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calificaciones', function (Blueprint $table) {
            // Primero, eliminar la constrainta única anterior si existe
            $table->dropUnique('calificaciones_proyecto_id_juez_user_id_criterio_id_unique');
        });

        Schema::table('calificaciones', function (Blueprint $table) {
            // Hacer criterio_id nullable si no lo es
            $table->foreignId('criterio_id')->nullable()->change();

            // Agregar la nueva constrainta única (solo proyecto_id y juez_user_id)
            $table->unique(['proyecto_id', 'juez_user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('calificaciones', function (Blueprint $table) {
            // Revertir cambios
            $table->dropUnique('calificaciones_proyecto_id_juez_user_id_unique');
            $table->foreignId('criterio_id')->nullable(false)->change();
            $table->unique(['proyecto_id', 'juez_user_id', 'criterio_id']);
        });
    }
};
