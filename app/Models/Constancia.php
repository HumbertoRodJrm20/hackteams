<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    protected $table = 'constancias';

    protected $fillable = [
        'participante_id',
        'evento_id',
        'tipo_constancia', // 'participacion', 'ganador'
        'ruta_archivo',    
        'generada_por_admin', 
    ];

    /**
     * Relación: Una constancia pertenece a un Participante.
     */
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id');
    }

    /**
     * Relación: Una constancia pertenece a un Evento.
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}