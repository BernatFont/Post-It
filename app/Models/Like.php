<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'id_publicacion'
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'id_publicacion', 'id');
    }
    
    public function notificacions()
    {
        return $this->morphMany(Notificacion::class, 'contenido');
    }
}

