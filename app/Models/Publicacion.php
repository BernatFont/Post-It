<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Publicacion extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'id_usuario',
        'texto'
    ];

    //Obtenemos todos los datos del usuario pasandole su id, para mostrarlo en el post
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function like()
    {
        return $this->belongsTo(Like::class, 'id_publicacion', 'id'); 
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'id_publicacion');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_publicacion');
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
