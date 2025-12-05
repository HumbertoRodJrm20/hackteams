<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use SoftDeletes;

    protected $table = 'eventos';
    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'estado', 'max_equipos'];

    // Relación con participantes (N:M)
    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'evento_participante', 'evento_id', 'participante_id')
            ->withTimestamps();
    }

    // Relación con proyectos (1:N)
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'evento_id');
    }

    // Relación con criterios de evaluación (1:N)
    public function criterios()
    {
        return $this->hasMany(CriterioEvaluacion::class, 'evento_id');
    }

    // Verificar si un usuario está unido al evento
    public function hasParticipante($userId)
    {
        return $this->participantes()->where('participantes.user_id', $userId)->exists();
    }
}
