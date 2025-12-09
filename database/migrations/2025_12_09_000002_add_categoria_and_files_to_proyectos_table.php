<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            if (!Schema::hasColumn('proyectos', 'categoria_id')) {
                $table->foreignId('categoria_id')->nullable()->after('evento_id')->constrained('categorias')->onDelete('set null');
            }
            if (!Schema::hasColumn('proyectos', 'imagen_path')) {
                $table->string('imagen_path')->nullable()->after('link_repositorio');
            }
            if (!Schema::hasColumn('proyectos', 'documento_path')) {
                $table->string('documento_path')->nullable()->after('imagen_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn(['categoria_id', 'imagen_path', 'documento_path']);
        });
    }
};
