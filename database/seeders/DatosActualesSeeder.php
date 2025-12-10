<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosActualesSeeder extends Seeder
{
    public function run(): void
    {
        $this->info('Cargando datos actuales desde la base de datos...');

        $this->seedRoles();
        $this->seedCarreras();
        $this->seedPerfiles();
        $this->seedCategorias();
        $this->seedEventos();
        $this->seedCriterios();
        $this->seedUsers();
        $this->seedParticipantes();
        $this->seedEventoParticipante();
        $this->seedEquipos();
        $this->seedEquipoParticipante();
        $this->seedProyectos();
        $this->seedAvances();
        $this->seedCalificaciones();
        $this->seedProyectoJuez();

        $this->info('Datos cargados exitosamente!');
    }

    protected function info($message)
    {
        echo $message."\n";
    }

    protected function seedRoles()
    {
        $data = DB::connection()->table('roles')->get()->toArray();
        if (! empty($data)) {
            DB::table('roles')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedCarreras()
    {
        $data = DB::connection()->table('carreras')->get()->toArray();
        if (! empty($data)) {
            DB::table('carreras')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedPerfiles()
    {
        $data = DB::connection()->table('perfiles')->get()->toArray();
        if (! empty($data)) {
            DB::table('perfiles')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedCategorias()
    {
        $data = DB::connection()->table('categorias')->get()->toArray();
        if (! empty($data)) {
            DB::table('categorias')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedEventos()
    {
        $data = DB::connection()->table('eventos')->get()->toArray();
        if (! empty($data)) {
            DB::table('eventos')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedCriterios()
    {
        $data = DB::connection()->table('criterio_evaluacion')->get()->toArray();
        if (! empty($data)) {
            DB::table('criterio_evaluacion')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedUsers()
    {
        $data = DB::connection()->table('users')->get()->toArray();
        if (! empty($data)) {
            DB::table('users')->insert(json_decode(json_encode($data), true));
        }

        $dataRoles = DB::connection()->table('user_rol')->get()->toArray();
        if (! empty($dataRoles)) {
            DB::table('user_rol')->insert(json_decode(json_encode($dataRoles), true));
        }
    }

    protected function seedParticipantes()
    {
        $data = DB::connection()->table('participantes')->get()->toArray();
        if (! empty($data)) {
            DB::table('participantes')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedEventoParticipante()
    {
        $data = DB::connection()->table('evento_participante')->get()->toArray();
        if (! empty($data)) {
            DB::table('evento_participante')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedEquipos()
    {
        $data = DB::connection()->table('equipos')->get()->toArray();
        if (! empty($data)) {
            DB::table('equipos')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedEquipoParticipante()
    {
        $data = DB::connection()->table('equipo_participante')->get()->toArray();
        if (! empty($data)) {
            DB::table('equipo_participante')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedProyectos()
    {
        $data = DB::connection()->table('proyectos')->get()->toArray();
        if (! empty($data)) {
            DB::table('proyectos')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedAvances()
    {
        $data = DB::connection()->table('avances')->get()->toArray();
        if (! empty($data)) {
            DB::table('avances')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedCalificaciones()
    {
        $data = DB::connection()->table('calificaciones')->get()->toArray();
        if (! empty($data)) {
            DB::table('calificaciones')->insert(json_decode(json_encode($data), true));
        }
    }

    protected function seedProyectoJuez()
    {
        $data = DB::connection()->table('proyecto_juez')->get()->toArray();
        if (! empty($data)) {
            DB::table('proyecto_juez')->insert(json_decode(json_encode($data), true));
        }
    }
}
