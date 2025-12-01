<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        // 🛑 CRUCIAL: El segundo parámetro es el nombre de la tabla pivote.
        return $this->belongsToMany(Rol::class, 'user_rol');
    }

    public function participante()
    {
        return $this->hasOne(Participante::class, 'user_id');
    }
    
    // Función de ayuda para vistas/middleware
    public function hasRole($role)
    {
        return $this->roles()->where('nombre', $role)->exists();
    }

    
}
