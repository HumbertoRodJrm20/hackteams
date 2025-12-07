<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clean-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia usuarios con múltiples roles, dejando solo un rol por usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de roles de usuario...');

        // Encontrar todos los usuarios con múltiples roles
        $usersWithMultipleRoles = DB::table('user_rol')
            ->select('user_id', DB::raw('COUNT(*) as role_count'))
            ->groupBy('user_id')
            ->having('role_count', '>', 1)
            ->get();

        if ($usersWithMultipleRoles->isEmpty()) {
            $this->info('✓ Todos los usuarios tienen solo un rol. No hay nada que limpiar.');

            return;
        }

        $this->line('Se encontraron '.$usersWithMultipleRoles->count().' usuario(s) con múltiples roles.');

        foreach ($usersWithMultipleRoles as $record) {
            $userId = $record->user_id;
            $user = DB::table('users')->find($userId);

            // Obtener los roles del usuario
            $roles = DB::table('user_rol')
                ->join('roles', 'user_rol.rol_id', '=', 'roles.id')
                ->where('user_id', $userId)
                ->pluck('roles.nombre')
                ->toArray();

            // Determinar el rol primario
            $primaryRole = 'Participante';
            if (in_array('Admin', $roles)) {
                $primaryRole = 'Admin';
            } elseif (in_array('Juez', $roles)) {
                $primaryRole = 'Juez';
            }

            $this->line("Usuario ID {$userId} ({$user->nombre}): Rol anterior [".implode(', ', $roles)."] → Nuevo rol: [{$primaryRole}]");

            // Obtener el ID del rol primario
            $primaryRoleId = DB::table('roles')
                ->where('nombre', $primaryRole)
                ->first()?->id;

            if ($primaryRoleId) {
                // Eliminar todos los roles
                DB::table('user_rol')->where('user_id', $userId)->delete();

                // Asignar solo el rol primario
                DB::table('user_rol')->insert([
                    'user_id' => $userId,
                    'rol_id' => $primaryRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Ajustar tabla participantes
                if ($primaryRole !== 'Participante') {
                    DB::table('participantes')->where('user_id', $userId)->delete();
                } else {
                    $exists = DB::table('participantes')->where('user_id', $userId)->exists();
                    if (! $exists) {
                        DB::table('participantes')->insert([
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->info('✓ Limpieza completada exitosamente.');
    }
}
