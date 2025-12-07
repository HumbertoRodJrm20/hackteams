<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $table = 'equipos';

    protected $fillable = ['nombre', 'logo_path', 'evento_id', 'es_publico'];

    // Relación con evento (N:1)
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Relación con participantes (N:M a través de equipo_participante)
    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'equipo_participante', 'equipo_id', 'participante_id')
            ->withPivot('perfil_id', 'es_lider')
            ->withTimestamps();
    }

    // Relación con proyectos (1:N)
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'equipo_id');
    }

    // Obtener el proyecto actual (si existe)
    public function proyectoActual()
    {
        return $this->proyectos()->first();
    }

    // Contar miembros del equipo
    public function contarMiembros()
    {
        return $this->participantes()->count();
    }

    // Verificar si un participante ya está en un equipo del mismo evento
    public static function participanteTieneEquipoEnEvento($participanteId, $eventoId)
    {
        return self::where('evento_id', $eventoId)
            ->whereHas('participantes', function ($query) use ($participanteId) {
                $query->where('participantes.user_id', $participanteId);
            })
            ->exists();
    }

    // Obtener equipos públicos disponibles de un evento
    public static function equiposPublicosDelEvento($eventoId)
    {
        return self::where('evento_id', $eventoId)
            ->where('es_publico', true)
            ->with(['participantes.user', 'evento'])
            ->get();
    }
}
