<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('user_rol')->truncate();
        DB::table('participantes')->truncate();

        // Asegurarse que la tabla pivote de eventos también esté limpia.
        DB::table('evento_participante')->truncate();
        //Asegurarse que la tabla constancias también esté limpia.
        DB::table('constancias')->truncate();

        // Obtener el ID del Rol Admin (asumiendo que RolesSeeder ya se ejecutó)
        $rolAdminId = DB::table('roles')->where('nombre', 'Admin')->first()->id;
        $rolParticipanteId = DB::table('roles')->where('nombre', 'Participante')->first()->id;
        $rolJuezId = DB::table('roles')->where('nombre', 'Juez')->first()->id;
        $carreraId = DB::table('carreras')->where('nombre', 'Ingeniería en Sistemas Computacionales')->first()->id;

        // --- PREPARACIÓN: OBTENER UN EVENTO DE PRUEBA ---
        $eventoDePruebaId = DB::table('eventos')->select('id')->first()->id ?? 1;

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

        //LÓGICA DE CONSTANCIAS
        // A. Registrar la participación en la tabla pivote 'evento_participante'
        DB::table('evento_participante')->insert([
            'evento_id' => $eventoDePruebaId,
            'participante_id' => $participanteUserId, // Usamos el ID del Usuario Participante
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // B. Crear el registro de la Constancia (simulando que el Admin ya la generó)
        DB::table('constancias')->insert([
            'participante_id' => $participanteUserId,
            'evento_id' => $eventoDePruebaId,
    
        //NOMBRES DE COLUMNA PARA LA DB:
        'tipo' => 'asistente',
        'archivo_path' => 'constancias/constancia-ejemplo.pdf',
        'codigo_qr' => \Illuminate\Support\Str::uuid(),
            
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

        // Registrar juez como participante para permitir constancias
DB::table('participantes')->insert([
    'user_id' => $juezUserId,
    'carrera_id' => $carreraId,
    'matricula' => 'JUEZ123',
    'created_at' => now(),
    'updated_at' => now(),
]);

DB::table('evento_participante')->insert([
    'evento_id' => $eventoDePruebaId,
    'participante_id' => $juezUserId,
    'created_at' => now(),
    'updated_at' => now(),
]);

DB::table('constancias')->insert([
    'participante_id' => $juezUserId,
    'evento_id' => $eventoDePruebaId,
    'tipo' => 'asistente',
    'archivo_path' => 'constancias/constancia-ejemplo.pdf',
    'codigo_qr' => Str::uuid(),
    'created_at' => now(),
    'updated_at' => now(),
]);

        // --- 4. Crear Segundo Usuario Juez ---
        $juez2UserId = DB::table('users')->insertGetId([
            'name' => 'Maria Rodriguez',
            'email' => 'maria.rodriguez@test.com',
            'password' => Hash::make('password'), // Contraseña: 'password'
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Asignar Rol Juez
        DB::table('user_rol')->insert([
            'user_id' => $juez2UserId,
            'rol_id' => $rolJuezId
        ]);

        // Registrar segundo juez como participante para permitir constancias
        DB::table('participantes')->insert([
            'user_id' => $juez2UserId,
            'carrera_id' => $carreraId,
            'matricula' => 'JUEZ456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('evento_participante')->insert([
            'evento_id' => $eventoDePruebaId,
            'participante_id' => $juez2UserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('constancias')->insert([
            'participante_id' => $juez2UserId,
            'evento_id' => $eventoDePruebaId,
            'tipo' => 'asistente',
            'archivo_path' => 'constancias/constancia-ejemplo.pdf',
            'codigo_qr' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}