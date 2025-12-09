<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'categoria_id');
    }
}
