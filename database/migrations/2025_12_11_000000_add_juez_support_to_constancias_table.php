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
        Schema::table('constancias', function (Blueprint $table) {
            // Hacer participante_id nullable para que una constancia pueda ser de un participante O de un juez
            $table->foreignId('participante_id')->nullable()->change();

            // Agregar campo para jueces
            $table->foreignId('juez_user_id')->nullable()->after('participante_id')->constrained('users')->onDelete('cascade');

            // Agregar índice para mejorar búsquedas
            $table->index('juez_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            $table->dropForeign(['juez_user_id']);
            $table->dropIndex(['juez_user_id']);
            $table->dropColumn('juez_user_id');

            // Revertir participante_id a no nullable
            $table->foreignId('participante_id')->nullable(false)->change();
        });
    }
};
