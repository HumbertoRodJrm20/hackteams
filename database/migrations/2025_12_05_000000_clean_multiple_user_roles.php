<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migración limpia a los usuarios que tienen múltiples roles.
     * Para cada usuario que tiene múltiples roles, se mantiene solo el rol "primario":
     * - Si tiene rol 'Admin', se mantiene solo Admin
     * - Si tiene rol 'Juez', se mantiene solo Juez
     * - Si tiene rol 'Participante', se mantiene solo Participante
     */
    public function up(): void
    {
        // Encontrar todos los usuarios con múltiples roles
        $usersWithMultipleRoles = DB::table('user_rol')
            ->select('user_id', DB::raw('COUNT(*) as role_count'))
            ->groupBy('user_id')
            ->having('role_count', '>', 1)
            ->get();

        foreach ($usersWithMultipleRoles as $record) {
            $userId = $record->user_id;

            // Obtener los roles del usuario
            $roles = DB::table('user_rol')
                ->join('roles', 'user_rol.rol_id', '=', 'roles.id')
                ->where('user_id', $userId)
                ->pluck('roles.nombre')
                ->toArray();

            // Determinar el rol primario (en orden de prioridad)
            $primaryRole = 'Participante'; // default
            if (in_array('Admin', $roles)) {
                $primaryRole = 'Admin';
            } elseif (in_array('Juez', $roles)) {
                $primaryRole = 'Juez';
            }

            // Obtener el ID del rol primario
            $primaryRoleId = DB::table('roles')
                ->where('nombre', $primaryRole)
                ->first()?->id;

            if ($primaryRoleId) {
                // Eliminar todos los roles del usuario
                DB::table('user_rol')->where('user_id', $userId)->delete();

                // Asignar solo el rol primario
                DB::table('user_rol')->insert([
                    'user_id' => $userId,
                    'rol_id' => $primaryRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Si el nuevo rol NO es Participante, eliminar record de participante
                if ($primaryRole !== 'Participante') {
                    DB::table('participantes')->where('user_id', $userId)->delete();
                } else {
                    // Si ES Participante, asegurar que existe el record
                    $exists = DB::table('participantes')->where('user_id', $userId)->exists();
                    if (!$exists) {
                        DB::table('participantes')->insert([
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se puede revertir esta migración de manera confiable
        // Ya que perdemos información sobre los roles anteriores
    }
};
