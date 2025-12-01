<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participante extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    
    // Asignación masiva solo para los campos que podemos tener al registrar
    protected $fillable = [
        'user_id',
        'carrera_id', // Si usas esta FK, el modelo Carrera.php debe existir.
        'matricula',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con eventos (N:M)
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_participante', 'participante_id', 'evento_id')
            ->withTimestamps();
    }
}
