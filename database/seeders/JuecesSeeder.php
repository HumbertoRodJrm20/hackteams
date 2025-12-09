<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JuecesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener el rol de Juez
        $rolJuez = Rol::where('nombre', 'Juez')->first();

        if (! $rolJuez) {
            $this->command->error('El rol "Juez" no existe. Ejecuta primero el seeder de roles.');

            return;
        }

        // Datos de los 5 nuevos jueces
        $jueces = [
            [
                'name' => 'Dr. Miguel Ángel Hernández',
                'email' => 'miguel.hernandez@juez.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Dra. Carmen Patricia López',
                'email' => 'carmen.lopez@juez.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Ing. Roberto Carlos Mendoza',
                'email' => 'roberto.mendoza@juez.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Dra. Ana María Gutiérrez',
                'email' => 'ana.gutierrez@juez.com',
                'password' => 'password123',
            ],
            [
                'name' => 'Mtro. José Luis Ramírez',
                'email' => 'jose.ramirez@juez.com',
                'password' => 'password123',
            ],
        ];

        foreach ($jueces as $juezData) {
            // Verificar si el juez ya existe
            $existingUser = User::where('email', $juezData['email'])->first();

            if ($existingUser) {
                $this->command->warn("El usuario {$juezData['email']} ya existe. Saltando...");

                continue;
            }

            // Crear el usuario
            $user = User::create([
                'name' => $juezData['name'],
                'email' => $juezData['email'],
                'password' => Hash::make($juezData['password']),
                'email_verified_at' => now(), // Verificado automáticamente
            ]);

            // Asignar rol de Juez
            $user->roles()->attach($rolJuez->id);

            $this->command->info("Juez creado: {$juezData['name']} ({$juezData['email']})");
        }

        $this->command->info('5 jueces creados exitosamente.');
        $this->command->info('Contraseña para todos: password123');
    }
}
