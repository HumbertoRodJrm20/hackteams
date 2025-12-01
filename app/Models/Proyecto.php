<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';
    protected $fillable = ['equipo_id', 'evento_id', 'titulo', 'resumen', 'link_repositorio', 'estado'];

    // Relación con equipo (N:1)
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    // Relación con evento (N:1)
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
