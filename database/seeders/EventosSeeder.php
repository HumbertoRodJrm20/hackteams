<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('eventos')->truncate();
        DB::table('criterio_evaluacion')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categoriaId = DB::table('categorias')->first()->id ?? null;

        $eventoId1 = DB::table('eventos')->insertGetId([
            'nombre' => 'Hackatón IoT para el Campus',
            'descripcion' => 'Desarrollo de soluciones conectadas para optimizar los recursos universitarios.',
            'fecha_inicio' => now()->addDays(5),
            'fecha_fin' => now()->addDays(7),
            'estado' => 'proximo',
            'max_equipos' => 20,
            'categoria_id' => $categoriaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventoId2 = DB::table('eventos')->insertGetId([
            'nombre' => 'Concurso de Diseño UX/UI',
            'descripcion' => 'Diseña la mejor interfaz para una aplicación móvil de servicios estudiantiles.',
            'fecha_inicio' => now()->subDays(10),
            'fecha_fin' => now()->subDays(3),
            'estado' => 'finalizado',
            'max_equipos' => 15,
            'categoria_id' => $categoriaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventoId3 = DB::table('eventos')->insertGetId([
            'nombre' => 'Desarrollo de Apps Móviles',
            'descripcion' => 'Crea una aplicación móvil innovadora que resuelva un problema real.',
            'fecha_inicio' => now()->subDays(2),
            'fecha_fin' => now()->addDays(5),
            'estado' => 'activo',
            'max_equipos' => 25,
            'categoria_id' => $categoriaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventoId4 = DB::table('eventos')->insertGetId([
            'nombre' => 'Inteligencia Artificial Challenge',
            'descripcion' => 'Desarrolla soluciones basadas en IA para resolver problemas complejos.',
            'fecha_inicio' => now()->addDays(15),
            'fecha_fin' => now()->addDays(17),
            'estado' => 'proximo',
            'max_equipos' => 18,
            'categoria_id' => $categoriaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventoId5 = DB::table('eventos')->insertGetId([
            'nombre' => 'Ciberseguridad Hackathon',
            'descripcion' => 'Participa en desafíos de seguridad informática y protección de datos.',
            'fecha_inicio' => now()->subDays(20),
            'fecha_fin' => now()->subDays(18),
            'estado' => 'finalizado',
            'max_equipos' => 12,
            'categoria_id' => $categoriaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $criterios = [
            ['evento_id' => $eventoId1, 'nombre' => 'Innovación', 'ponderacion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId1, 'nombre' => 'Viabilidad Técnica', 'ponderacion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId1, 'nombre' => 'Presentación y Pitch', 'ponderacion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId2, 'nombre' => 'Diseño Visual', 'ponderacion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId2, 'nombre' => 'Usabilidad', 'ponderacion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId2, 'nombre' => 'Creatividad', 'ponderacion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId3, 'nombre' => 'Funcionalidad', 'ponderacion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId3, 'nombre' => 'Diseño UX', 'ponderacion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['evento_id' => $eventoId3, 'nombre' => 'Innovación', 'ponderacion' => 30, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('criterio_evaluacion')->insert($criterios);
    }
}
