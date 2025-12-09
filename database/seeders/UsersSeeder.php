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
    }
}
