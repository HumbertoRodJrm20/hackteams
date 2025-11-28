<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Cambia 'return new class' por el nombre de la clase:
class CreateConstanciasTable extends Migration
{
    public function up(): void
    {
        Schema::create('constancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_id')->constrained('participantes', 'user_id')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->enum('tipo', ['asistente', 'ganador', 'ponente']);
            $table->string('archivo_path');
            $table->string('codigo_qr')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('constancias');
    }
};
