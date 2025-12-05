<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            if (!Schema::hasColumn('equipos', 'evento_id')) {
                $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            if (Schema::hasColumn('equipos', 'evento_id')) {
                $table->dropForeignKey(['evento_id']);
                $table->dropColumn('evento_id');
            }
        });
    }
};
