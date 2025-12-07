<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participantes', function (Blueprint $table) {
            // Clave foránea a users (también actúa como clave primaria, 1:1)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->onDelete('set null');
            $table->string('matricula')->unique()->nullable();
            $table->primary('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
