<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calificacion extends Model
{
    use SoftDeletes;

    protected $table = 'calificaciones';
    protected $fillable = ['proyecto_id', 'juez_id', 'puntaje', 'comentarios'];

    // Relación con proyecto (N:1)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    // Relación con juez (N:1)
    public function juez()
    {
        return $this->belongsTo(User::class, 'juez_id');
    }
}
