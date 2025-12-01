<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calificacion extends Model
{
    use SoftDeletes;

    protected $table = 'calificaciones';

    protected $fillable = [
        'proyecto_id',
        'juez_user_id',
        'criterio_id',
        'puntuacion',
    ];

    // Relaciones (opcionales, pero buenas prácticas)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function juez()
    {
        // Asumiendo que 'juez_user_id' se relaciona con 'users.id'
        return $this->belongsTo(User::class, 'juez_user_id'); 
    }

    public function criterio()
    {
        return $this->belongsTo(CriterioEvaluacion::class, 'criterio_id');
    }
}