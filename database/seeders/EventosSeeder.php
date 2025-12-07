<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('eventos')->truncate();
        DB::table('criterio_evaluacion')->truncate();

        // --- 1. Crear Eventos de Prueba ---
        $eventoId1 = DB::table('eventos')->insertGetId([
            'nombre' => 'Hackatón IoT para el Campus',
            'descripcion' => 'Desarrollo de soluciones conectadas para optimizar los recursos universitarios.',
            'fecha_inicio' => now()->addDays(5),
            'fecha_fin' => now()->addDays(7),
            'estado' => 'proximo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventoId2 = DB::table('eventos')->insertGetId([
            'nombre' => 'Concurso de Diseño UX/UI',
            'descripcion' => 'Diseña la mejor interfaz para una aplicación móvil de servicios estudiantiles.',
            'fecha_inicio' => now()->subDays(10),
            'fecha_fin' => now()->subDays(3),
            'estado' => 'finalizado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- 2. Crear Criterios de Evaluación para el primer Evento ---
        $criterios = [
            ['evento_id' => $eventoId1, 'nombre' => 'Innovación', 'ponderacion' => 30],
            ['evento_id' => $eventoId1, 'nombre' => 'Viabilidad Técnica', 'ponderacion' => 40],
            ['evento_id' => $eventoId1, 'nombre' => 'Presentación y Pitch', 'ponderacion' => 30],
        ];

        DB::table('criterio_evaluacion')->insert($criterios);
    }
}
