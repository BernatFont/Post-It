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
}
