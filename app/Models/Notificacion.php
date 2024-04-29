<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_remitente',
        'id_destinatario',
        'id_contenido',
        'tipo_interaccion',
        'leida'
    ];

    // Relacion polimorfica
    public function contenido()
    {
        return $this->morphTo();
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_remitente'); 
        
    }
    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_destinatario'); 
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_contenido');
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'id_contenido');
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }
}
