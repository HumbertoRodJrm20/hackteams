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
       Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID autoincremental, clave primaria

            // Campos para identificar al usuario
            $table->string('name');
            $table->string('email')->unique();
            
            // Campo de contraseña (es vital para el controlador de login que hice)
            $table->string('password'); 

            // Campo para definir el rol del usuario (ADMIN, JUEZ, ESTUDIANTE)
            // Usamos enum para asegurar que solo se puedan usar estos 3 valores.
            $table->enum('role', ['admin', 'juez', 'estudiante'])->default('estudiante');

            // Campos adicionales de Laravel (opcionales)
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps(); // Crea los campos created_at y updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
