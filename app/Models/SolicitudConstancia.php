<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudConstancia extends Model
{
    protected $table = 'solicitudes_constancias';

    protected $fillable = [
        'participante_id',
        'evento_id',
        'rol',
        'fecha_evento',
        'tipo',
        'motivo',
        'comentario',
        'evidencia_path',
        'estatus',
    ];

    // Relación con usuario (participante)
    public function participante()
    {
        return $this->belongsTo(User::class, 'participante_id');
    }

    // Relación con evento
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
