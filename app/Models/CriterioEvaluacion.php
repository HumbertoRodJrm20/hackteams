<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CriterioEvaluacion extends Model
{
    use SoftDeletes;

    protected $table = 'criterio_evaluacion';

    protected $fillable = ['evento_id', 'nombre', 'ponderacion'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'criterio_id');
    }
}
