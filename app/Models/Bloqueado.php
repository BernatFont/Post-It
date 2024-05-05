<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloqueado extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'id_usuario_bloqueado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
