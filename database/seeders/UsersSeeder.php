<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('user_rol')->truncate();
        DB::table('participantes')->truncate();

        // Obtener el ID del Rol Admin (asumiendo que RolesSeeder ya se ejecutó)
        $rolAdminId = DB::table('roles')->where('nombre', 'Admin')->first()->id;
        $rolParticipanteId = DB::table('roles')->where('nombre', 'Participante')->first()->id;
        $carreraId = DB::table('carreras')->where('nombre', 'Ingeniería en Sistemas Computacionales')->first()->id;

        // --- 1. Crear Usuario Administrador ---
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Admin Innovatec',
            'email' => 'admin@innovatec.com',
            'password' => Hash::make('password'), // Contraseña: 'password'
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Asignar Rol Admin
        DB::table('user_rol')->insert([
            'user_id' => $adminUserId,
            'rol_id' => $rolAdminId
        ]);

        // --- 2. Crear Usuario Participante de Prueba ---
        $participanteUserId = DB::table('users')->insertGetId([
            'name' => 'Juan Perez',
            'email' => 'juan.perez@test.com',
            'password' => Hash::make('password'), // Contraseña: 'password'
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Asignar Rol Participante
        DB::table('user_rol')->insert([
            'user_id' => $participanteUserId,
            'rol_id' => $rolParticipanteId
        ]);
        // Crear registro en la tabla 'participantes'
        DB::table('participantes')->insert([
            'user_id' => $participanteUserId,
            'carrera_id' => $carreraId,
            'matricula' => 'A12345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Crear registro de Juez
        $juezUserId = DB::table('users')->insertGetId([
            'name' => 'Angel Zarate',
            'email' => 'angelz@test.com',
            'password' => Hash::make('password'), // Contraseña: 'password'
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Asignar Rol Juez
        DB::table('user_rol')->insert([
            'user_id' => $juezUserId,
            'rol_id' => DB::table('roles')->where('nombre', 'Juez')->first()->id
        ]);
        
    }
}