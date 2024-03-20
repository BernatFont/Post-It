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

    public function seguidores()
{
    // Relación que obtiene los seguidores del usuario a través de la tabla intermedia Seguido.
    return $this->hasManyThrough(User::class, Seguido::class,
        'id_usuario_seguido', // Clave foránea de la tabla intermedia en Seguido
        'id', // Clave primaria de la tabla de usuarios
        'id', // Clave primaria local en el modelo de usuario
        'id_usuario' // Clave foránea local en el modelo de usuario
    );
}

public function seguidos()
{
    // Relación que obtiene los usuarios seguidos por el usuario a través de la tabla intermedia Seguido.
    return $this->hasManyThrough(User::class, Seguido::class,
        'id_usuario', // Clave foránea local en la tabla intermedia en Seguido
        'id', // Clave primaria de la tabla de usuarios
        'id', // Clave primaria local en el modelo de usuario
        'id_usuario_seguido' // Clave foránea en la tabla de usuarios en Seguido
    );
}


}

