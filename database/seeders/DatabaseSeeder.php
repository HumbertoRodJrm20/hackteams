<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactiva las claves foráneas temporalmente para permitir TRUNCATE
        Schema::disableForeignKeyConstraints();

        // 1. Seeders de Catálogo (Sin dependencias complejas)
        $this->call(RolesSeeder::class);
        $this->call(CarrerasPerfilesSeeder::class);
        $this->call(UsersSeeder::class); // Debe ir antes de Participantes

        // 2. Seeder de Datos Transaccionales Iniciales
        $this->call(EventosSeeder::class);
        $this->call(DatosPruebaSeeder::class); // Equipos, Proyectos, Calificaciones

        // Reactiva las claves foráneas
        Schema::enableForeignKeyConstraints();
    }
}