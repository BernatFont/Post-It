<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

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

    public function seguidos(){
        return $this->belongsToMany(User::class, 'user_user','id_usuario', 'id_usuario_seguido');
    }
    
    public function seguidores(){
        return $this->belongsToMany(User::class,'user_user','id_usuario_seguido','id_usuario');
    }
    
    public function bloqueados(){
        return $this->belongsToMany(User::class, 'user_blocks','id_usuario', 'id_usuario_bloqueado');
    }
    
    public function bloqueado(){
        return $this->belongsToMany(User::class,'user_blocks','id_usuario_bloqueado','id_usuario');
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'id_usuario');
    }

    /**
     * Define las colecciones de medios para el modelo.
     */
    public function registerMediaCollections(): void
    {
        // Registra una colección de medios para imágenes relacionadas con publicaciones
        $this->addMediaCollection('images/publicacion')
            // Establece una URL de respaldo para la colección en caso de que no haya ningún medio disponible
            ->useFallbackUrl('/images/placeholder.jpg')
            // Establece una ruta de respaldo para la colección en caso de que no haya ningún medio disponible
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if (env('RESIZE_IMAGE') === true) {

            $this->addMediaConversion('resized-image')
                ->width(env('IMAGE_WIDTH', 300))
                ->height(env('IMAGE_HEIGHT', 300));
        }
    }
}

