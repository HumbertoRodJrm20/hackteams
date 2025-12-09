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
        Schema::disableForeignKeyConstraints();

        $this->call(RolesSeeder::class);
        $this->call(CarrerasPerfilesSeeder::class);
        $this->call(EventosSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(DatosPruebaSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
