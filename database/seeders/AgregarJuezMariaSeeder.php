<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgregarJuezMariaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs necesarios
        $rolJuezId = DB::table('roles')->where('nombre', 'Juez')->first()->id;
        $carreraId = DB::table('carreras')->where('nombre', 'Ingeniería en Sistemas Computacionales')->first()->id;
        $eventoDePruebaId = DB::table('eventos')->select('id')->first()->id ?? 1;

        // Verificar si el juez ya existe
        $existeJuez = DB::table('users')->where('email', 'maria.rodriguez@test.com')->exists();

        if ($existeJuez) {
            echo "⚠️  El usuario maria.rodriguez@test.com ya existe. No se creó duplicado.\n";
            return;
        }

        // Crear Usuario Juez
        $juezUserId = DB::table('users')->insertGetId([
            'name' => 'Maria Rodriguez',
            'email' => 'maria.rodriguez@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar Rol Juez
        DB::table('user_rol')->insert([
            'user_id' => $juezUserId,
            'rol_id' => $rolJuezId
        ]);

        // Registrar como participante para constancias
        DB::table('participantes')->insert([
            'user_id' => $juezUserId,
            'carrera_id' => $carreraId,
            'matricula' => 'JUEZ456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Registrar participación en evento
        DB::table('evento_participante')->insert([
            'evento_id' => $eventoDePruebaId,
            'participante_id' => $juezUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear constancia
        DB::table('constancias')->insert([
            'participante_id' => $juezUserId,
            'evento_id' => $eventoDePruebaId,
            'tipo' => 'asistente',
            'archivo_path' => 'constancias/constancia-ejemplo.pdf',
            'codigo_qr' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Usuario juez 'Maria Rodriguez' creado exitosamente!\n";
        echo "📧 Email: maria.rodriguez@test.com\n";
        echo "🔑 Contraseña: password\n";
    }
}
