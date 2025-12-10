<?php

namespace App\Http\Controllers;

use App\Mail\InvitacionEquipoMail;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        if (! $participante) {
            return view('ListaEquipos', ['equipos' => collect()]);
        }

        // Obtener los equipos del participante
        $equipos = Equipo::whereHas('participantes', function ($query) use ($participante) {
            $query->where('participantes.user_id', $participante->user_id);
        })
            ->with('participantes', 'proyectos.evento', 'evento')
            ->get()
            ->map(function ($equipo) {
                return [
                    'id' => $equipo->id,
                    'nombre' => $equipo->nombre,
                    'logo_path' => $equipo->logo_path,
                    'es_publico' => $equipo->es_publico,
                    'proyecto' => $equipo->proyectoActual()?->titulo ?? 'Sin proyecto',
                    'evento' => $equipo->evento?->nombre ?? ($equipo->proyectoActual()?->evento?->nombre ?? 'N/A'),
                    'miembros' => $equipo->contarMiembros(),
                    'estado' => $equipo->proyectoActual()?->estado ?? 'pendiente',
                ];
            });

        return view('ListaEquipos', ['equipos' => $equipos]);
    }

    /**
     * Muestra el formulario para registrar un nuevo equipo.
     */
    public function create()
    {
        // Obtener eventos activos o próximos que NO hayan iniciado
        $eventos = \App\Models\Evento::whereIn('estado', ['activo', 'proximo'])
            ->where('fecha_inicio', '>', now())
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('RegistrarEquipo', compact('eventos'));
    }

    /**
     * Guarda un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        \Log::info('=== INICIO CREACIÓN DE EQUIPO ===');
        \Log::info('Request data:', $request->all());

        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            \Log::warning('Usuario no es participante');

            return redirect()->back()
                ->with('error', 'Debes estar registrado como participante para crear un equipo.');
        }

        \Log::info('Participante ID: '.$participante->user_id);

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|unique:equipos|max:255',
                'evento_id' => 'required|exists:eventos,id',
                'es_publico' => 'required|boolean',
                'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            \Log::info('Validación exitosa:', $validated);

            // Verificar si el evento ya inició
            $evento = \App\Models\Evento::find($validated['evento_id']);
            if ($evento && now()->gte($evento->fecha_inicio)) {
                \Log::warning('El evento ya ha iniciado');

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No puedes crear un equipo después de que el evento haya iniciado.');
            }

            // Verificar si el participante ya está en un equipo del mismo evento
            if (Equipo::participanteTieneEquipoEnEvento($participante->user_id, $validated['evento_id'])) {
                \Log::warning('Participante ya está en un equipo del mismo evento');

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ya estás en un equipo de este evento. Solo puedes estar en un equipo por evento.');
            }

            $equipo = new Equipo;
            $equipo->nombre = $validated['nombre'];
            $equipo->evento_id = $validated['evento_id'];
            $equipo->es_publico = $validated['es_publico'];

            if ($request->hasFile('logo_path')) {
                $path = $request->file('logo_path')->store('equipos', 'public');
                $equipo->logo_path = $path;
                \Log::info('Logo guardado en: '.$path);
            }

            $equipo->save();
            \Log::info('Equipo guardado con ID: '.$equipo->id);

            // Obtener el perfil "Líder de Proyecto" como rol por defecto
            $perfilLider = \App\Models\Perfil::where('nombre', 'Líder de Proyecto')->first();

            // Crear relación del usuario actual como líder del equipo con rol por defecto
            $equipo->participantes()->attach($participante->user_id, [
                'perfil_id' => $perfilLider?->id,
                'es_lider' => true,
            ]);
            \Log::info('Participante agregado como líder con perfil: '.($perfilLider?->nombre ?? 'Sin perfil'));

            $tipoEquipo = $equipo->es_publico ? 'público' : 'privado';
            \Log::info('=== FIN CREACIÓN DE EQUIPO EXITOSA ===');

            return redirect()->route('equipos.index')
                ->with('success', "Equipo \"{$equipo->nombre}\" ({$tipoEquipo}) creado exitosamente. Eres el líder del equipo.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación:', $e->errors());

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al crear equipo: '.$e->getMessage());
            \Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Error al crear equipo: '.$e->getMessage())
                ->withInput();
        }
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
        if (! $isMember) {
            return redirect()->route('equipos.index')
                ->with('error', 'No tienes acceso a este equipo.');
        }

        // Verificar si es líder
        $isLeader = $equipo->participantes()
            ->where('participantes.user_id', $participante->user_id)
            ->wherePivot('es_lider', true)
            ->exists();

        // Obtener todos los perfiles disponibles
        $perfiles = \App\Models\Perfil::all();

        // Obtener miembros del equipo con información completa
        $miembros = $equipo->participantes()
            ->with('user')
            ->get()
            ->map(function ($participante) use ($equipo) {
                $pivotData = $equipo->participantes()
                    ->where('participantes.user_id', $participante->user_id)
                    ->first()
                    ->pivot;

                $perfil = null;
                if ($pivotData->perfil_id) {
                    $perfil = \App\Models\Perfil::find($pivotData->perfil_id);
                }

                return [
                    'id' => $participante->user_id,
                    'nombre' => $participante->user->name,
                    'email' => $participante->user->email,
                    'es_lider' => $pivotData->es_lider,
                    'perfil_id' => $pivotData->perfil_id,
                    'perfil_nombre' => $perfil?->nombre,
                ];
            });

        // Obtener proyecto del equipo
        $proyecto = $equipo->proyectoActual();

        // Verificar si el evento ya inició
        $eventoIniciado = $equipo->evento && now()->gte($equipo->evento->fecha_inicio);

        return view('DetalleEquipo', compact('equipo', 'miembros', 'isLeader', 'proyecto', 'perfiles', 'eventoIniciado'));
    }

    /**
     * Invita a un participante al equipo (solo para líderes).
     */
    public function invite(Request $request, Equipo $equipo)
    {
        $this->checkLeadership($equipo);

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return back()->with('error', 'No puedes invitar miembros después de que el evento haya iniciado.');
        }

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $userToInvite = \App\Models\User::where('email', $validated['email'])->first();
        $participante = $userToInvite->participante;

        if (! $participante) {
            return back()->with('error', 'El usuario no está registrado como participante.');
        }

        // Verificar si ya está en el equipo
        if ($equipo->participantes()->where('participantes.user_id', $participante->user_id)->exists()) {
            return back()->with('info', 'Este participante ya está en el equipo.');
        }

        // Verificar si ya está en otro equipo del mismo evento
        if ($equipo->evento_id && Equipo::participanteTieneEquipoEnEvento($participante->user_id, $equipo->evento_id)) {
            return back()->with('error', 'Este participante ya está en otro equipo del mismo evento. Solo puede estar en un equipo por evento.');
        }

        // Verificar que no tiene una invitación pendiente
        $invitacionExistente = \App\Models\SolicitudEquipo::where('equipo_id', $equipo->id)
            ->where('participante_id', $participante->user_id)
            ->where('estado', 'pendiente')
            ->where('tipo', 'invitacion')
            ->first();

        if ($invitacionExistente) {
            return back()->with('error', 'Este participante ya tiene una invitación pendiente.');
        }

        // Crear la invitación
        $invitacion = \App\Models\SolicitudEquipo::create([
            'equipo_id' => $equipo->id,
            'participante_id' => $participante->user_id,
            'tipo' => 'invitacion',
            'estado' => 'pendiente',
            'mensaje' => null,
        ]);

        // Enviar correo de invitación
        try {
            Mail::to($userToInvite->email)->send(
                new InvitacionEquipoMail($invitacion)
            );
        } catch (\Exception $e) {
            \Log::error('Error al enviar correo de invitación: '.$e->getMessage());
            // Continuar aunque falle el correo
        }

        return back()->with('success', 'Invitación enviada a '.$userToInvite->name.'. Debe aceptarla para unirse al equipo.');
    }

    /**
     * Remueve un miembro del equipo (solo para líderes).
     */
    public function removeMember(Equipo $equipo, $participanteId)
    {
        $this->checkLeadership($equipo);

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return back()->with('error', 'No puedes remover miembros después de que el evento haya iniciado.');
        }

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
            'perfil_id' => 'nullable|exists:perfiles,id',
        ]);

        $equipo->participantes()->updateExistingPivot($participanteId, [
            'perfil_id' => $validated['perfil_id'],
        ]);

        $perfilNombre = $validated['perfil_id']
            ? \App\Models\Perfil::find($validated['perfil_id'])->nombre
            : 'Sin rol';

        return back()->with('success', "El rol ha sido actualizado a: {$perfilNombre}");
    }

    /**
     * Muestra equipos públicos disponibles para unirse.
     */
    public function equiposPublicos()
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return view('EquiposPublicos', ['equiposPorEvento' => collect()]);
        }

        // Obtener todos los perfiles disponibles
        $perfiles = \App\Models\Perfil::all();

        // Obtener eventos activos
        $eventos = \App\Models\Evento::whereIn('estado', ['activo', 'proximo'])
            ->with(['equipos' => function ($query) {
                $query->where('es_publico', true)->with('participantes.user');
            }])
            ->get();

        $equiposPorEvento = $eventos->filter(function ($evento) {
            return $evento->equipos->isNotEmpty();
        })->map(function ($evento) use ($participante, $perfiles) {
            // Verificar si el participante ya está en un equipo de este evento
            $yaEnEquipo = Equipo::participanteTieneEquipoEnEvento($participante->user_id, $evento->id);

            // Verificar si el evento ya inició
            $eventoIniciado = now()->gte($evento->fecha_inicio);

            return [
                'evento' => $evento,
                'eventoIniciado' => $eventoIniciado,
                'equipos' => $evento->equipos->map(function ($equipo) use ($participante, $perfiles) {
                    // Obtener los IDs de perfiles ya ocupados en este equipo
                    $perfilesOcupados = \DB::table('equipo_participante')
                        ->where('equipo_id', $equipo->id)
                        ->whereNotNull('perfil_id')
                        ->pluck('perfil_id')
                        ->toArray();

                    // Calcular perfiles disponibles
                    $perfilesDisponibles = $perfiles->filter(function ($perfil) use ($perfilesOcupados) {
                        return ! in_array($perfil->id, $perfilesOcupados);
                    });

                    return [
                        'id' => $equipo->id,
                        'nombre' => $equipo->nombre,
                        'logo_path' => $equipo->logo_path,
                        'miembros' => $equipo->contarMiembros(),
                        'participantes' => $equipo->participantes,
                        'yaSoyMiembro' => $equipo->participantes->contains('user_id', $participante->user_id),
                        'perfiles_disponibles' => $perfilesDisponibles->count(),
                    ];
                }),
                'yaEnEquipo' => $yaEnEquipo,
            ];
        });

        return view('EquiposPublicos', compact('equiposPorEvento'));
    }

    /**
     * Permite a un participante unirse a un equipo público.
     */
    public function unirse(Equipo $equipo)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->back()
                ->with('error', 'Debes estar registrado como participante para unirte a un equipo.');
        }

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return redirect()->back()
                ->with('error', 'No puedes unirte a un equipo después de que el evento haya iniciado.');
        }

        // Verificar que el equipo sea público
        if (! $equipo->es_publico) {
            return redirect()->back()
                ->with('error', 'Este equipo es privado. Solo puedes unirte por invitación.');
        }

        // Verificar si ya está en un equipo del mismo evento
        if (Equipo::participanteTieneEquipoEnEvento($participante->user_id, $equipo->evento_id)) {
            return redirect()->back()
                ->with('error', 'Ya estás en un equipo de este evento. Solo puedes estar en un equipo por evento.');
        }

        // Verificar si ya es miembro
        if ($equipo->participantes()->where('participantes.user_id', $participante->user_id)->exists()) {
            return redirect()->back()
                ->with('info', 'Ya eres miembro de este equipo.');
        }

        // Agregar al equipo
        $equipo->participantes()->attach($participante->user_id, [
            'perfil_id' => null,
            'es_lider' => false,
        ]);

        return redirect()->route('equipos.show', $equipo->id)
            ->with('success', 'Te has unido exitosamente al equipo "'.$equipo->nombre.'".');
    }

    /**
     * Permite a un miembro salirse del equipo.
     */
    public function leave(Equipo $equipo)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->back()
                ->with('error', 'Debes estar registrado como participante.');
        }

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return redirect()->back()
                ->with('error', 'No puedes salir del equipo después de que el evento haya iniciado.');
        }

        // Verificar si es miembro del equipo
        $isMember = $equipo->participantes()->where('participantes.user_id', $participante->user_id)->exists();
        if (! $isMember) {
            return redirect()->route('equipos.index')
                ->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar si es líder
        $isLeader = $equipo->participantes()
            ->where('participantes.user_id', $participante->user_id)
            ->wherePivot('es_lider', true)
            ->exists();

        if ($isLeader) {
            // Si es líder, verificar si hay otros miembros
            $totalMiembros = $equipo->participantes()->count();
            if ($totalMiembros > 1) {
                return redirect()->back()
                    ->with('error', 'Como líder, debes remover a todos los miembros antes de salir del equipo, o eliminar el equipo directamente.');
            }
        }

        // Remover al participante del equipo
        $equipo->participantes()->detach($participante->user_id);

        return redirect()->route('equipos.index')
            ->with('success', 'Has salido del equipo "'.$equipo->nombre.'" exitosamente.');
    }

    /**
     * Elimina el equipo completo (solo para líderes).
     */
    public function destroy(Equipo $equipo)
    {
        $this->checkLeadership($equipo);

        // Verificar si el evento ya inició
        if ($equipo->evento && now()->gte($equipo->evento->fecha_inicio)) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar el equipo después de que el evento haya iniciado.');
        }

        $nombreEquipo = $equipo->nombre;

        // Eliminar el equipo (soft delete)
        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', "El equipo \"{$nombreEquipo}\" ha sido eliminado exitosamente.");
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

        if (! $isLeader) {
            abort(403, 'Solo el líder del equipo puede realizar esta acción.');
        }
    }
}
