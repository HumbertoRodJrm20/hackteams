<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    protected $table = 'constancias';

    protected $fillable = [
        'participante_id',
        'evento_id',
        'tipo',             // ✅ Usamos el nombre REAL de la DB (de la migración)
        'archivo_path',     // ✅ Usamos el nombre REAL de la DB (de la migración)
        'codigo_qr',        // Columna agregada en la migración
        // NOTA: 'tipo_constancia', 'ruta_archivo' y 'generada_por_admin' se eliminan de fillable porque NO existen en la DB.
    ];

    // =================================================================
    // ACCESSORES (Getters) para mantener la compatibilidad con el código existente
    // Esto permite que el código use $constancia->tipo_constancia y $constancia->ruta_archivo
    // =================================================================
    
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
        // 🚨 CRÍTICO: Si Participante usa user_id como clave primaria, es mejor especificar la FK.
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