<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('equipos')->truncate();
        DB::table('equipo_participante')->truncate();
        DB::table('proyectos')->truncate();
        DB::table('avances')->truncate();
        DB::table('calificaciones')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $participanteId = DB::table('participantes')->first()->user_id;
        $perfilLiderId = DB::table('perfiles')->where('nombre', 'Líder de Proyecto')->first()->id;
        $perfilProgId = DB::table('perfiles')->where('nombre', 'Programador Backend')->first()->id;
        $eventoId = DB::table('eventos')->first()->id;
        $criterioId = DB::table('criterio_evaluacion')->first()->id;
        $juezUserId = DB::table('users')->where('email', '!=', 'admin@innovatec.com')->first()->id; // Usar el participante como juez simulado

        // --- 1. Crear Equipo de Prueba ---
        $equipoId = DB::table('equipos')->insertGetId([
            'nombre' => 'Team Alpha',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- 2. Asignar Participante al Equipo ---
        DB::table('equipo_participante')->insert([
            'equipo_id' => $equipoId,
            'participante_id' => $participanteId,
            'perfil_id' => $perfilLiderId,
            'es_lider' => true,
        ]);

        // --- 3. Crear Proyecto para el Equipo ---
        $proyectoId = DB::table('proyectos')->insertGetId([
            'equipo_id' => $equipoId,
            'evento_id' => $eventoId,
            'titulo' => 'App de monitoreo energético V1',
            'resumen' => 'Aplicación web para visualizar el consumo energético en tiempo real del campus.',
            'link_repositorio' => 'https://github.com/team-alpha/monitoreo',
            'estado' => 'en_desarrollo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- 4. Crear Avance de Prueba ---
        DB::table('avances')->insert([
            'proyecto_id' => $proyectoId,
            'descripcion' => 'Se completó la estructura del backend y la conexión inicial a la base de datos.',
            'fecha' => now()->subDays(2),
        ]);

        // --- 5. Crear Calificación de Prueba (Simulada) ---
        DB::table('calificaciones')->insert([
            'proyecto_id' => $proyectoId,
            'juez_user_id' => $juezUserId,
            'criterio_id' => $criterioId,
            'puntuacion' => 85,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
