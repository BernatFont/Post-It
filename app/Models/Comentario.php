<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_publicacion',
        'id_usuario',
        'contenido'
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'id_publicacion', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
    
    public function notificacions()
    {
        return $this->morphMany(Notificacion::class, 'contenido');
    }
}
