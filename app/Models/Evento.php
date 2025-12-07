<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use SoftDeletes;

    protected $table = 'eventos';

    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'estado', 'max_equipos', 'imagen'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

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

    // Relación con equipos (1:N)
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'evento_id');
    }

    // Relación con criterios de evaluación (1:N)
    public function criterios()
    {
        return $this->hasMany(CriterioEvaluacion::class, 'evento_id');
    }

    // Relación con constancias (1:N)
    public function constancias()
    {
        return $this->hasMany(Constancia::class, 'evento_id');
    }

    // Verificar si un usuario está unido al evento
    public function hasParticipante($userId)
    {
        return $this->participantes()->where('participantes.user_id', $userId)->exists();
    }
}
