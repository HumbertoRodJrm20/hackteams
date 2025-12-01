<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_participante', function (Blueprint $table) {
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('participantes', 'user_id')->onDelete('cascade');
            $table->primary(['evento_id', 'participante_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_participante');
    }
};
