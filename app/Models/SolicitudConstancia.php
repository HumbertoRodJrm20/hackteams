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
        'evidencia_path',
        'datos_personalizados',
        'comentario',
        'estatus',
        'respuesta_admin',
        'admin_id'
    ];

    public function participante() {
        return $this->belongsTo(User::class, 'participante_id');
    }

    public function evento() {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

