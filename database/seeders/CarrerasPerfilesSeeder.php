<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrerasPerfilesSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar temporalmente las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('carreras')->truncate();
        DB::table('perfiles')->truncate();

        $carreras = [
            ['nombre' => 'Contador Público'],
            ['nombre' => 'Licenciatura en Administración'],
            ['nombre' => 'Ingeniería Química'],
            ['nombre' => 'Ingeniería Mecánica'],
            ['nombre' => 'Ingeniería Industrial'],
            ['nombre' => 'Ingeniería en Sistemas Computacionales'],
            ['nombre' => 'Ingeniería en Gestión Empresarial'],
            ['nombre' => 'Ingeniería Electrónica'],
            ['nombre' => 'Ingeniería Eléctrica'],
            ['nombre' => 'Ingeniería Civil'],
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

        // Reactivar las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
