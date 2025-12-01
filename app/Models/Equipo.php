<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $table = 'equipos';
    protected $fillable = ['nombre', 'logo_path'];

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
}
