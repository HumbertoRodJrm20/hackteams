<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrerasPerfilesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('carreras')->truncate();
        DB::table('perfiles')->truncate();

        $carreras = [
            ['nombre' => 'Ingeniería en Sistemas Computacionales'],
            ['nombre' => 'Ingeniería en Gestión Empresarial'],
            ['nombre' => 'Arquitectura'],
            ['nombre' => 'Licenciatura en Administración'],
        ];

        $perfiles = [
            ['nombre' => 'Líder de Proyecto'],
            ['nombre' => 'Programador Backend'],
            ['nombre' => 'Programador Frontend'],
            ['nombre' => 'Diseñador UI/UX'],
            ['nombre' => 'Tester de Calidad'],
        ];

        DB::table('carreras')->insert($carreras);
        DB::table('perfiles')->insert($perfiles);
    }
}
