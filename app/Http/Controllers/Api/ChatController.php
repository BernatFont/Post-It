<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Models\Media;


class ChatController extends Controller
{
    // Funcion que devuelve todos los chats que tiene el usuario logeado.
    public function index()
    {
        $userLogeado = auth()->id();
        $chats = Chat::with(['user1', 'user2', 'user1.media', 'user2.media'])->where('user_id_1', $userLogeado)->orWhere('user_id_2', $userLogeado)->get();
    
        return $chats;
    }

    // Funcion que crea y almacena un nuevo chat.
    public function store($id, Request $request)
    {
        $userLogeado = auth()->id();
        $chat1 = Chat::where('user_id_1', $userLogeado)->where('user_id_2', $id)->first();
        $chat2 = Chat::where('user_id_1', $id)->where('user_id_2', $userLogeado)->first();
    
        // Si el chat ya existe en alguna dirección, devuelve false
        if ($chat1) {
            return response()->json(['chat_id' => $chat1->id]);
        } elseif ($chat2) {
            return response()->json(['chat_id' => $chat2->id]);
        } else {
            // Si el chat no existe, crea el chat, guarda los valores en la variable newChat y envia en el return el id
            $newChat = Chat::create([
                'user_id_1' => $userLogeado,
                'user_id_2' => $id
            ]);
            return response()->json(['chat_id' => $newChat->id]);
        }
    }

    // Funcion que obtiene un chat en base a un id.
    public function obtenerChat($id, Request $request) {
        $chat = Chat::find($id);
    
        // Verifica si el chat existe
        if (!$chat) {
            return response()->json(['error' => 'Chat no encontrado'], 404);
        } else {
            $chat = Chat::with(['user1', 'user2', 'user1.media', 'user2.media'])->where('id', $id)->get();
        }
        // Devuelve solo el chat encontrado por su ID
        return response()->json($chat);
    }

}
