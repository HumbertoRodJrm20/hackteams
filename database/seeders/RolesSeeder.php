<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->truncate(); // Limpia la tabla antes de insertar

        $roles = [
            ['nombre' => 'Admin', 'descripcion' => 'Administrador del sistema'],
            ['nombre' => 'Juez', 'descripcion' => 'Encargado de calificar proyectos'],
            ['nombre' => 'Participante', 'descripcion' => 'Miembro de un equipo'],
        ];

        DB::table('roles')->insert($roles);
    }
}
