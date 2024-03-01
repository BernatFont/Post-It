<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_remitente',
        'id_destinatario',
        'contenido'
    ];

    public function notificacions()
    {
        return $this->morphMany(Notificacion::class, 'contenido');
    }
}
