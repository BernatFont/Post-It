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
        return $this->belongsTo(User::class, 'id_remitente', 'id'); 
        
    }
    public function user2()
    {
        return $this->belongsTo(User::class, 'id_destinatario', 'id'); 
    }

    public function comentario()
    {
        return $this->belongsTo(Comentario::class, 'id_contenido', 'id');
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'id_contenido', 'id');
    }
}
