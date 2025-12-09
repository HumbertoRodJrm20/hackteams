<?php

namespace Database\Seeders;

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

        $perfiles = DB::table('perfiles')->get();
        $perfilLiderId = $perfiles->where('nombre', 'Líder de Proyecto')->first()->id;
        $perfilBackendId = $perfiles->where('nombre', 'Programador Backend')->first()->id;
        $perfilFrontendId = $perfiles->where('nombre', 'Programador Frontend')->first()->id;
        $perfilDisenadorId = $perfiles->where('nombre', 'Diseñador UI/UX')->first()->id;

        $eventos = DB::table('eventos')->get();
        $participantes = DB::table('participantes')->get();
        $jueces = DB::table('users')
            ->join('user_rol', 'users.id', '=', 'user_rol.user_id')
            ->join('roles', 'user_rol.rol_id', '=', 'roles.id')
            ->where('roles.nombre', 'Juez')
            ->select('users.id')
            ->get();

        $equiposData = [
            [
                'nombre' => 'Team Alpha',
                'evento_id' => $eventos[0]->id,
                'participantes' => [0, 1, 2],
                'perfiles' => [$perfilLiderId, $perfilBackendId, $perfilFrontendId],
                'proyecto' => [
                    'titulo' => 'App de monitoreo energético',
                    'resumen' => 'Aplicación web para visualizar el consumo energético en tiempo real del campus.',
                    'link_repositorio' => 'https://github.com/team-alpha/monitoreo',
                    'estado' => 'en_desarrollo',
                ],
            ],
            [
                'nombre' => 'Innovators Hub',
                'evento_id' => $eventos[0]->id,
                'participantes' => [3, 4, 5, 6],
                'perfiles' => [$perfilLiderId, $perfilBackendId, $perfilFrontendId, $perfilDisenadorId],
                'proyecto' => [
                    'titulo' => 'Sistema de sensores IoT para aulas',
                    'resumen' => 'Red de sensores para monitorear temperatura, humedad y ocupación de las aulas.',
                    'link_repositorio' => 'https://github.com/innovators/iot-aulas',
                    'estado' => 'en_desarrollo',
                ],
            ],
            [
                'nombre' => 'Code Warriors',
                'evento_id' => $eventos[2]->id,
                'participantes' => [7, 8, 9],
                'perfiles' => [$perfilLiderId, $perfilBackendId, $perfilFrontendId],
                'proyecto' => [
                    'titulo' => 'App móvil de gestión de tareas estudiantiles',
                    'resumen' => 'Aplicación para organizar tareas, horarios y eventos académicos.',
                    'link_repositorio' => 'https://github.com/codewarriors/taskapp',
                    'estado' => 'en_desarrollo',
                ],
            ],
            [
                'nombre' => 'Tech Pioneers',
                'evento_id' => $eventos[2]->id,
                'participantes' => [10, 11, 12, 13],
                'perfiles' => [$perfilLiderId, $perfilBackendId, $perfilFrontendId, $perfilDisenadorId],
                'proyecto' => [
                    'titulo' => 'Plataforma de intercambio de libros',
                    'resumen' => 'App móvil para facilitar el préstamo e intercambio de libros entre estudiantes.',
                    'link_repositorio' => 'https://github.com/techpioneers/bookshare',
                    'estado' => 'terminado',
                ],
            ],
            [
                'nombre' => 'Digital Minds',
                'evento_id' => $eventos[1]->id,
                'participantes' => [14, 15, 16],
                'perfiles' => [$perfilLiderId, $perfilDisenadorId, $perfilFrontendId],
                'proyecto' => [
                    'titulo' => 'Rediseño de portal estudiantil',
                    'resumen' => 'Nueva interfaz intuitiva para el sistema de servicios estudiantiles.',
                    'link_repositorio' => 'https://github.com/digitalminds/portal-ui',
                    'estado' => 'calificado',
                ],
            ],
        ];

        foreach ($equiposData as $index => $equipoData) {
            $equipoId = DB::table('equipos')->insertGetId([
                'nombre' => $equipoData['nombre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($equipoData['participantes'] as $idx => $participanteIndex) {
                if ($participanteIndex < count($participantes)) {
                    DB::table('equipo_participante')->insert([
                        'equipo_id' => $equipoId,
                        'participante_id' => $participantes[$participanteIndex]->user_id,
                        'perfil_id' => $equipoData['perfiles'][$idx] ?? $perfilBackendId,
                        'es_lider' => $idx === 0,
                    ]);
                }
            }

            $proyectoId = DB::table('proyectos')->insertGetId([
                'equipo_id' => $equipoId,
                'evento_id' => $equipoData['evento_id'],
                'titulo' => $equipoData['proyecto']['titulo'],
                'resumen' => $equipoData['proyecto']['resumen'],
                'link_repositorio' => $equipoData['proyecto']['link_repositorio'],
                'estado' => $equipoData['proyecto']['estado'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $avancesCount = rand(2, 4);
            for ($i = 0; $i < $avancesCount; $i++) {
                DB::table('avances')->insert([
                    'proyecto_id' => $proyectoId,
                    'descripcion' => 'Avance '.($i + 1).': Progreso en el desarrollo del proyecto.',
                    'fecha' => now()->subDays(($avancesCount - $i) * 3),
                ]);
            }

            $criterios = DB::table('criterio_evaluacion')
                ->where('evento_id', $equipoData['evento_id'])
                ->get();

            foreach ($criterios as $criterio) {
                foreach ($jueces->take(2) as $juez) {
                    DB::table('calificaciones')->insert([
                        'proyecto_id' => $proyectoId,
                        'juez_user_id' => $juez->id,
                        'criterio_id' => $criterio->id,
                        'puntuacion' => rand(70, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
