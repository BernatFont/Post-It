<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

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

}
