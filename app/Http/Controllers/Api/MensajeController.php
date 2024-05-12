<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mensaje;

class MensajeController extends Controller
{
    public function index($chatId)
    {
        $mensajes = Mensaje::with('user')->where('chat_id', $chatId)->get();
    
        return $mensajes;
    }

    // Esta funcion añade un nuevo mensaje a un chat.
    public function store(Request $request, $chatId)
    {
        // Validación de los datos del mensaje
        $request->validate([
            'user_id' => 'required',
            'contenido' => 'required|string|max:255',
        ]);

        // Crear el mensaje
        $mensaje = Mensaje::create([
            'chat_id' => $chatId,
            'user_id' => $request->user_id,
            'contenido' => $request->contenido,
        ]);
    }
}
