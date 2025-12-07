<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // 🛑 CRUCIAL: Nombre de la tabla de Roles
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Define la relación de muchos a muchos con el modelo User.
     */
    public function users()
    {
        // El segundo parámetro es el nombre de la tabla pivote.
        return $this->belongsToMany(User::class, 'user_rol', 'rol_id', 'user_id');
    }
}
