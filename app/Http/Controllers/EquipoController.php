<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    /**
     * Muestra la lista de todos los equipos del usuario actual con sus proyectos y miembros.
     */
    public function index()
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Si el usuario no es participante, mostrar equipos vacío
        if (!$participante) {
            return view('ListaEquipos', ['equipos' => collect()]);
        }

        // Obtener los equipos del participante
        $equipos = Equipo::whereHas('participantes', function ($query) use ($participante) {
            $query->where('participantes.user_id', $participante->user_id);
        })
        ->with('participantes', 'proyectos.evento')
        ->get()
        ->map(function ($equipo) {
            return [
                'id' => $equipo->id,
                'nombre' => $equipo->nombre,
                'logo_path' => $equipo->logo_path,
                'proyecto' => $equipo->proyectoActual()?->titulo ?? 'Sin proyecto',
                'evento' => $equipo->proyectoActual()?->evento?->nombre ?? 'N/A',
                'miembros' => $equipo->contarMiembros(),
                'estado' => $equipo->proyectoActual()?->estado ?? 'pendiente'
            ];
        });

        return view('ListaEquipos', ['equipos' => $equipos]);
    }

    /**
     * Muestra el formulario para registrar un nuevo equipo.
     */
    public function create()
    {
        return view('RegistrarEquipo');
    }

    /**
     * Guarda un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:equipos|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $equipo = new Equipo();
        $equipo->nombre = $validated['nombre'];

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('equipos', 'public');
            $equipo->logo_path = $path;
        }

        $equipo->save();

        // Crear relación del usuario actual como líder del equipo
        $user = Auth::user();
        $participante = $user->participante;

        if ($participante) {
            $equipo->participantes()->attach($participante->user_id, [
                'perfil_id' => null, // Se puede actualizar después
                'es_lider' => true
            ]);
        }

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo "' . $equipo->nombre . '" creado exitosamente. Eres el líder del equipo.');
    }

    /**
     * Muestra los detalles de un equipo específico con gestión de miembros.
     */
    public function show(Equipo $equipo)
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Verificar si el usuario es miembro del equipo
        $isMember = $equipo->participantes()->where('participantes.user_id', $participante->user_id)->exists();
        if (!$isMember) {
            return redirect()->route('equipos.index')
                ->with('error', 'No tienes acceso a este equipo.');
        }

        // Verificar si es líder
        $isLeader = $equipo->participantes()
            ->where('participantes.user_id', $participante->user_id)
            ->wherePivot('es_lider', true)
            ->exists();

        // Obtener miembros del equipo con información completa
        $miembros = $equipo->participantes()
            ->with('user')
            ->get()
            ->map(function ($participante) use ($equipo) {
                $pivotData = $equipo->participantes()
                    ->where('participantes.user_id', $participante->user_id)
                    ->first()
                    ->pivot;
                return [
                    'id' => $participante->user_id,
                    'nombre' => $participante->user->name,
                    'email' => $participante->user->email,
                    'es_lider' => $pivotData->es_lider,
                    'perfil' => $pivotData->perfil_id // Se implementará después
                ];
            });

        // Obtener proyecto del equipo
        $proyecto = $equipo->proyectoActual();

        return view('DetalleEquipo', compact('equipo', 'miembros', 'isLeader', 'proyecto'));
    }

    /**
     * Invita a un participante al equipo (solo para líderes).
     */
    public function invite(Request $request, Equipo $equipo)
    {
        $this->checkLeadership($equipo);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $userToInvite = \App\Models\User::where('email', $validated['email'])->first();
        $participante = $userToInvite->participante;

        if (!$participante) {
            return back()->with('error', 'El usuario no está registrado como participante.');
        }

        // Verificar si ya está en el equipo
        if ($equipo->participantes()->where('participantes.user_id', $participante->user_id)->exists()) {
            return back()->with('info', 'Este participante ya está en el equipo.');
        }

        // Agregar al equipo
        $equipo->participantes()->attach($participante->user_id, [
            'perfil_id' => null,
            'es_lider' => false
        ]);

        return back()->with('success', 'Se ha invitado a ' . $userToInvite->name . ' al equipo.');
    }

    /**
     * Remueve un miembro del equipo (solo para líderes).
     */
    public function removeMember(Equipo $equipo, $participanteId)
    {
        $this->checkLeadership($equipo);

        $user = Auth::user();

        // Evitar que se elimine a sí mismo
        if ($participanteId == $user->participante->user_id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo del equipo.');
        }

        $equipo->participantes()->detach($participanteId);

        return back()->with('success', 'El miembro ha sido removido del equipo.');
    }

    /**
     * Actualiza el rol de un miembro del equipo (solo para líderes).
     */
    public function updateMemberRole(Request $request, Equipo $equipo, $participanteId)
    {
        $this->checkLeadership($equipo);

        $validated = $request->validate([
            'perfil_id' => 'nullable|exists:perfiles,id'
        ]);

        $equipo->participantes()
            ->wherePivot('participante_id', $participanteId)
            ->updateExistingPivot($participanteId, [
                'perfil_id' => $validated['perfil_id']
            ]);

        return back()->with('success', 'El rol ha sido actualizado.');
    }

    /**
     * Verifica si el usuario actual es líder del equipo.
     */
    private function checkLeadership(Equipo $equipo)
    {
        $user = Auth::user();
        $participante = $user->participante;

        $isLeader = $equipo->participantes()
            ->where('participantes.user_id', $participante->user_id)
            ->wherePivot('es_lider', true)
            ->exists();

        if (!$isLeader) {
            abort(403, 'Solo el líder del equipo puede realizar esta acción.');
        }
    }
}
