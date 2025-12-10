<?php

namespace App\Http\Controllers;

use App\Mail\InvitacionEquipoMail;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\SolicitudEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SolicitudEquipoController extends Controller
{
    /**
     * Participante se une directamente a un equipo público
     */
    public function solicitar(Request $request, Equipo $equipo)
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar que el participante existe
        if (! $participante) {
            return redirect()->back()->with('error', 'Debes ser un participante para unirte a un equipo.');
        }

        // Verificar que el equipo es público
        if (! $equipo->es_publico) {
            return redirect()->back()->with('error', 'Este equipo no acepta nuevos miembros.');
        }

        // Verificar que no es miembro del equipo
        if ($equipo->participantes()->where('participante_id', $participante->user_id)->exists()) {
            return redirect()->back()->with('error', 'Ya eres miembro de este equipo.');
        }

        // Verificar si ya está en otro equipo del mismo evento
        if ($equipo->evento_id && Equipo::participanteTieneEquipoEnEvento($participante->user_id, $equipo->evento_id)) {
            return redirect()->back()->with('error', 'Ya estás en otro equipo de este evento. Solo puedes estar en un equipo por evento.');
        }

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return redirect()->back()->with('error', 'No puedes unirte a un equipo después de que el evento haya iniciado.');
        }

        // Agregar directamente al equipo
        $equipo->participantes()->attach($participante->user_id, [
            'perfil_id' => null,
            'es_lider' => false,
        ]);

        return redirect()->route('equipos.show', $equipo->id)
            ->with('success', '¡Te has unido al equipo "'.$equipo->nombre.'" exitosamente!');
    }

    /**
     * Líder invita a un participante a su equipo
     */
    public function invitar(Request $request, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        $esLider = $equipo->participantes()
            ->where('participante_id', $user->id)
            ->wherePivot('es_lider', true)
            ->exists();

        if (! $esLider) {
            return redirect()->back()->with('error', 'No tienes permisos para invitar participantes a este equipo.');
        }

        // Validar datos
        $request->validate([
            'participante_id' => 'required|exists:participantes,user_id',
            'mensaje' => 'nullable|string|max:500',
        ]);

        $participanteId = $request->participante_id;

        // Verificar que no es miembro del equipo
        if ($equipo->participantes()->where('participante_id', $participanteId)->exists()) {
            return redirect()->back()->with('error', 'Este participante ya es miembro del equipo.');
        }

        // Verificar que no tiene una invitación pendiente
        $invitacionExistente = SolicitudEquipo::where('equipo_id', $equipo->id)
            ->where('participante_id', $participanteId)
            ->where('estado', 'pendiente')
            ->where('tipo', 'invitacion')
            ->first();

        if ($invitacionExistente) {
            return redirect()->back()->with('error', 'Este participante ya tiene una invitación pendiente.');
        }

        // Crear la invitación
        $invitacion = SolicitudEquipo::create([
            'equipo_id' => $equipo->id,
            'participante_id' => $participanteId,
            'tipo' => 'invitacion',
            'estado' => 'pendiente',
            'mensaje' => $request->mensaje,
        ]);

        // Obtener el participante invitado
        $participante = Participante::where('user_id', $participanteId)->first();

        // Enviar email al invitado
        if ($participante && $participante->user->email) {
            Mail::to($participante->user->email)->send(new InvitacionEquipoMail($invitacion));
        }

        return redirect()->back()->with('success', 'Invitación enviada exitosamente.');
    }

    /**
     * Mostrar solicitudes pendientes para el líder (participantes que quieren unirse)
     */
    public function misSolicitudes()
    {
        $user = Auth::user();

        // Obtener equipos donde el usuario es líder
        $equiposLiderados = Equipo::whereHas('participantes', function ($query) use ($user) {
            $query->where('participante_id', $user->id)
                ->where('es_lider', true);
        })->pluck('id');

        // Obtener solicitudes pendientes para esos equipos
        $solicitudes = SolicitudEquipo::with(['equipo', 'participante.user'])
            ->whereIn('equipo_id', $equiposLiderados)
            ->where('tipo', 'solicitud')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('equipos.solicitudes', compact('solicitudes'));
    }

    /**
     * Mostrar invitaciones pendientes para el participante (líder lo invitó)
     */
    public function misInvitaciones()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (! $participante) {
            return redirect()->route('home')->with('error', 'No tienes un perfil de participante.');
        }

        // Obtener invitaciones pendientes
        $invitaciones = SolicitudEquipo::with(['equipo.participantes.user'])
            ->where('participante_id', $participante->user_id)
            ->where('tipo', 'invitacion')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('equipos.invitaciones', compact('invitaciones'));
    }

    /**
     * Líder acepta una solicitud de participante
     */
    public function aceptarSolicitud(SolicitudEquipo $solicitud)
    {
        $user = Auth::user();

        // Verificar que es el líder del equipo
        $esLider = $solicitud->equipo->participantes()
            ->where('participante_id', $user->id)
            ->wherePivot('es_lider', true)
            ->exists();

        if (! $esLider) {
            return redirect()->back()->with('error', 'No tienes permisos para aceptar esta solicitud.');
        }

        // Verificar que la solicitud está pendiente
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Agregar al participante al equipo
        $solicitud->equipo->participantes()->attach($solicitud->participante_id, [
            'rol' => 'Miembro',
            'es_lider' => false,
        ]);

        // Actualizar estado de la solicitud
        $solicitud->update(['estado' => 'aceptada']);

        return redirect()->back()->with('success', 'Solicitud aceptada. El participante ahora es miembro del equipo.');
    }

    /**
     * Líder rechaza una solicitud de participante
     */
    public function rechazarSolicitud(SolicitudEquipo $solicitud)
    {
        $user = Auth::user();

        // Verificar que es el líder del equipo
        $esLider = $solicitud->equipo->participantes()
            ->where('participante_id', $user->id)
            ->wherePivot('es_lider', true)
            ->exists();

        if (! $esLider) {
            return redirect()->back()->with('error', 'No tienes permisos para rechazar esta solicitud.');
        }

        // Verificar que la solicitud está pendiente
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Actualizar estado de la solicitud
        $solicitud->update(['estado' => 'rechazada']);

        return redirect()->back()->with('success', 'Solicitud rechazada.');
    }

    /**
     * Participante acepta una invitación del líder
     */
    public function aceptarInvitacion(SolicitudEquipo $invitacion)
    {
        $user = Auth::user();

        // Verificar que la invitación es para este usuario
        if ($invitacion->participante_id !== $user->id) {
            return redirect()->back()->with('error', 'Esta invitación no es para ti.');
        }

        // Verificar que la invitación está pendiente
        if ($invitacion->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta invitación ya fue procesada.');
        }

        // Agregar al participante al equipo
        $invitacion->equipo->participantes()->attach($invitacion->participante_id, [
            'rol' => 'Miembro',
            'es_lider' => false,
        ]);

        // Actualizar estado de la invitación
        $invitacion->update(['estado' => 'aceptada']);

        return redirect()->route('equipos.show', $invitacion->equipo_id)
            ->with('success', '¡Te has unido al equipo exitosamente!');
    }

    /**
     * Participante rechaza una invitación del líder
     */
    public function rechazarInvitacion(SolicitudEquipo $invitacion)
    {
        $user = Auth::user();

        // Verificar que la invitación es para este usuario
        if ($invitacion->participante_id !== $user->id) {
            return redirect()->back()->with('error', 'Esta invitación no es para ti.');
        }

        // Verificar que la invitación está pendiente
        if ($invitacion->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta invitación ya fue procesada.');
        }

        // Actualizar estado de la invitación
        $invitacion->update(['estado' => 'rechazada']);

        return redirect()->back()->with('success', 'Invitación rechazada.');
    }
}
