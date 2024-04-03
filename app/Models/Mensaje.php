<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'contenido'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); 
    }
}
