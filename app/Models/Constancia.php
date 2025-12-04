<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    protected $table = 'constancias';

    protected $fillable = [
        'participante_id',
        'evento_id',
        'tipo',
        'archivo_path',
        'codigo_qr',
    ];

    // Devuelve el valor de la columna 'tipo' al solicitar 'tipo_constancia'
    public function getTipoConstanciaAttribute()
    {
        return $this->attributes['tipo'];
    }

    // Devuelve el valor de la columna 'archivo_path' al solicitar 'ruta_archivo'
    public function getRutaArchivoAttribute()
    {
        return $this->attributes['archivo_path'];
    }
    
    /**
     * Relación: Una constancia pertenece a un Participante.
     */
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'user_id');
    }

    /**
     * Relación: Una constancia pertenece a un Evento.
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}