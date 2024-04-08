<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_1',
        'user_id_2'
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id_1', 'id'); 
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id_2', 'id'); 
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'chat_id', 'id'); 
    }
}
