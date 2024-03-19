<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'surname',
        'biography',
        'image',
        'birth_date',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }
    
    // Definición de la relación con el campo 'id_usuario_seguido' de la tabla Seguido
    public function getUsuariosQueLeSiguen()
    {
        return $this->hasMany(Seguido::class, 'id_usuario_seguido');
    }


    // Definición de la relación con el campo 'id_usuario' de la tabla Seguido
    public function getUsuarioSeguidos()
    {
        return $this->hasMany(Seguido::class, 'id_usuario');
    }

}

