<?php

namespace App\View\Composers;

use App\Models\SolicitudConstancia;
use App\Models\SolicitudEquipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (! $user) {
            $view->with([
                'solicitudesEquipoPendientes' => 0,
                'invitacionesPendientes' => 0,
                'solicitudesConstanciaPendientes' => 0,
            ]);

            return;
        }

        // Contadores para Participantes
        $solicitudesEquipoPendientes = 0;
        $invitacionesPendientes = 0;

        if ($user->hasRole('Participante') && $user->participante) {
            $participanteId = $user->participante->id;

            // Contar solicitudes que el usuario ha enviado y están pendientes
            $solicitudesEquipoPendientes = SolicitudEquipo::where('participante_id', $participanteId)
                ->where('tipo', 'solicitud')
                ->where('estado', 'pendiente')
                ->count();

            // Contar invitaciones que el usuario ha recibido y están pendientes
            $invitacionesPendientes = SolicitudEquipo::where('participante_id', $participanteId)
                ->where('tipo', 'invitacion')
                ->where('estado', 'pendiente')
                ->count();
        }

        // Contador para Admin
        $solicitudesConstanciaPendientes = 0;

        if ($user->hasRole('Admin')) {
            $solicitudesConstanciaPendientes = SolicitudConstancia::where('estatus', 'pendiente')
                ->count();
        }

        $view->with([
            'solicitudesEquipoPendientes' => $solicitudesEquipoPendientes,
            'invitacionesPendientes' => $invitacionesPendientes,
            'solicitudesConstanciaPendientes' => $solicitudesConstanciaPendientes,
        ]);
    }
}
