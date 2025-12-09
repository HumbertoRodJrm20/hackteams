<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudEquipo extends Model
{
    protected $table = 'solicitudes_equipo';

    protected $fillable = [
        'equipo_id',
        'participante_id',
        'tipo',
        'estado',
        'mensaje',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relación con equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    // Relación con participante
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'user_id');
    }

    // Verificar si es una solicitud (participante quiere unirse)
    public function esSolicitud()
    {
        return $this->tipo === 'solicitud';
    }

    // Verificar si es una invitación (líder invita)
    public function esInvitacion()
    {
        return $this->tipo === 'invitacion';
    }

    // Verificar si está pendiente
    public function estaPendiente()
    {
        return $this->estado === 'pendiente';
    }
}
