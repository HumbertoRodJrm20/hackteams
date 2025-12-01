<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avance extends Model
{
    use SoftDeletes;

    protected $table = 'avances';
    protected $fillable = ['proyecto_id', 'descripcion', 'fecha'];
    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación con proyecto (N:1)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
