<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguido extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'id_usuario_seguido'
    ];
}
