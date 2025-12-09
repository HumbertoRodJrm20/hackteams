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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('users')->truncate();
        DB::table('user_rol')->truncate();
        DB::table('participantes')->truncate();
        DB::table('evento_participante')->truncate();
        DB::table('constancias')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rolAdminId = DB::table('roles')->where('nombre', 'Admin')->first()->id;
        $rolParticipanteId = DB::table('roles')->where('nombre', 'Participante')->first()->id;
        $rolJuezId = DB::table('roles')->where('nombre', 'Juez')->first()->id;
        $carreraId = DB::table('carreras')->where('nombre', 'Ingeniería en Sistemas Computacionales')->first()->id;

        $eventoDePruebaId = DB::table('eventos')->select('id')->first()->id ?? 1;
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Admin Innovatec',
            'email' => 'admin@innovatec.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_rol')->insert([
            'user_id' => $adminUserId,
            'rol_id' => $rolAdminId,
        ]);

        $participanteUserId = DB::table('users')->insertGetId([
            'name' => 'Juan Perez',
            'email' => 'juan.perez@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_rol')->insert([
            'user_id' => $participanteUserId,
            'rol_id' => $rolParticipanteId,
        ]);

        DB::table('participantes')->insert([
            'user_id' => $participanteUserId,
            'carrera_id' => $carreraId,
            'matricula' => 'A12345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('evento_participante')->insert([
            'evento_id' => $eventoDePruebaId,
            'participante_id' => $participanteUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('constancias')->insert([
            'participante_id' => $participanteUserId,
            'evento_id' => $eventoDePruebaId,
            'tipo' => 'participacion',
            'archivo_path' => 'constancias/constancia-ejemplo.pdf',
            'codigo_qr' => \Illuminate\Support\Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $juezUserId = DB::table('users')->insertGetId([
            'name' => 'Angel Zarate',
            'email' => 'angelz@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_rol')->insert([
            'user_id' => $juezUserId,
            'rol_id' => DB::table('roles')->where('nombre', 'Juez')->first()->id,
        ]);

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
            'tipo' => 'participacion',
            'archivo_path' => 'constancias/constancia-ejemplo.pdf',
            'codigo_qr' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $juez2UserId = DB::table('users')->insertGetId([
            'name' => 'Maria Rodriguez',
            'email' => 'maria.rodriguez@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_rol')->insert([
            'user_id' => $juez2UserId,
            'rol_id' => $rolJuezId,
        ]);

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
            'tipo' => 'participacion',
            'archivo_path' => 'constancias/constancia-ejemplo.pdf',
            'codigo_qr' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $participantesData = [
            ['name' => 'Carlos Mendez', 'email' => 'carlos.mendez@test.com', 'matricula' => 'A00123456'],
            ['name' => 'Sofia Rodriguez', 'email' => 'sofia.rodriguez@test.com', 'matricula' => 'A00123457'],
            ['name' => 'Miguel Torres', 'email' => 'miguel.torres@test.com', 'matricula' => 'A00123458'],
            ['name' => 'Laura Gomez', 'email' => 'laura.gomez@test.com', 'matricula' => 'A00123459'],
            ['name' => 'Diego Martinez', 'email' => 'diego.martinez@test.com', 'matricula' => 'A00123460'],
            ['name' => 'Ana Hernandez', 'email' => 'ana.hernandez@test.com', 'matricula' => 'A00123461'],
            ['name' => 'Roberto Sanchez', 'email' => 'roberto.sanchez@test.com', 'matricula' => 'A00123462'],
            ['name' => 'Patricia Lopez', 'email' => 'patricia.lopez@test.com', 'matricula' => 'A00123463'],
            ['name' => 'Fernando Diaz', 'email' => 'fernando.diaz@test.com', 'matricula' => 'A00123464'],
            ['name' => 'Gabriela Ramirez', 'email' => 'gabriela.ramirez@test.com', 'matricula' => 'A00123465'],
            ['name' => 'Luis Morales', 'email' => 'luis.morales@test.com', 'matricula' => 'A00123466'],
            ['name' => 'Carmen Flores', 'email' => 'carmen.flores@test.com', 'matricula' => 'A00123467'],
            ['name' => 'Jorge Castro', 'email' => 'jorge.castro@test.com', 'matricula' => 'A00123468'],
            ['name' => 'Monica Vargas', 'email' => 'monica.vargas@test.com', 'matricula' => 'A00123469'],
            ['name' => 'Ricardo Ortiz', 'email' => 'ricardo.ortiz@test.com', 'matricula' => 'A00123470'],
        ];

        foreach ($participantesData as $participanteData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $participanteData['name'],
                'email' => $participanteData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_rol')->insert([
                'user_id' => $userId,
                'rol_id' => $rolParticipanteId,
            ]);

            DB::table('participantes')->insert([
                'user_id' => $userId,
                'carrera_id' => $carreraId,
                'matricula' => $participanteData['matricula'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $juecesData = [
            ['name' => 'Dr. Roberto Silva', 'email' => 'roberto.silva@juez.com'],
            ['name' => 'Dra. Ana Martinez', 'email' => 'ana.martinez@juez.com'],
            ['name' => 'Ing. Pedro Gonzalez', 'email' => 'pedro.gonzalez@juez.com'],
        ];

        foreach ($juecesData as $juezData) {
            $juezId = DB::table('users')->insertGetId([
                'name' => $juezData['name'],
                'email' => $juezData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_rol')->insert([
                'user_id' => $juezId,
                'rol_id' => $rolJuezId,
            ]);
        }
    }
}
