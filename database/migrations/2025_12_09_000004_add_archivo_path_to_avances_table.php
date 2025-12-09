<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            if (!Schema::hasColumn('avances', 'archivo_path')) {
                $table->string('archivo_path')->nullable()->after('descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            $table->dropColumn('archivo_path');
        });
    }
};
